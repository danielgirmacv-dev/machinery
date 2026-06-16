# Deploy to cPanel from GitHub

This guide covers deploying the **EEC Machine Management System** (Laravel 11) from GitHub to cPanel.

**Repository:** https://github.com/danielgirmacv-dev/machinery

---

## Requirements

| Requirement | Version |
|-------------|---------|
| PHP | 8.2 or higher |
| MySQL | 8.0+ |
| Composer | 2.x |
| Node.js + npm | 18+ (only if you build assets on the server) |
| cPanel | Git Version Control (recommended) or SSH/Terminal |

**Required PHP extensions:** `bcmath`, `ctype`, `curl`, `dom`, `fileinfo`, `json`, `mbstring`, `openssl`, `pdo`, `pdo_mysql`, `tokenizer`, `xml`

In cPanel → **Select PHP Version** → enable the extensions above.

---

## Recommended server layout

Keep the Laravel app **outside** `public_html` and only expose the web root:

```
/home/youruser/
├── repositories/
│   └── machinery/              ← Git clone (this repo)
│       ├── app/
│       ├── bootstrap/
│       ├── config/
│       ├── deploy/
│       ├── public/
│       │   ├── build/          ← Vite assets
│       │   └── images/
│       ├── resources/
│       ├── storage/
│       ├── vendor/
│       ├── .env                ← production config (not in GitHub)
│       └── artisan
│
└── public_html/                ← web root (your domain)
    ├── index.php               ← points to ../repositories/machinery
    ├── .htaccess
    ├── build/                  ← symlink to repo public/build
    └── images/                 ← symlink to repo public/images
```

> **Why?** Laravel’s `vendor/`, `.env`, and `storage/` must never be web-accessible.

---

## Option A — cPanel Git Version Control (recommended)

### 1. Create the database

1. cPanel → **MySQL Databases**
2. Create a database, e.g. `youruser_machinery`
3. Create a MySQL user with a strong password
4. Add the user to the database with **ALL PRIVILEGES**

### 2. Clone from GitHub

1. cPanel → **Git Version Control** → **Create**
2. Clone URL: `https://github.com/danielgirmacv-dev/machinery.git`
3. Repository path: `/home/youruser/repositories/machinery`
4. Leave **Deploy** unchecked for the first clone (set up `.env` first)

### 3. Configure production `.env`

SSH or cPanel **Terminal**:

```bash
cd ~/repositories/machinery
cp .env.example .env
nano .env
```

Set at minimum:

```env
APP_NAME="Machine Management System"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

APP_KEY=                        # generate in step 4

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=youruser_machinery
DB_USERNAME=youruser_dbuser
DB_PASSWORD=your_db_password

SESSION_DRIVER=file
CACHE_DRIVER=file
QUEUE_CONNECTION=sync

# Optional: Cloudflare Turnstile (login CAPTCHA)
TURNSTILE_SITE_KEY=
TURNSTILE_SECRET_KEY=
```

### 4. First-time install

```bash
cd ~/repositories/machinery

# PHP & Composer (use cPanel's composer path if needed)
composer install --no-dev --optimize-autoloader
php artisan key:generate
php artisan migrate --force
php artisan db:seed --force          # optional: demo data

# Permissions
chmod -R 775 storage bootstrap/cache
```

If your host uses a specific PHP binary:

```bash
/usr/local/bin/ea-php82 artisan migrate --force
```

### 5. Set up `public_html`

```bash
cd ~/repositories/machinery
bash deploy/setup-public-html.sh
```

Or manually:

```bash
cp deploy/cpanel/public_html/index.php ~/public_html/index.php
cp public/.htaccess ~/public_html/.htaccess
ln -sfn ~/repositories/machinery/public/build ~/public_html/build
ln -sfn ~/repositories/machinery/public/images ~/public_html/images
```

Edit `~/public_html/index.php` if your repo path is not `repositories/machinery`.

### 6. Enable automatic deploys

1. cPanel → **Git Version Control** → your repo → **Manage**
2. Enable **Pull & Deploy** (or add a deployment script)
3. Set deployment script to:

```bash
bash deploy/post-deploy.sh
```

Or create `.cpanel.yml` in the repo root (already included) — cPanel runs it after each pull.

### 7. Verify

