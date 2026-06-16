#!/usr/bin/env bash
set -euo pipefail

# Run from the Laravel project root after git pull.
# Usage: bash deploy/post-deploy.sh

APP_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "$APP_DIR"

echo "==> Deploying from: $APP_DIR"

# Detect PHP binary (cPanel often needs a specific version)
PHP_BIN="${PHP_BIN:-php}"
if command -v ea-php82 >/dev/null 2>&1; then
    PHP_BIN="ea-php82"
fi

# Detect Composer
COMPOSER_BIN="${COMPOSER_BIN:-composer}"
if [ -x /usr/local/bin/composer ]; then
    COMPOSER_BIN="/usr/local/bin/composer"
fi

echo "==> PHP: $($PHP_BIN -v | head -1)"
echo "==> Composer: $COMPOSER_BIN"

echo "==> Installing PHP dependencies..."
$COMPOSER_BIN install --no-dev --optimize-autoloader --no-interaction

if [ ! -f .env ]; then
    echo "ERROR: .env not found. Copy .env.example to .env and configure it first."
    exit 1
fi

if ! grep -q '^APP_KEY=base64:' .env 2>/dev/null; then
    echo "==> Generating application key..."
    $PHP_BIN artisan key:generate --force
fi

echo "==> Running migrations..."
$PHP_BIN artisan migrate --force

echo "==> Caching config, routes, and views..."
$PHP_BIN artisan config:cache
$PHP_BIN artisan route:cache
$PHP_BIN artisan view:cache

echo "==> Fixing permissions..."
chmod -R 775 storage bootstrap/cache 2>/dev/null || true

# Re-link public assets if public_html setup exists
if [ -d "$HOME/public_html" ]; then
    echo "==> Linking public assets to public_html..."
    ln -sfn "$APP_DIR/public/build" "$HOME/public_html/build" 2>/dev/null || true
    ln -sfn "$APP_DIR/public/images" "$HOME/public_html/images" 2>/dev/null || true
fi

echo "==> Deploy complete."
