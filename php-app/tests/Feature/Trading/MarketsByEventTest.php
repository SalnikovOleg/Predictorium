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

class MarketsByEventTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_markets_by_event_id(): void
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

        $outcomeType = OutcomeType::create(['name' => 'Participant']);

        $participant = Participant::create([
            'category_id' => $category->id,
            'name' => 'Max Verstappen',
        ]);

        $marketType = MarketType::create(['name' => 'Selection']);

        $marketTemplate = MarketTemplate::create([
            'name' => 'Race Winner',
            'category_id' => $category->id,
            'market_type_id' => $marketType->id,
        ]);

        $market = Market::create([
            'event_id' => $event->id,
            'market_template_id' => $marketTemplate->id,
            'description' => 'Race Winner',
        ]);

        $outcome = Outcome::create([
            'market_id' => $market->id,
            'outcome_type_id' => $outcomeType->id,
            'participant_id' => $participant->id,
            'coef' => 2.50,
        ]);

        $response = $this->getJson(route('api.events.markets', $event->id));

        $response->assertOk()
            ->assertJson([
                'status' => true,
                'data' => [
                    [
                        'id' => $market->id,
                        'description' => 'Race Winner',
                        'outcomes' => [
                            [
                                'id' => $outcome->id,
                                'coef' => '2.50',
                                'outcome_type' => [
                                    'id' => $outcomeType->id,
                                    'name' => 'Participant',
                                ],
                                'participant' => [
                                    'id' => $participant->id,
                                    'name' => 'Max Verstappen',
                                ],
                            ],
                        ],
                    ],
                ],
            ]);
    }

    public function test_returns_404_when_event_has_no_markets(): void
    {
        $response = $this->getJson(route('api.events.markets', 999));

        $response->assertNotFound()
            ->assertJson([
                'status' => false,
                'message' => 'No markets found for this event',
            ]);
    }

    public function test_returns_empty_outcomes_when_market_has_none(): void
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
        ]);

        $event = Event::create([
            'tournament_id' => $tournament->id,
            'name' => 'Monaco Grand Prix',
            'slug' => 'monaco-gp',
            'status' => 'active',
        ]);

        $marketType = MarketType::create(['name' => 'Selection']);

        $marketTemplate = MarketTemplate::create([
            'name' => 'Podium Finish',
            'category_id' => $category->id,
            'market_type_id' => $marketType->id,
        ]);

        Market::create([
            'event_id' => $event->id,
            'market_template_id' => $marketTemplate->id,
            'description' => 'Podium Finish',
        ]);

        $response = $this->getJson(route('api.events.markets', $event->id));

        $response->assertOk()
            ->assertJson([
                'status' => true,
                'data' => [
                    [
                        'description' => 'Podium Finish',
                        'outcomes' => [],
                    ],
                ],
            ]);
    }
}
