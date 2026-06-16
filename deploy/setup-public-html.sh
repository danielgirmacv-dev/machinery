#!/usr/bin/env bash
set -euo pipefail

# One-time setup: wire public_html to the Laravel app.
# Usage: bash deploy/setup-public-html.sh

APP_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
PUBLIC_HTML="${PUBLIC_HTML:-$HOME/public_html}"
REPO_NAME="${REPO_NAME:-machinery}"

echo "==> App directory:  $APP_DIR"
echo "==> Public HTML:    $PUBLIC_HTML"

mkdir -p "$PUBLIC_HTML"

# Write index.php with the correct relative path from public_html to the repo
INDEX_FILE="$PUBLIC_HTML/index.php"
sed "s|__REPO_PATH__|../repositories/$REPO_NAME|g" \
    "$APP_DIR/deploy/cpanel/public_html/index.php" > "$INDEX_FILE"

cp "$APP_DIR/public/.htaccess" "$PUBLIC_HTML/.htaccess"

ln -sfn "$APP_DIR/public/build" "$PUBLIC_HTML/build"
ln -sfn "$APP_DIR/public/images" "$PUBLIC_HTML/images"

echo "==> Created:  $INDEX_FILE"
echo "==> Linked:   $PUBLIC_HTML/build -> $APP_DIR/public/build"
echo "==> Linked:   $PUBLIC_HTML/images -> $APP_DIR/public/images"
echo ""
echo "If your repo is NOT at ~/repositories/$REPO_NAME, edit $INDEX_FILE manually."
echo "Done."
