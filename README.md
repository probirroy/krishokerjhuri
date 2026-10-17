# Krishoker Jhuri

A Laravel storefront for farm-fresh groceries, with product/category management, site content editing, an administrator area, and cash-on-delivery ordering.

## Requirements

- PHP 8.2 or later with Laravel's required extensions
- Composer
- Node.js and npm
- SQLite for local development, or MySQL 8 for production

## Local setup

1. Install PHP and Composer dependencies: `composer install`
2. Copy `.env.example` to `.env`, then set `APP_KEY` with `php artisan key:generate`.
3. Configure the database in `.env`. SQLite is configured by default; create `database/database.sqlite` if it does not exist.
4. Set `ADMIN_NAME`, `ADMIN_EMAIL`, and a unique strong `ADMIN_PASSWORD` in `.env`. Do not reuse a shared or example password.
5. Run `php artisan migrate --seed` to create the schema, sample catalog, content, and admin account.
6. Run `php artisan storage:link` so uploaded product, logo, and banner images are publicly available.
7. Install and compile frontend assets with `npm install` and `npm run build`.
8. Start the local web server with `php artisan serve`.

In **Admin → Products**, set a cover image and optionally select up to 10 additional product photos. Product pages display the photos as a thumbnail gallery, and hovering over the main photo zooms it in.

## Install on XAMPP (Windows)

### 1. Install and check the tools

Install XAMPP with Apache and MySQL/MariaDB, Composer, and Node.js (which includes npm). Use PHP 8.2 or later. In the XAMPP Control Panel, start **MySQL**; start **Apache** too if you want to use the Apache virtual host below. Enable PHP's `pdo_mysql` extension in the `php.ini` used by XAMPP, then restart Apache.

In PowerShell, check that the command-line PHP and Composer are available:

```powershell
php -v
php -m
composer --version
node --version
npm --version
```

Make sure command-line PHP is the XAMPP PHP installation, or use its full path (for example, `C:\xampp\php\php.exe artisan ...`) consistently. Laravel needs its standard PHP extensions, including `ctype`, `curl`, `dom`, `fileinfo`, `mbstring`, `openssl`, `pdo`, `tokenizer`, and `xml`; MySQL requires `pdo_mysql`.

### 2. Put the project in the XAMPP web directory

Copy or clone the project to `C:\xampp\htdocs\krishokerjhuri`, then open PowerShell in that folder:

```powershell
Set-Location C:\xampp\htdocs\krishokerjhuri
composer install
npm ci
```

Do not put the project itself in a publicly hosted folder on a live server; the XAMPP virtual host below points only to Laravel's `public` directory.

### 3. Create the local database and configure the app

Open `http://localhost/phpmyadmin`, create a database named `krishoker_jhuri` using the `utf8mb4_unicode_ci` collation, and set the matching values in the project's `.env` file. If `.env` does not exist, create it from `.env.example`:

```powershell
Copy-Item .env.example .env
```

Set these local development values in `.env`:

```dotenv
APP_NAME="Krishoker Jhuri"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://krishokerjhuri.test

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=krishoker_jhuri
DB_USERNAME=root
DB_PASSWORD=

ADMIN_NAME="Store Administrator"
ADMIN_EMAIL=your-admin@example.com
ADMIN_PASSWORD=replace-with-a-unique-strong-password
```

If you set a MySQL root password in XAMPP, put it in `DB_PASSWORD`. The root account and blank password are for a local development installation only, not production.

### 4. Create tables, sample content, and the local admin

Run the Laravel migrations and local seed data, then create the public storage link:

```powershell
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
```

`migrate --seed` creates the tables, sample catalog and site content, plus a local test account. This is for local development only; see the production instructions below for creating a production administrator without the test account. Do not import `database.sql` as well as running migrations on this fresh database. `database.sql` is an alternative schema import for phpMyAdmin-only workflows.

### 5. Build and run the site

Compile the frontend assets and start Laravel:

```powershell
npm run build
php artisan serve
```

Open the URL printed by `artisan serve` (usually `http://127.0.0.1:8000`). Leave that PowerShell window open while using the site. For local development with hot-reloaded assets, use `npm run dev` in a second terminal instead of `npm run build`.

### 6. (Optional) Serve through XAMPP Apache

The project root is not the Apache document root. Configure a virtual host pointing to `C:\xampp\htdocs\krishokerjhuri\public`:

