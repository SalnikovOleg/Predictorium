# AGENTS.md — Predictorium PHP App

## Project Overview

Laravel 13.8 monorepo workspace with Filament 5.6 admin panel and Spatie RBAC.

## Quick Commands

```bash
# Full setup (from php-app/)
composer setup

# Development (all services: server, queue, logs, vite)
composer dev

# Tests (uses in-memory SQLite)
composer test

# Single test file
php artisan test --filter=ExampleTest
```

## Architecture

- **Admin Panel**: Filament at `/admin` (requires login)
- **Roles**: `admin`, `editor`, `user` — managed via Filament
- **Default DB**: SQLite (tests use `:memory:`), MySQL in Docker
- **Auth**: Spatie Permission with `web` guard

## Key Paths

- `app/Filament/Resources/` — Admin CRUD (User, Role, Permission)
- `app/Models/` — Eloquent models (currently just User)
- `database/seeders/RolesAndPermissionsSeeder.php` — RBAC setup
- `config/permission.php` — Spatie config

## Testing

- PHPUnit 12.5 with in-memory SQLite
- Two suites: `Unit` (pure PHP), `Feature` (HTTP)
- Tests auto-clear config cache before running
- No external services required for tests

## Docker (root workspace)

```bash
# From D:\projects\Predictorium
docker compose up -d
# nginx:80, phpmyadmin:8080, react:3000, mysql:3306
```

## Gotchas

- Filament auto-discovers resources in `app/Filament/Resources/`
- Permission seeder must run before user seeder (order matters)
- `composer dev` uses `concurrently` for 4 parallel processes
- React frontend builds to `react-app/build/`, served by nginx
