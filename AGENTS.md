# AGENTS.md — Predictorium

Multi-service monorepo: **Laravel 13.8** backend (`php-app/`) + **React 19 + Vite 8** frontend (`react-app/`). Domain: sports betting & event management.

## Quick Commands

```bash
# --- php-app/ ---
composer setup          # install + .env + key:generate + migrate + npm build
composer dev            # 4 parallel processes: serve, queue, logs, vite
composer test           # clears config cache, then PHPUnit (in-memory SQLite)
php artisan test --filter=TestName    # single test

# --- react-app/ ---
npm run dev             # Vite dev server on :3000, proxies /api → nginx:80
npm run build           # tsc -b && vite build → react-app/build/
npm run lint            # eslint

# --- Docker (from repo root) ---
docker compose up -d    # nginx:80, react:3000, mysql:3306, phpmyadmin:8080, redis:6379
docker exec -i predictum-backend php artisan [command]
```

## Architecture

- **nginx** (`predictum-nginx`): routes `/api` and `/admin` to PHP-FPM (`backend:9000`), serves React build as static files from `/var/www/frontend`
- **backend** (`predictum-backend`): PHP 8.5-FPM, Laravel + Filament + Spatie RBAC + Sanctum
- **frontend** (`predictum-frontend`): Node 24 dev server; in Docker, `/api` requests proxy to nginx
- **db** (`predictum-mysql_db`): MySQL 8.0 — production only
- **tests use in-memory SQLite** — no MySQL needed to run tests

## Domain Hierarchy

Category → Tournament → Event → Market → Outcome. Full spec in `php-app/SPEC.md`.

MarketTypes define construction logic: (1) Boolean, (2) Participant-based Boolean, (3) Selection, (4) Binary Selection.

## Key Conventions

- **API layering**: Controller → Service → Repository (see `php-app/.mimocode/skills/laravel-api-developer/SKILL.md`)
- **Routes**: all API routes in `php-app/routes/api.php`, every route has a `->name()`
- **Auth**: Sanctum tokens; `auth:sanctum` middleware on protected routes
- **RBAC**: Spatie Permission; roles seeded in `database/seeders/RolesAndPermissionsSeeder.php` — **must run before user seeder**
- **Filament admin**: auto-discovers resources in `app/Filament/Resources/`; access at `/admin`
- **React build output**: `react-app/build/` is what nginx serves — rebuild after frontend changes

## Gotchas

- `composer dev` uses `concurrently` for 4 processes — if one crashes, `--kill-others` stops all
- Filament auto-discovers resources — new files in `app/Filament/Resources/` appear without registration
- Seeder order matters: `RolesAndPermissionsSeeder` before any user seeder
- Docker `vendor_volume` in `docker-compose.yml` persists vendor — run `composer install` inside container to update
- `.env.example` defaults to SQLite — MySQL config is commented out; uncomment for Docker
- React Vite dev proxy targets `http://nginx:80` — only works inside Docker network
