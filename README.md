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

## XAMPP and MySQL setup

1. Install XAMPP with Apache and MySQL/MariaDB, then start **MySQL** in the XAMPP Control Panel. For local development, the app can be served with `php artisan serve`; Apache is optional.
2. In phpMyAdmin, create and select a database named `krishoker_jhuri`, then import the project-root `database.sql` file. It creates the MySQL tables and records the matching Laravel migrations; it does not contain administrator credentials or sample catalog data.
3. Set the database values in `.env` to match your XAMPP MySQL configuration:

   ```dotenv
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=krishoker_jhuri
   DB_USERNAME=root
   DB_PASSWORD=
   ```

   Set `DB_PASSWORD` to your MySQL password if you configured one.
4. Set `ADMIN_NAME`, `ADMIN_EMAIL`, and a unique strong `ADMIN_PASSWORD` in `.env`, then run `php artisan db:seed` to add the sample catalog and configured administrator.
5. Run `php artisan storage:link`, build frontend assets with `npm install` and `npm run build`, and start the site with `php artisan serve`.

To serve through XAMPP Apache instead, configure an Apache virtual host whose document root is the project's `public` directory; do not expose the Laravel project root. The project must also use a PHP installation with the required Laravel extensions, including `pdo_mysql`.

Admin sign-in is at `/admin/login` and is not linked from customer pages. The first administrator is created by the install-time `php artisan db:seed --class=AdminUserSeeder` command using the private values in `.env`; there is no public admin sign-up and no universal default email/password. Existing administrators can grant/revoke administrator access for registered accounts from **Admin access** in the dashboard. Target accounts must have an email address to use the separate admin sign-in. The app prevents an administrator from removing their own access or removing the last administrator.

Customer accounts are available at `/register` and `/login`. Customers can register with an email address, mobile number, or both, then sign in with either one. Customer registration cannot grant administrator permissions; administrator sign-in and sign-out remain separate.

## Store features

- Public storefront, category filters, product detail pages, and responsive light/dark themes
- Session cart with stock checks and database-authoritative checkout prices
- Customer account registration using email, mobile number, or both; sign-in accepts either identifier
- Cash-on-delivery checkout, order confirmation, stock decrement, and order status management
- Separate administrator authentication at `/admin/login`
- Admin dashboard for products, categories, orders, homepage copy, logo/banner/farmer images, and service-benefit text
- Image uploads are stored on Laravel's public filesystem disk

Payment gateway processing, customer account profiles/order history, and automated delivery integration are not configured in this starter application.
# krishokerjhuri
