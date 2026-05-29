# Agent Notes

## Development

- Start the full dev stack with `composer dev` (runs artisan serve + queue:listen + pail log tail + Vite via `concurrently`).
- Vite asset builder: `npm run dev` (dev server) and `npm run build` (production build). Entry points are `resources/css/app.css` and `resources/js/app.js`.
- PHP code style: run `php artisan pint` (Laravel Pint is installed). It is not automatically enforced in CI.

## Database

- `.env.example` defaults to `sqlite`, but the committed `.env` currently targets MySQL (`DB_CONNECTION=mysql`, `DB_DATABASE=secure-web`).
- If MySQL is unavailable, switch to SQLite by copying `.env.example` to `.env` and running `php artisan key:generate`.
- Feature tests use `RefreshDatabase`. `phpunit.xml` does not hardcode a test DB connection (commented out), so the app falls back to `.env` values.

## Tests

- Test runner: `./vendor/bin/phpunit` (or `php artisan test`).
- Test suites: `Unit` (`tests/Unit`) and `Feature` (`tests/Feature`).
- All existing feature tests depend on the auth scaffolding (Laravel Breeze). Expect failures if auth migrations have not run.

## Architecture

- Laravel 12 (PHP ^8.2) with Laravel Breeze auth scaffolding (Blade + Tailwind CSS + Alpine.js).
- Auth routes live in `routes/auth.php` and are required from `routes/web.php`.
- Auth views are in `resources/views/auth/`. Profile views are in `resources/views/profile/`.
- Tailwind config (`tailwind.config.js`) is v3 with `@tailwindcss/forms`. Content paths include pagination, cached views, and all Blade templates under `resources/views/`.
- No CI workflows, no custom packages, and no API routes are present.
