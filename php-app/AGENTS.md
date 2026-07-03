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

# Run php artisan 
docker exec -i predictum-backend php artisan [command]
```

## Gotchas

- Filament auto-discovers resources in `app/Filament/Resources/`
- Permission seeder must run before user seeder (order matters)
- `composer dev` uses `concurrently` for 4 parallel processes
- React frontend builds to `react-app/build/`, served by nginx

## Technical Specification: Sports Betting & Event Management System

### System Overview

This system manages the lifecycle of sports events, from configuration and market creation to result processing. The architecture follows a hierarchical structure: **Category → Tournament → Event → Market → Outcome**.

### Core Schema Structure

#### 1. Hierarchy & Events

- **Categories**: Top-level taxonomy (e.g., specific sports). All subsequent entities are scoped within a category.
- **Tournaments**: Represents a specific competition series.
  - Linked to Categories via `category_id`.
  - Linked to TournamentConfig to enforce rules and settings.
- **Events**: Individual matches or races.
  - Linked to Tournaments via `tournament_id`.
  - Contains `start_date`, `end_date`, and `status`.
  - Linked to Participants via `event_participants` (many-to-many).

#### 2. Market Logic

- **Markets**: Betting opportunities linked to an Event (`event_id`).
  - Driven by MarketTemplates (`market_template_id`).
- **MarketTemplates**: Blueprint for market creation.
  - Scoped by `category_id`.
  - `market_type_id`: Defines how the market is constructed.
  - `outcome_type_ids`: Determines allowed outcome types.
  - `param1`: Configurable integer (e.g., number of participants to select for stake: 1 for "Winner", 3 for "Podium").
  - `param2`: Reserved for future logic.
- **MarketTypes**: Defines business logic for templates:
  1. **Boolean** — Yes/No outcomes.
  2. **Participant-based Boolean** — Yes/No, statement incorporates a specific participant name.
  3. **Selection** — Outcomes are a list of participants.
  4. **Binary Selection** — Two outcomes, both are participants.

#### 3. Outcomes

- **Outcomes**: Specific betting options within a market.
  - Linked to Markets via `market_id`.
  - `outcome_type_id`: Linked to OutcomeTypes (e.g., Yes, No, Participant).
  - `participant_id`: References the specific participant if outcome_type is Participant (type 3).
  - `coef`: Payout multiplier (odds).
  - `result`: Tracks the outcome status (win, lose, return).

#### 4. Results Processing

- **Results**: Recorded post-event to determine payouts.
  - Linked to Event via `event_id`.
  - `result_type_id`: Defines the metric (e.g., Race Winner, Best Lap, Qualification Winner).
  - `value`: JSON storage for complex structures (e.g., `[{"p": 1, "participant_id": 123}, ...]`).
- **ResultTypes**: Configurable result definitions scoped by `category_id`.
  - `value_type`: Dictates format (e.g., positions for JSON array, or participant for a single ID reference).

### Agent Guidelines

- **Context Enforcement**: Always verify the `category_id` when creating or validating Tournaments, TournamentConfigs, MarketTemplates, or ResultTypes.
- **Market Construction**: When generating markets, strictly adhere to the logic defined by MarketType (e.g., ensure correct `outcome_type_ids` are used based on the template).
- **Data Integrity**: When processing results, parse the `value_type` from ResultTypes to correctly interpret the `results.value` JSON or `results.participant_id` field.
- **Relationship Mapping**: Always reference the join tables (`event_participants`, `market_templates`) to maintain relational integrity across the hierarchy.
