---
name: laravel-deep-test-setup
description: Creates Feature tests for Predictorium's deep entity hierarchy (Category→Taxonomy→TournamentConfig→Tournament→Event→MarketType→MarketTemplate→Market→OutcomeType→Outcome) using raw DB inserts and hardcoded enum IDs.
triggers: [feature test, phpunit test, test setup, test data hierarchy, stake test, market test, outcome test]
---

# Laravel Deep Test Setup (Predictorium)

## Purpose

Write reliable Feature tests for the Predictorium domain hierarchy without factories, using raw database inserts and hardcoded IDs to match seeder-dependent service logic.

## When to Use

- Writing Feature tests for Trading endpoints (stakes, markets, events, outcomes, results)
- Testing services that depend on seeder-enum ID mappings (market_type_id, outcome_type_id, result_type_id)
- Any test requiring the full Category→Tournament→Event→Market→Outcome hierarchy

## The Hierarchy (Required Insert Order)

```
1. taxonomies          (type, name) — NOT NULL type
2. categories          (taxonomy_id, taxonomy_type, name, slug) — NOT NULL taxonomy_type
3. tournament_configs  (category_id, taxonomy_ids[], params) — FK to category
4. taxonomies (again)  for tournament taxonomy_ids references
5. tournaments         (category_id, config_id, taxonomy_id, status, dates)
6. events              (tournament_id, name, slug, status, dates)
7. market_types        (id, name) — SEEDED, use hardcoded IDs
8. market_templates    (category_id, market_type_id, outcome_type_ids[], param1, param2)
9. markets             (event_id, market_template_id, sort_order)
10. outcome_types      (id, name) — SEEDED, use hardcoded IDs
11. outcomes           (market_id, outcome_type_id, participant_id, coef, result_type_id)
12. result_types       (category_id, id, name, value_type) — SEEDED, use hardcoded IDs
13. results            (event_id, result_type_id, value[], participant_id)
```

**Critical:** No factories exist for Event, Market, Outcome, Tournament, TournamentConfig. Use `DB::table()->insertGetId()`.

## Seeder-Dependent Hardcoded IDs

The service logic matches on specific seeder IDs. Tests MUST use these IDs:

### MarketTypes (from MarketTypeSeeder)
| ID | Name | Logic |
|---|---|---|
| 1 | Boolean | Yes/No outcomes |
| 2 | Participant Boolean | Yes/No with participant |
| 3 | Selection | List of participants (param1 = count) |
| 4 | Binary Selection | Two participant outcomes |
| 5 | Outcome List | Exact score (7 outcomes per row) |

### OutcomeTypes (from OutcomeTypeSeeder)
| ID Range | Name | Use Case |
|---|---|---|
| 1 | Yes | market_type 1, 2 |
| 2 | No | market_type 1, 2 |
| 3 | Participant | market_type 3, 4 |
| 4-52 | Participant variants | market_type 3 (param1=3 → pick 3) |

### ResultTypes (from ResultTypeSeeder)
| ID | Name | value_type | Used By |
|---|---|---|---|
| 1 | Race Winner | positions | OutcomeResultService rule 1, 5 |
| 2 | Qualification Winner | positions | Rule 1 |
| 3 | Best Lap | participant | Rule 2 |
| 4-6 | Other participant-based | participant | Rule 2 |
| 7 | Exact Score | score | MarketType 5 |

## Test Setup Template

