<?php

namespace Tests\Feature\Trading;

use App\Models\Category;
use App\Models\Event;
use App\Models\Market;
use App\Models\MarketTemplate;
use App\Models\MarketType;
use App\Models\Outcome;
use App\Models\OutcomeType;
use App\Models\Participant;
use App\Models\Tournament;
use App\Models\TournamentConfig;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventShowTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_event_by_id(): void
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

        $event = Event::create([
            'tournament_id' => $tournament->id,
            'name' => 'Monaco GP',
            'slug' => 'monaco-gp',
            'status' => 'active',
            'start_date' => '2026-05-24',
            'end_date' => '2026-05-24',
        ]);

        $response = $this->getJson(route('api.events.show', $event->id));

        $response->assertOk()
            ->assertJson([
                'status' => true,
                'data' => [
                    'name' => 'Monaco GP',
                    'slug' => 'monaco-gp',
                    'status' => 'active',
                    'tournament' => [
                        'id' => $tournament->id,
                        'name' => '2026 Season',
                        'slug' => '2026-season',
                    ],
                ],
            ])
            ->assertJsonStructure([
                'data' => [
                    'id', 'name', 'slug', 'status', 'start_date', 'end_date',
                    'tournament' => ['id', 'name', 'slug'],
                    'contents',
                    'markets',
                ],
            ]);
    }

    public function test_get_event_by_id_includes_contents(): void
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

        $event = Event::create([
            'tournament_id' => $tournament->id,
            'name' => 'Monaco GP',
            'slug' => 'monaco-gp',
            'status' => 'active',
        ]);

        $event->contents()->create([
            'title' => 'Race Preview',
            'content' => 'The Monaco Grand Prix...',
            'lang' => 'en',
        ]);

        $response = $this->getJson(route('api.events.show', $event->id));

        $response->assertOk()
            ->assertJsonPath('data.contents.0.title', 'Race Preview');
    }

    public function test_get_event_by_id_includes_markets_with_outcomes(): void
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

        $event = Event::create([
            'tournament_id' => $tournament->id,
            'name' => 'Monaco GP',
            'slug' => 'monaco-gp',
            'status' => 'active',
        ]);

        $marketType = MarketType::create(['name' => 'Selection']);

        $template = MarketTemplate::create([
            'category_id' => $category->id,
            'market_type_id' => $marketType->id,
            'name' => 'Race Winner',
        ]);

        $market = Market::create([
            'event_id' => $event->id,
            'market_template_id' => $template->id,
            'description' => 'Race Winner',
        ]);

        $participant = Participant::create([
            'category_id' => $category->id,
            'name' => 'Max Verstappen',
        ]);

        $outcomeType = OutcomeType::create(['name' => 'Participant']);

        Outcome::create([
            'market_id' => $market->id,
            'outcome_type_id' => $outcomeType->id,
            'participant_id' => $participant->id,
            'coef' => 2.50,
        ]);

        $response = $this->getJson(route('api.events.show', $event->id));

        $response->assertOk()
            ->assertJsonPath('data.markets.0.description', 'Race Winner')
            ->assertJsonPath('data.markets.0.outcomes.0.coef', '2.50')
            ->assertJsonPath('data.markets.0.outcomes.0.participant.name', 'Max Verstappen');
    }

    public function test_get_event_by_id_returns_404_when_not_found(): void
    {
        $response = $this->getJson(route('api.events.show', 999999));

        $response->assertNotFound()
            ->assertJson([
                'status' => false,
                'message' => 'Page not found',
            ]);
    }
}