1. In `C:\xampp\apache\conf\httpd.conf`, make sure the `Include conf/extra/httpd-vhosts.conf` line is enabled.
2. Add this entry to `C:\xampp\apache\conf\extra\httpd-vhosts.conf`:

   ```apache
   <VirtualHost *:80>
       ServerName krishokerjhuri.test
       DocumentRoot "C:\xampp\htdocs\krishokerjhuri\public"
       <Directory "C:\xampp\htdocs\krishokerjhuri\public">
           AllowOverride All
           Require all granted
       </Directory>
   </VirtualHost>
   ```

3. As an administrator, add `127.0.0.1 krishokerjhuri.test` to `C:\Windows\System32\drivers\etc\hosts`.
4. Restart Apache in the XAMPP Control Panel and open `http://krishokerjhuri.test`.
5. Set `APP_URL=http://krishokerjhuri.test` in `.env`. If you change `.env` after caching configuration, run `php artisan optimize:clear`.

If Apache reports a configuration error, check the XAMPP Apache error log and confirm `mod_rewrite` is enabled. `AllowOverride All` is needed for Laravel's `public\.htaccess`. For static-file caching and compression, also enable `mod_expires`, `mod_headers`, and `mod_deflate` in Apache's `httpd.conf`, then restart Apache. These `.htaccess` optimizations do not apply when using `php artisan serve`.

## Deploy to a hosting server

