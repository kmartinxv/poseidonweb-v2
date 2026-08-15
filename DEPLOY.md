# Deploying to HostGator (shared hosting)

This site is a Vue 3 SPA (built to static files) + a plain PHP/MySQL API in
`/api`. No Node.js is required on the server — HostGator shared hosting only
needs to serve static files and run PHP, which it does natively.

## 1. Create the database

In cPanel → **MySQL Databases**:
1. Create a database (e.g. `youruser_poseidonweb`).
2. Create a database user with a strong password, and add it to the database
   with **All Privileges**.
3. Note the full database name and username — HostGator prefixes both with
   your cPanel username (e.g. `youruser_poseidonweb`, `youruser_dbuser`).

In cPanel → **phpMyAdmin**, select the new database, open the **Import** tab,
and import `database/schema.sql` from this repo. This creates the `staff`,
`categories`, `products`, `blog_posts`, and `banners` tables.

## 2. Configure the API

Copy `api/config/config.example.php` to `api/config/config.php` and fill in:
- `db.host` — almost always `localhost` on HostGator
- `db.name`, `db.user`, `db.pass` — from step 1
- `session_secret` — generate one with `php -r "echo bin2hex(random_bytes(32));"`

`api/config/config.php` is gitignored — it never gets committed, so this step
has to be done directly on the server (or copy the file up over SFTP).

## 3. Seed initial content (one time only)

The easiest way is via SSH if your HostGator plan includes it:
```
cd public_html/api/../../database   # wherever you uploaded the repo
php seed.php
```
This creates the first admin login (`admin@poseidonvet.com` / `ChangeMe123!`)
and the original product/blog/category content. **Sign in and change that
password immediately** — see Staff Accounts in the admin panel.

If you don't have SSH access, run `seed.php` once by visiting its URL in a
browser instead, then **delete `database/seed.php` from the server
immediately after** — it has no login check, so leaving it reachable would
let anyone reset the seed data.

## 4. Build and upload the frontend

On your local machine:
```
npm install
npm run build
```
This produces a `dist/` folder. Upload:
- everything **inside** `dist/` → `public_html/` (the files themselves, not
  the `dist` folder itself)
- the whole `api/` folder → `public_html/api/`
- `deploy/.htaccess` → `public_html/.htaccess`

Your `public_html/` should end up looking like:
```
public_html/
  index.html
  assets/...
  favicon.svg
  .htaccess
  api/
    config/config.php
    products/...
    blog/...
    ...
```

## 5. Verify

- Visit your domain — the homepage should load.
- Visit a deep link directly, e.g. `yourdomain.com/blog/why-annual-checkups-matter`
  and `yourdomain.com/shop` — if these 404, double check `.htaccess` uploaded
  correctly and `mod_rewrite` is enabled (it is by default on HostGator).
- Visit `yourdomain.com/admin`, sign in with the seeded admin account, and
  change the password (Staff Accounts panel — for now, ask an admin to
  create you a fresh account with a real password and remove the seeded one
  once you've confirmed access).
- Confirm `api/uploads/` is writable (HostGator's default permissions are
  usually fine; if image uploads fail with a server error, `chmod 755` that
  folder via cPanel's File Manager).

## Notes on scope

This database only covers the website's own shop catalog, blog, and
promotional banners — **not** client, pet, or medical records. Those live in
whatever practice-management system the clinic uses day to day; linking the
two is a separate integration to plan once that system's access details are
available.
