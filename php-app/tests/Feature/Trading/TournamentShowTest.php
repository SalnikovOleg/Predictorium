<?php

namespace Tests\Feature\Trading;

use App\Models\Category;
use App\Models\Event;
use App\Models\Tournament;
use App\Models\TournamentConfig;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TournamentShowTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_tournament_by_id(): void
    {
        $category = Category::create([
            'name' => 'Formula 1',
            'slug' => 'formula-1',
            'is_active' => true,
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

        Event::create([
            'tournament_id' => $tournament->id,
            'name' => 'Monaco GP',
            'slug' => 'monaco-gp',
            'status' => 'active',
            'start_date' => '2026-05-24',
            'end_date' => '2026-05-24',
        ]);

        $response = $this->getJson(route('api.tournaments.show', $tournament->id));

        $response->assertOk()
            ->assertJson([
                'status' => true,
                'data' => [
                    'name' => '2026 Season',
                    'slug' => '2026-season',
                    'status' => 'active',
                    'category' => [
                        'id' => $category->id,
                        'name' => 'Formula 1',
                        'slug' => 'formula-1',
                    ],
                ],
            ])
            ->assertJsonStructure([
                'data' => [
                    'id', 'name', 'slug', 'description', 'status', 'start_date', 'end_date',
                    'category' => ['id', 'name', 'slug'],
                    'contents',
                    'events' => [
                        ['id', 'name', 'slug', 'status'],
                    ],
                ],
            ]);
    }

    public function test_get_tournament_by_id_includes_contents(): void
    {
        $category = Category::create([
            'name' => 'Formula 1',
            'slug' => 'formula-1',
            'is_active' => true,
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
        ]);

        $tournament->contents()->create([
            'title' => 'Season Overview',
            'content' => 'The 2026 F1 season...',
            'lang' => 'en',
        ]);

        $response = $this->getJson(route('api.tournaments.show', $tournament->id));

        $response->assertOk()
            ->assertJsonPath('data.contents.0.title', 'Season Overview');
    }

    public function test_get_tournament_by_id_excludes_inactive_events(): void
    {
        $category = Category::create([
            'name' => 'Formula 1',
            'slug' => 'formula-1',
            'is_active' => true,
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
        ]);

        Event::create([
            'tournament_id' => $tournament->id,
            'name' => 'Draft Event',
            'slug' => 'draft-event',
            'status' => 'draft',
        ]);

        $response = $this->getJson(route('api.tournaments.show', $tournament->id));

        $response->assertOk()
            ->assertJsonPath('data.events', []);
    }

    public function test_get_tournament_by_id_returns_404_when_not_found(): void
    {
        $response = $this->getJson(route('api.tournaments.show', 999999));

        $response->assertNotFound()
            ->assertJson([
                'status' => false,
                'message' => 'Page not found',
            ]);
    }
}
