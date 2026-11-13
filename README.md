# Barangay Bulletin - Purok Rabanes

Lab Activity 10: Laravel 12, Eloquent, migrations, factories, and seeding.

- Author: Kagawad Jason Cedrick Rabanes.
- Posts per page: 7 because Rabanes has seven letters.
- Categories: Announcement, Health, Peace & Order, Ayuda, and Education.
- Custom category: Education.
- Pinned post: Purok Rabanes Clean-up Drive.
- Birthday post date: November 23, 2026.

## Setup

Requires PHP 8.2 or newer, Composer, SQLite support, and Node.js 20.19+ or 22.12+.

Run these commands in the project folder using PowerShell:

```powershell
composer install
Copy-Item .env.example .env
php artisan key:generate
if (!(Test-Path database/database.sqlite)) { New-Item database/database.sqlite -ItemType File }
php artisan migrate:fresh --seed
npm install
npm run dev
```

Keep `DB_CONNECTION=sqlite` and leave `DB_DATABASE` unset. On macOS/Linux, use `cp .env.example .env` and `touch database/database.sqlite` for the copy and database-file steps. `migrate:fresh --seed` resets the lab database.

In another terminal, run:

```powershell
php artisan serve
```

Open http://127.0.0.1:8000/posts. The app lists published posts, supports title and category filters, and counts views when a post is opened. Drafts return 404.

## Checks

```powershell
php artisan test
php artisan migrate:rollback --step=1
php artisan migrate
```

The seeders create 5 authors and 51 posts: 40 published posts, 10 drafts, and the pinned birthday post. The Tinker screenshot is in [docs/tinker.png](docs/tinker.png).