```php
<?php

namespace Tests\Feature\Trading;

use Tests\TestCase;
use App\Models\User;
use App\Models\Group;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StakeTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Group $group;
    protected int $categoryId;
    protected int $taxonomyId;
    protected int $tournamentConfigId;
    protected int $tournamentId;
    protected int $eventId;
    protected int $marketId;
    protected array $outcomeIds = [];

    protected function setUp(): void
    {
        parent::setUp();
        
        // 1. User + Group (owner_id NOT NULL)
        $this->user = User::factory()->create();
        $this->group = Group::create(['name' => 'Test Group', 'owner_id' => $this->user->id]);
        $this->actingAs($this->user, 'sanctum');

        // 2. Taxonomy (type NOT NULL)
        $this->taxonomyId = DB::table('taxonomies')->insertGetId([
            'type' => 'community',
            'name' => 'Test Community',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 3. Category (taxonomy_type NOT NULL)
        $this->categoryId = DB::table('categories')->insertGetId([
            'taxonomy_id' => $this->taxonomyId,
            'taxonomy_type' => 'community',  // enum value
            'name' => 'Test Category',
            'slug' => 'test-category',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 4. TournamentConfig
        $this->tournamentConfigId = DB::table('tournament_configs')->insertGetId([
            'category_id' => $this->categoryId,
            'taxonomy_ids' => json_encode([$this->taxonomyId]),
            'params' => json_encode([]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 5. Tournament
        $this->tournamentId = DB::table('tournaments')->insertGetId([
            'category_id' => $this->categoryId,
            'config_id' => $this->tournamentConfigId,
            'taxonomy_id' => $this->taxonomyId,
            'name' => 'Test Tournament',
            'slug' => 'test-tournament',
            'status' => 'active',
            'start_date' => now()->addDay(),
            'end_date' => now()->addDays(3),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 6. Event
        $this->eventId = DB::table('events')->insertGetId([
            'tournament_id' => $this->tournamentId,
            'name' => 'Test Event',
            'slug' => 'test-event',
            'status' => 'active',
            'start_date' => now()->addDay(),
            'end_date' => now()->addDays(2),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 7. Market (use SEEDED market_type_id, market_template_id)
        // MarketTemplate must exist for this category + market_type
        $templateId = DB::table('market_templates')->insertGetId([
            'category_id' => $this->categoryId,
            'market_type_id' => 3,  // Selection (param1 = count)
            'outcome_type_ids' => json_encode([3]),  // Participant
            'param1' => 1,
            'param2' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->marketId = DB::table('markets')->insertGetId([
            'event_id' => $this->eventId,
            'market_template_id' => $templateId,
            'name' => 'Winner',
            'sort_order' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 8. Participants + Outcomes (outcome_type_id = 3 for Participant)
        $participantIds = [];
        for ($i = 1; $i <= 3; $i++) {
            $participantIds[] = DB::table('participants')->insertGetId([
                'category_id' => $this->categoryId,
                'taxonomy_id' => $this->taxonomyId,
                'name' => "Participant $i",
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        foreach ($participantIds as $pid) {
            $this->outcomeIds[] = DB::table('outcomes')->insertGetId([
                'market_id' => $this->marketId,
                'outcome_type_id' => 3,  // Participant
                'participant_id' => $pid,
                'coef' => 2.50,
                'result_type_id' => 3,   // Best Lap (participant-based)
                'result' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /** @test */
    public function it_creates_a_stake_and_returns_201(): void
    {
        $response = $this->postJson(route('api.stakes.store'), [
            'group_id' => $this->group->id,
            'user_id' => $this->user->id,
            'event_id' => $this->eventId,
            'market_id' => $this->marketId,
            'outcome_id' => $this->outcomeIds[0],
        ]);

        $response->assertCreated()  // JsonResource returns 201 for new records
            ->assertJsonStructure([
                'status',
                'data' => ['id', 'group_id', 'user_id', 'event_id', 'market_id', 'outcome_id', 'outcome_ids']
            ]);
    }
}
```

## Key Patterns

### 1. JsonResource Returns 201

When a `JsonResource` is returned from a controller creating a new record, Laravel returns **HTTP 201**.
```php
$response->assertCreated();  // NOT assertOk()
```

### 2. groups.owner_id NOT NULL

```php
$group = Group::create(['name' => 'Test', 'owner_id' => $user->id]);
```

### 3. categories.taxonomy_type NOT NULL

```php
'taxonomy_type' => 'community',  // or 'country' — enum value
```

### 4. OutcomeResultService Tests — Force Specific IDs

