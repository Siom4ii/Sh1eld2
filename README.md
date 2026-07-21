# SH1ELD

SH1ELD is a Laravel 12 application with Vite-built frontend assets. It includes a public landing page and authenticated role-based areas for super admin, admin/Katuparan, LGU, government agency, MBLRC, 39th IB, and AFP users.

## Requirements

- PHP 8.2 or newer
- Composer
- Node.js and npm
- SQLite by default, or another Laravel-supported database configured in `.env`

## Local Setup

1. Clone the repository and enter the project directory.

   ```bash
   git clone https://github.com/Siom4ii/Sh1eld2.git
   cd Sh1eld2
   ```

2. Install PHP dependencies.

   ```bash
   composer install
   ```

3. Install JavaScript dependencies.

   ```bash
   npm install
   ```

4. Create the environment file and application key.

   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

   On Windows PowerShell, use:

   ```powershell
   Copy-Item .env.example .env
   php artisan key:generate
   ```

5. Create the SQLite database file if you are using the default `.env.example` settings.

   ```bash
   touch database/database.sqlite
   ```

   On Windows PowerShell, use:

   ```powershell
   New-Item -ItemType File database/database.sqlite -Force
   ```

6. Run migrations and seed the starter data.

   ```bash
   php artisan migrate --seed
   ```

7. Start the application.

   ```bash
   composer run dev
   ```

   This starts the Laravel development server, queue listener, log viewer, and Vite dev server together.

8. Open the local site.

   ```text
   http://127.0.0.1:8000
   ```

## Default Test Account

After running `php artisan migrate --seed`, a test user is created:

```text
Email: test@example.com
Password: password
Role: lgu
```

## Common Commands

Run only the Laravel server:

```bash
php artisan serve
```

Run only Vite:

```bash
npm run dev
```

Build production frontend assets:

```bash
npm run build
```

Run the test suite:

```bash
composer run test
```

Clear cached Laravel configuration:

```bash
php artisan optimize:clear
```

## Database Configuration

The example environment uses SQLite:

```env
DB_CONNECTION=sqlite
```

To use MySQL or another database, update the `DB_*` variables in `.env`, create the database manually, then run:

```bash
php artisan migrate --seed
```

## Legacy Data Import

The project includes an Artisan command for importing legacy data:

```bash
php artisan import:legacy
```

Use `--fresh` to wipe the destination tables first:

```bash
php artisan import:legacy --fresh
```

Run this only after configuring the required legacy data source expected by the command.

## Troubleshooting

If pages do not load correctly, make sure both the Laravel server and Vite are running with `composer run dev`.

If the database tables are missing, run:

```bash
php artisan migrate --seed
```

If assets are stale or missing, rebuild them:

```bash
npm run build
```
