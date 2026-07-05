<?php

namespace Tests\Feature\Trading;

use App\Models\Category;
use App\Models\Event;
use App\Models\Tournament;
use App\Models\TournamentConfig;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventsByTournamentTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_active_events_by_tournament_slug(): void
    {
        $category = Category::create([
            'name' => 'Formula 1',
            'slug' => 'formula-1',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $config = TournamentConfig::create([
            'category_id' => $category->id,
            'name' => 'F1 Config',
        ]);

        $tournament = Tournament::create([
            'category_id' => $category->id,
            'config_id' => $config->id,
            'name' => '2026 Season',
            'slug' => '2026-season',
            'status' => 'active',
            'start_date' => '2026-03-01',
            'end_date' => '2026-11-30',
        ]);

        $event = Event::create([
            'tournament_id' => $tournament->id,
            'name' => 'Monaco Grand Prix',
            'slug' => 'monaco-gp',
            'status' => 'active',
            'start_date' => '2026-05-24',
            'end_date' => '2026-05-26',
        ]);

        $response = $this->getJson(route('api.tournaments.events', $event->id));

        $response->assertOk()
            ->assertJson([
                'status' => true,
                'data' => [
                    [
                        'id' => $event->id,
                        'name' => 'Monaco Grand Prix',
                        'slug' => 'monaco-gp',
                        'status' => 'active',
                        'tournament' => [
                            'id' => $tournament->id,
                            'name' => '2026 Season',
                            'slug' => '2026-season',
                        ],
                    ],
                ],
            ]);
    }

    public function test_returns_404_when_tournament_not_found(): void
    {
        $response = $this->getJson(route('api.tournaments.events', 0));

        $response->assertNotFound()
            ->assertJson([
                'status' => false,
                'message' => 'No active events found for this tournament',
            ]);
    }

    public function test_returns_404_when_tournament_has_no_active_events(): void
    {
        $category = Category::create([
            'name' => 'Formula 1',
            'slug' => 'formula-1',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $config = TournamentConfig::create([
            'category_id' => $category->id,
            'name' => 'F1 Config',
        ]);

        $tournament = Tournament::create([
            'category_id' => $category->id,
            'config_id' => $config->id,
            'name' => '2026 Season',
            'slug' => '2026-season',
            'status' => 'active',
            'start_date' => '2026-03-01',
            'end_date' => '2026-11-30',
        ]);

        $event = Event::create([
            'tournament_id' => $tournament->id,
            'name' => 'Draft Event',
            'slug' => 'draft-event',
            'status' => 'draft',
            'start_date' => '2026-05-24',
            'end_date' => '2026-05-26',
        ]);

        $response = $this->getJson(route('api.tournaments.events', $event->id));

        $response->assertNotFound()
            ->assertJson([
                'status' => false,
                'message' => 'No active events found for this tournament',
            ]);
    }

    public function test_excludes_finished_events(): void
    {
        $category = Category::create([
            'name' => 'Formula 1',
            'slug' => 'formula-1',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $config = TournamentConfig::create([
            'category_id' => $category->id,
            'name' => 'F1 Config',
        ]);

        $tournament = Tournament::create([
            'category_id' => $category->id,
            'config_id' => $config->id,
            'name' => '2026 Season',
            'slug' => '2026-season',
            'status' => 'active',
            'start_date' => '2026-03-01',
            'end_date' => '2026-11-30',
        ]);

        $event = Event::create([
            'tournament_id' => $tournament->id,
            'name' => 'Bahrain GP',
            'slug' => 'bahrain-gp',
            'status' => 'finished',
            'start_date' => '2026-03-15',
            'end_date' => '2026-03-17',
        ]);

        $response = $this->getJson(route('api.tournaments.events', $event->id));

        $response->assertNotFound()
            ->assertJson([
                'status' => false,
                'message' => 'No active events found for this tournament',
            ]);
    }
}