Service logic matches on hardcoded `result_type_id` values:
```php
// Service checks: in_array($outcome->result_type_id, [1, 2]) or [3,4,5,6]
// Tests MUST insert with specific IDs:
DB::table('result_types')->insert([
    'id' => 1, 'category_id' => $catId, 'name' => 'Race Winner', 'value_type' => 'positions',
]);
DB::table('result_types')->insert([
    'id' => 3, 'category_id' => $catId, 'name' => 'Best Lap', 'value_type' => 'participant',
]);
```

### 5. Result.value JSON Structure

```php
// For result_type_id 1,2 (positions):
'value' => json_encode([['place' => 1, 'participant_id' => $pid]])

// For result_type_id 3-6 (participant):
'value' => json_encode([['participant_id' => $pid]])
```

### 6. Stake Unique Constraint (Application-Level)

Migration has NO unique composite index on `(group_id, user_id, event_id, market_id)`. Upsert is enforced in Service:
```php
// Repository::findExisting checks this combination
// Service::store does upsert
```

## Common Test Scenarios

### POST /stake (Single Outcome)
```php
$response = $this->postJson(route('api.stakes.store'), [
    'group_id' => $group->id,
    'user_id' => $user->id,
    'event_id' => $eventId,
    'market_id' => $marketId,
    'outcome_id' => $outcomeIds[0],
]);
$response->assertCreated();
```

### POST /stake (Multi-Outcome, param1 > 1)
```php
$response = $this->postJson(route('api.stakes.store'), [
    'group_id' => $group->id,
    'user_id' => $user->id,
    'event_id' => $eventId,
    'market_id' => $marketId,
    'outcome_ids' => [$outcomeIds[0], $outcomeIds[1], $outcomeIds[2]],  // exactly param1
]);
```

### GET /stakes/stat/{marketId}
```php
$response = $this->getJson(route('api.stakes.stat', ['marketId' => $marketId]));
$response->assertOk()
    ->assertJsonStructure([
        'status',
        'data' => [['outcome_id', 'name', 'percent']]
    ]);
```

### OutcomeResultService Calculation
```php
// Create results
DB::table('results')->insert([
    'event_id' => $eventId,
    'result_type_id' => 1,  // Race Winner
    'value' => json_encode([['place' => 1, 'participant_id' => $winnerPid]]),
    'created_at' => now(),
    'updated_at' => now(),
]);

$service = app(OutcomeResultService::class);
$service->calculateForEvent($eventId);

// Assert outcomes updated
$this->assertDatabaseHas('outcomes', ['id' => $winnerOutcomeId, 'result' => 'win']);
$this->assertDatabaseHas('outcomes', ['id' => $loserOutcomeId, 'result' => 'lose']);
```

## Procedure

1. **Extend `TestCase`**, use `RefreshDatabase`
2. **Create User + Group** in `setUp()` (owner_id required)
3. **Insert hierarchy bottom-up** using `DB::table()->insertGetId()`
4. **Use hardcoded seeder IDs** for market_type, outcome_type, result_type
5. **Force specific IDs** for result_types when testing OutcomeResultService
6. **Use `assertCreated()`** for POST endpoints returning JsonResource
7. **Run tests**: `composer test` or `php artisan test --filter=TestName`

## Quality Bar

- [ ] All NOT NULL constraints satisfied (taxonomy_type, owner_id, taxonomy_id on categories)
- [ ] Hardcoded seeder IDs used for market_type, outcome_type, result_type
- [ ] Result types inserted with forced IDs when testing OutcomeResultService
- [ ] `assertCreated()` for create endpoints
- [ ] No factories for Event/Market/Outcome/Tournament/TournamentConfig
- [ ] Test runs in isolation (RefreshDatabase)
- [ ] `composer test` passes

## Anti-patterns

- Using `Model::factory()->create()` for Event, Market, Outcome, Tournament
- Forgetting `taxonomy_type` on categories
- Forgetting `owner_id` on groups
- Using auto-increment IDs for result_types in OutcomeResultService tests
- Using `assertOk()` for POST create endpoints
- Not cleaning up between tests (missing RefreshDatabase)