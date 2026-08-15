# API — local development

Plain PHP + MySQL, no framework, no Composer dependencies — deliberately, so
it runs unmodified on HostGator shared hosting. See `/DEPLOY.md` at the repo
root for production setup.

## First-time local setup

1. Install a local PHP + MySQL stack if you don't have one — XAMPP is the
   simplest on Windows (bundles both, MySQL is actually MariaDB under the
   hood but fully compatible).
2. Start MySQL: `xampp/mysql_start.bat` (or via the XAMPP control panel).
3. Create a database and import the schema:
   ```
   mysql -u root -e "CREATE DATABASE poseidonweb_shop CHARACTER SET utf8mb4"
   mysql -u root poseidonweb_shop < database/schema.sql
   ```
4. Copy `api/config/config.example.php` to `api/config/config.php` and set
   `db.name` to match (defaults already assume `poseidonweb_shop` / root /
   no password, i.e. a stock local MySQL install).
5. Seed initial content + first admin login:
   ```
   php database/seed.php
   ```
   Creates `admin@poseidonvet.com` / `ChangeMe123!` — change this from the
   admin panel's Staff Accounts screen once you're in.

## Running it

Two servers, both need to be up:

```
php -S localhost:8787 -t api      # the API
npm run dev                       # the Vue frontend (proxies /api -> :8787, see vite.config.js)
```

Then visit `http://localhost:5173` as normal — `/admin` is the staff login.

## Adding a new endpoint

Follow the existing pattern in `api/products/`, `api/blog/`, etc.: one file
per action (`list.php`, `create.php`, `update.php`, `delete.php`), each
starting with `require_once __DIR__ . '/../lib/bootstrap.php';`, using
`require_staff()` / `require_admin()` from `api/lib/auth.php` to gate
write access, and `json_response()` / `json_error()` from
`api/lib/response.php` for output. Keep everything POST-only (except public
GET listings) — some shared hosts restrict other HTTP verbs.
