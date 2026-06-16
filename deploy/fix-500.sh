#!/usr/bin/env bash
set -euo pipefail

# Emergency fix for HTTP 500 on cPanel.
# Run on the server: bash deploy/fix-500.sh

APP_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "$APP_DIR"

PHP_BIN="${PHP_BIN:-php}"
if command -v ea-php82 >/dev/null 2>&1; then
    PHP_BIN="ea-php82"
elif command -v ea-php83 >/dev/null 2>&1; then
    PHP_BIN="ea-php83"
fi

COMPOSER_BIN="${COMPOSER_BIN:-composer}"
if [ -x /usr/local/bin/composer ]; then
    COMPOSER_BIN="/usr/local/bin/composer"
fi

echo "============================================"
echo " Laravel 500 diagnostic — $APP_DIR"
echo "============================================"
echo ""

# 1. PHP version
echo "▶ PHP version (need 8.2+):"
$PHP_BIN -v | head -1
echo ""

# 2. Required files
echo "▶ Checking required files..."
for f in .env vendor/autoload.php bootstrap/app.php artisan; do
    if [ -e "$f" ]; then
        echo "  ✓ $f"
    else
        echo "  ✗ MISSING: $f"
    fi
done
echo ""

# 3. APP_KEY
if [ -f .env ]; then
    if grep -q '^APP_KEY=base64:' .env; then
        echo "▶ APP_KEY: ✓ set"
    else
        echo "▶ APP_KEY: ✗ missing — generating..."
        $PHP_BIN artisan key:generate --force
    fi
else
    echo "▶ .env: ✗ MISSING — copy .env.example to .env and configure it"
    exit 1
fi
echo ""

# 4. Permissions
echo "▶ Fixing storage & cache permissions..."
chmod -R 775 storage bootstrap/cache 2>/dev/null || true
echo ""

# 5. Clear ALL stale caches (most common 500 fix)
echo "▶ Clearing stale caches..."
$PHP_BIN artisan optimize:clear 2>/dev/null || true
echo ""

# 6. Composer
if [ ! -d vendor ] || [ ! -f vendor/autoload.php ]; then
    echo "▶ Installing Composer dependencies..."
    $COMPOSER_BIN install --no-dev --optimize-autoloader --no-interaction
fi
echo ""

# 7. Database test
echo "▶ Testing database connection..."
if $PHP_BIN artisan migrate:status >/dev/null 2>&1; then
    echo "  ✓ Database connection OK"
    echo "▶ Running migrations..."
    $PHP_BIN artisan migrate --force
else
    echo "  ✗ Database connection FAILED"
    echo "    Check DB_HOST, DB_DATABASE, DB_USERNAME, DB_PASSWORD in .env"
    echo "    On cPanel, try DB_HOST=localhost instead of 127.0.0.1"
fi
echo ""

# 8. Rebuild caches
echo "▶ Rebuilding production caches..."
$PHP_BIN artisan config:cache
$PHP_BIN artisan route:cache
$PHP_BIN artisan view:cache
echo ""

# 9. public_html links
if [ -d "$HOME/public_html" ]; then
    echo "▶ Checking public_html symlinks..."
    ln -sfn "$APP_DIR/public/build" "$HOME/public_html/build" 2>/dev/null && echo "  ✓ build symlink" || echo "  ✗ build symlink failed"
    ln -sfn "$APP_DIR/public/images" "$HOME/public_html/images" 2>/dev/null && echo "  ✓ images symlink" || echo "  ✗ images symlink failed"
fi
echo ""

# 10. Last error from log
LOG="$APP_DIR/storage/logs/laravel.log"
if [ -f "$LOG" ]; then
    echo "▶ Last error in laravel.log:"
    echo "--------------------------------------------"
    grep -m1 'local.ERROR\|production.ERROR' "$LOG" 2>/dev/null | tail -1 || tail -3 "$LOG"
    echo "--------------------------------------------"
    echo "  Full log: tail -50 $LOG"
else
    echo "▶ No laravel.log yet — error may be in index.php path or PHP version"
fi
echo ""
echo "Done. Reload https://your-domain.com"
