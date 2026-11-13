# Barangay Bulletin - Purok Rabanes

Lab Activity 11: relationships, eager loading, and Form Requests. Built on top of my Lab 10 project (Laravel 12, SQLite).

- Author: Kagawad Jason Cedrick Rabanes.
- Posts per page: 7.
- Categories: Announcement, Health, Peace & Order, Ayuda, Education.
- Pinned post: Purok Rabanes Clean-up Drive (tags: Purok Rabanes, Urgent).

## Seeded logins

Both accounts use the password `bulletin2026`.

| Account | Email | Can do |
| --- | --- | --- |
| Lola Rabanes (resident) | lola.rabanes@bulletin.test | comment |
| Kagawad Jason Cedrick Rabanes | kagawad.rabanes@bulletin.test | comment and write new posts |

The seeder also makes 8 more resident users with the user factory.

## Tags

Purok Rabanes, Urgent, Libre, Schedule, and Bayanihan (my own tag, for clean-up drives and other community work).

## Relationships

| Link | Type | Why |
| --- | --- | --- |
| User → Author | hasOne | A login account has at most one author profile. `authors.user_id` is unique and nullable, because only Kagawads have one. |
| Author → User | belongsTo | The foreign key `user_id` is on the authors table. |
| Author → Post | hasMany | One author writes many posts (`posts.author_id`). |
| Post → Author | belongsTo | Each post has one author, so I can show the author's name. |
| Post → Comment | hasMany | A post can have many comments (`comments.post_id`). |
| Comment → Post / User | belongsTo | Each comment belongs to one post and was written by one user. |
| User → Comment | hasMany | A resident can write many comments. |
| Post ↔ Tag | belongsToMany | A post can have many tags and a tag is used on many posts, so they need the `post_tag` pivot table. |
| Author → Comment | hasManyThrough (via Post) | Comments don't have an author_id. They are linked to the author through the author's posts, which gives the "N comments received" count on the author page. |

`Model::preventLazyLoading()` is on outside production. The posts index uses `with(['author', 'tags'])->withCount('comments')`, and the post page uses `load()` with `comments.user`, so there are no N+1 queries.

## Setup (Windows, PowerShell)

Requires PHP 8.2 or newer with the `pdo_sqlite` extension, Composer, and Node.js 20.19+ or 22.12+.

```powershell
composer install
Copy-Item .env.example .env
php artisan key:generate
if (!(Test-Path database/database.sqlite)) { New-Item database/database.sqlite -ItemType File }
php artisan migrate:fresh --seed
npm install
npm run dev
```

In another terminal, run:

```powershell
php artisan serve
```

Open http://127.0.0.1:8000/posts. On macOS/Linux use `cp .env.example .env` and `touch database/database.sqlite` instead.

## Checks

```powershell
php artisan test
php artisan tinker
```

```php
App\Models\User::where('email', 'like', 'kagawad.%')->first()->author->name
App\Models\Author::first()->comments()->count()
App\Models\Post::where('is_pinned', true)->first()->tags->pluck('name')
```