- Visit `https://yourdomain.com`
- Login page should load with styles (if CSS is missing, see [Troubleshooting](#troubleshooting))
- Health check: `https://yourdomain.com/up`

---

## Option B — Manual deploy via SSH

Use this if Git Version Control is not available.

```bash
# First time
cd ~
mkdir -p repositories
cd repositories
git clone https://github.com/danielgirmacv-dev/machinery.git
cd machinery

cp .env.example .env
# edit .env (see above)

composer install --no-dev --optimize-autoloader
php artisan key:generate
php artisan migrate --force
bash deploy/setup-public-html.sh
bash deploy/post-deploy.sh
```

**Every update after pushing to GitHub:**

```bash
cd ~/repositories/machinery
git pull origin main
bash deploy/post-deploy.sh
```

---

## Option C — Point document root to `public/` (simplest, if allowed)

Some hosts let you change the domain document root in cPanel → **Domains** → **Document Root**.

Set it to:

```
/home/youruser/repositories/machinery/public
```

Then you only need:

```bash
cd ~/repositories/machinery
git pull
bash deploy/post-deploy.sh
```

No `public_html/index.php` bridge required.

---

## Building frontend assets

Assets are pre-built in `public/build/` and committed to the repo. After `git pull`, they are ready to use.

To rebuild (locally or on server):

```bash
npm ci
npm run build
```

If you build locally, commit and push the updated `public/build/` files:

```bash
npm run build
git add public/build
git commit -m "Rebuild production assets"
git push
```

---

## Post-deploy script

`deploy/post-deploy.sh` runs after every deployment:

- `composer install --no-dev`
- `php artisan migrate --force`
- `php artisan config:cache`
- `php artisan route:cache`
- `php artisan view:cache`
- Fixes `storage/` and `bootstrap/cache/` permissions

Run manually anytime:

```bash
cd ~/repositories/machinery
bash deploy/post-deploy.sh
```

---

## Production checklist

- [ ] `APP_ENV=production`
- [ ] `APP_DEBUG=false`
- [ ] `APP_URL` matches your real domain (with `https://`)
- [ ] Database credentials are correct
- [ ] `php artisan key:generate` has been run
- [ ] Migrations applied (`php artisan migrate --force`)
- [ ] `public_html/index.php` points to the correct repo path
- [ ] `public_html/build` symlink exists
- [ ] `storage/` and `bootstrap/cache/` are writable (775)
- [ ] PHP version is 8.2+
- [ ] SSL certificate is active (cPanel → SSL/TLS)

---

## Troubleshooting

### 500 Internal Server Error

```bash
cd ~/repositories/machinery
tail -50 storage/logs/laravel.log
php artisan config:clear
php artisan cache:clear
```

Check `storage/` and `bootstrap/cache/` permissions:

```bash
chmod -R 775 storage bootstrap/cache
```

### Page loads but no CSS / broken layout

Vite assets are missing from the web root.

```bash
ln -sfn ~/repositories/machinery/public/build ~/public_html/build
```

Verify `https://yourdomain.com/build/manifest.json` returns JSON.

### `composer: command not found`

Use cPanel’s Composer path, often:

```bash
/usr/local/bin/composer install --no-dev --optimize-autoloader
```

Or install via cPanel → **PHP Composer**.

### `php artisan` uses wrong PHP version

```bash
/usr/local/bin/ea-php82 artisan migrate --force
```

Replace `ea-php82` with your PHP selector binary.

### Database connection refused

- Use `DB_HOST=localhost` (not `127.0.0.1`) on many cPanel hosts
- Confirm the MySQL user is assigned to the database
- Use the full cPanel-prefixed names (`youruser_machinery`, `youruser_dbuser`)

### Background image not showing

Copy a `background.png` into the public web root:

```bash
cp /path/to/background.png ~/public_html/background.png
```

### After deploy, changes not visible

```bash
php artisan config:clear
php artisan view:clear
php artisan cache:clear
php artisan config:cache
php artisan view:cache
```

---

## Quick reference

| Task | Command |
|------|---------|
| Pull latest code | `cd ~/repositories/machinery && git pull` |
| Deploy | `bash deploy/post-deploy.sh` |
| Run migrations | `php artisan migrate --force` |
| Clear all caches | `php artisan optimize:clear` |
| View logs | `tail -f storage/logs/laravel.log` |
| Maintenance mode on | `php artisan down` |
| Maintenance mode off | `php artisan up` |

---

## Security notes

- Never commit `.env` to GitHub
- Keep `APP_DEBUG=false` in production
- Restrict `storage/` and `vendor/` from web access (default layout handles this)
- Use strong database and admin passwords
- Enable HTTPS and force SSL in cPanel
