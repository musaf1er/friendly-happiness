# Mischief Outlaws MC

A native PHP 8.3+ website for a motorcycle club, using PDO, MySQL/MariaDB, Tailwind CSS 3.4, HTML, and vanilla JavaScript.

## Requirements

PHP 8.3+, Apache or Nginx, MySQL 8/MariaDB 10.6+, PDO MySQL, and Node.js LTS for CSS development. Demo photography is stored locally under `assets/images/`; replace it with club-owned photography before production.

## Tailwind CSS

The frontend is compiled with Tailwind CSS 3.4. Install the development dependency with `npm install`. During development run `npm run dev`; for a deployment build run `npm run build`. The generated production stylesheet is `assets/css/app.css`. npm is only used to compile CSS; PHP still runs through Apache, Laragon, XAMPP, or the PHP CLI server.

## Replacing Website Images

The complete image map, recommended dimensions, and upload behavior are documented in [ASSETS.md](ASSETS.md).

- Put the club logo and favicon in `assets/images/branding/`.
- Put homepage hero photography in `assets/images/hero/`.
- Put static gallery images in `assets/images/gallery/`.
- Put officer portraits in `assets/images/officers/`.
- Put merchandise images in `assets/images/merchandise/`.
- Put event images in `assets/images/events/`.
- Put local fallback imagery in `assets/images/placeholders/`.
- Admin uploads are generated automatically under `uploads/gallery/`, `uploads/events/`, `uploads/officers/`, and `uploads/merchandise/`.

Prefer WebP or compressed JPEG. The admin upload limit is 5 MB per image.

## Local setup (XAMPP or Laragon)

1. Copy the project into `htdocs` (XAMPP) or `www` (Laragon).
2. Create a MySQL database by importing `database/schema.sql`.
3. Import `database/seed.sql` into the same database.
4. Configure environment variables where supported, or edit the defaults in `config/db.php`: `DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_USER`, and `DB_PASS`.
5. Ensure the web server can write to `uploads/`; the directory is created on the first upload.
6. Open `http://localhost/Mischief%20OMC/` or the configured virtual host.

For a quick local CLI smoke run without MySQL, set `DB_DRIVER=sqlite` and `DB_PATH` to a writable SQLite file. The MySQL schema remains the deployment source of truth.

## Admin

Open `index.php?page=admin/login`. The seeded account is `admin` with password `ChangeMe!2026`. Change or remove this account before production. The admin area supports dashboard counts, event/gallery/officer/merchandise CRUD, prospect status management, and authenticated logout.

## Security and deployment

All writes use POST, CSRF tokens, prepared statements, output escaping, password hashing, session regeneration, role checks, and MIME/size checks for image uploads. Put database credentials in the server environment, enable HTTPS, disable PHP error display, and point the web root at this directory only when `config/` and uploads are protected by server configuration. Back up the database and uploads together.

## Troubleshooting

A blank content list usually means the schema was not imported or the database credentials are wrong; inspect the PHP/server error log. If uploads fail, check the PHP upload limits and writable permissions. If rewrite rules are unavailable, use the explicit `index.php?page=...` URLs shown by the site.