Use a host that supports PHP 8.2+, Laravel 12, Composer (or uploading Composer's `vendor` directory), MySQL/MariaDB, and the PHP extensions listed above. Confirm the host's PHP version and `pdo_mysql` are enabled for both the website and CLI. Shared-host control panels call the document root setting different names; it must point to this project's `public` directory.

### 1. Prepare a production database and domain

1. Point your domain's DNS to the hosting provider and add the domain in its control panel.
2. Create a MySQL/MariaDB database and a dedicated database user with access to that database. Record the database name, username, password, and host supplied by the provider; these may include an account prefix and are often not `127.0.0.1`.
3. Set the domain's document root to the deployed project's `public` directory, for example `/home/ACCOUNT/apps/krishokerjhuri/public`. Keep `.env`, `app`, `vendor`, and the rest of the project outside the public web root. If the provider cannot point a domain to `public`, ask the host for a Laravel-compatible document-root setup instead of exposing the project root.

### 2. Build and upload the application

On a deployment machine with PHP 8.2+, Composer, and Node.js installed, from the project root run:

```sh
composer install --no-dev --optimize-autoloader
npm ci
npm run build
```

Upload the application to the private project directory on the host, including `vendor` and the generated `public/build` directory. Do not upload `node_modules`, `.git`, or a local `.env`. Alternatively, if the host provides SSH and compatible versions of PHP, Composer, and Node.js, run the appropriate install/build commands there. Never use `npm run dev` on the production site.

### 3. Configure production environment

Create `.env` in the private project root on the server. Use the database credentials from the host and set production values; do not copy local credentials or commit this file:

```dotenv
APP_NAME="Krishoker Jhuri"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.example
APP_KEY=

DB_CONNECTION=mysql
DB_HOST=HOST_PROVIDED_BY_YOUR_HOST
DB_PORT=3306
DB_DATABASE=HOST_DATABASE_NAME
DB_USERNAME=HOST_DATABASE_USER
DB_PASSWORD=HOST_DATABASE_PASSWORD

ADMIN_NAME="Store Administrator"
ADMIN_EMAIL=your-admin@example.com
ADMIN_PASSWORD=replace-with-a-unique-strong-password
```

Use the host's PHP CLI to generate `APP_KEY` once, then keep that key unchanged across deployments:

```sh
php artisan key:generate
```

Restrict access to `.env`. Ensure the hosting account's PHP process can write to `storage` and `bootstrap/cache`; use the ownership and permissions recommended by the hosting provider rather than making the whole application world-writable.

### 4. Initialize production

Run these commands from the project root using the hosting provider's terminal or SSH:

```sh
php artisan migrate --force
php artisan db:seed --class=AdminUserSeeder --force
php artisan storage:link
php artisan optimize
```

The production seeder creates the administrator using `ADMIN_NAME`, `ADMIN_EMAIL`, and `ADMIN_PASSWORD`. Do not run the general `db:seed` command on production: it also creates a local test account and demo catalog. Sign in at `https://your-domain.example/admin/login`, then add production categories, products, and site content through the admin dashboard. The checkout currently supports cash on delivery.

Every product receives a unique internal SKU automatically if one is not entered. Add only a barcode value actually assigned to the item; a barcode value is stored as an identifier and is not a generated or printable barcode image. Product editing includes a detailed description and optional SEO title, meta description, and comma-separated keywords. Product pages publish canonical, Open Graph, Twitter, and Schema.org Product metadata.

For an existing demo catalog, optionally populate the professional sample descriptions and SEO defaults for the starter products without replacing custom copy:

```sh
php artisan db:seed --class=ProductCatalogMetadataSeeder --force
```

### 5. Enable HTTPS and verify

Enable the host's SSL certificate and force HTTPS using the host's recommended setting. Confirm the storefront and `/admin/login` load over HTTPS, sign in as the new administrator, create a test product, and verify its image can be uploaded and displayed. Check the hosting error log if a page returns an error; keep `APP_DEBUG=false` on the public server.

### Production performance settings

- Keep Apache `mod_deflate`, `mod_expires`, and `mod_headers` enabled and allow `.htaccess` overrides. `public/.htaccess` compresses text responses, gives uploaded/static images and fonts a one-month cache, and marks fingerprinted Vite assets immutable for one year. If the host uses Nginx, configure equivalent gzip/Brotli and cache headers in the virtual-host configuration; `.htaccess` is not read by Nginx.
- Enable PHP OPcache in the hosting control panel or PHP-FPM configuration, and verify it is enabled for the web PHP runtime. Keep `APP_DEBUG=false` and run `php artisan optimize` for production deployments.
- Put a CDN in front of the site for edge delivery of `/build`, `/images`, `/fonts`, and `/storage`. Do not publicly cache HTML responses without a session-aware cache policy: storefront output can vary by language, cart session, and authentication.
- Keep `npm run build` output deployed from `public/build`; those hashed files are safe to cache immutably. Rebuild and deploy them together whenever frontend assets change.

## Updating and maintaining a deployment

For each production update:

1. Back up the database and uploaded files in `storage/app/public`.
2. Upload the new application code without replacing the server's `.env`, user uploads, or generated `APP_KEY`.
3. If dependencies changed, run `composer install --no-dev --optimize-autoloader`. If files in `resources` or the Vite configuration changed, rebuild with `npm ci` and `npm run build`, then upload the new `public/build` directory.
4. Apply database changes and refresh Laravel's optimized caches:

   ```sh
   php artisan migrate --force
   php artisan optimize
   ```

5. Check the storefront, admin login, checkout, and image uploads. If you need to roll back, restore the matching database and uploaded-file backups as well as the previous code release.

Do not run `migrate:fresh` or `db:wipe` on a live store; these commands delete data. For local development, `php artisan optimize:clear` removes cached configuration and routes, and `php artisan test` runs the project's tests.

Admin sign-in is at `/admin/login` and is not linked from customer pages. The first administrator is created by the install-time `php artisan db:seed --class=AdminUserSeeder` command using the private values in `.env`; there is no public admin sign-up and no universal default email/password. Existing administrators can grant/revoke administrator access for registered accounts from **Admin access** in the dashboard. Target accounts must have an email address to use the separate admin sign-in. The app prevents an administrator from removing their own access or removing the last administrator.

Customer accounts are available at `/register` and `/login`. Customers can register with an email address, mobile number, or both, then sign in with either one. Customer registration cannot grant administrator permissions; administrator sign-in and sign-out remain separate.

## Store features

- Public storefront, category filters, product detail pages, and responsive light/dark themes
- English/Bangla storefront language switch with the supplied SolimanLipi font for Bangla UI
- Session cart with stock checks and database-authoritative checkout prices
- Customer account registration using email, mobile number, or both; sign-in accepts either identifier
- Cash-on-delivery checkout, order confirmation, stock decrement, and order status management
- Separate administrator authentication at `/admin/login`
- Admin dashboard for products, categories, orders, homepage copy, logo/banner/farmer images, and service-benefit text
- Image uploads are stored on Laravel's public filesystem disk

Payment gateway processing, customer account profiles/order history, and automated delivery integration are not configured in this starter application.
# krishokerjhuri
