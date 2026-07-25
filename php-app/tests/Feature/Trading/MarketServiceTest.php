<?php

namespace Tests\Feature\Trading;

use App\Models\Market;
use App\Models\Outcome;
use App\Services\Trading\MarketService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class MarketServiceTest extends TestCase
{
    use RefreshDatabase;

    private MarketService $service;
    private int $categoryId;
    private int $tournamentId;
    private int $configId;
    private int $eventId;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = app(MarketService::class);

        $this->categoryId = DB::table('categories')->insertGetId([
            'taxonomy_type' => 'community',
            'name' => 'Test Category',
            'slug' => 'test-category',
            'is_active' => true,
        ]);

        $taxonomyId = DB::table('taxonomies')->insertGetId([
            'type' => 'community',
            'name' => 'Test Taxonomy',
            'icon' => 'heroicon-o-test',
        ]);

        $this->configId = DB::table('tournament_configs')->insertGetId([
            'category_id' => $this->categoryId,
            'name' => 'Default',
        ]);

        $this->tournamentId = DB::table('tournaments')->insertGetId([
            'category_id' => $this->categoryId,
            'taxonomy_id' => $taxonomyId,
            'name' => 'Test Tournament',
            'slug' => 'test-tournament',
            'config_id' => $this->configId,
        ]);

        $this->eventId = DB::table('events')->insertGetId([
            'tournament_id' => $this->tournamentId,
            'name' => 'Test Event',
            'slug' => 'test-event',
            'status' => 'draft',
        ]);

        // Create score outcome types (IDs 4-52)
        for ($i = 4; $i <= 52; $i++) {
            $index = $i - 4;
            $home = intdiv($index, 7);
            $away = $index % 7;
            DB::table('outcome_types')->insert([
                'id' => $i,
                'name' => "{$home}:{$away}",
            ]);
        }
    }

    public function test_creates_market_with_outcome_list_type(): void
    {
        // Create market type (Outcome list)
        $marketTypeId = DB::table('market_types')->insertGetId(['name' => 'Outcome list']);

        // Create a result type for Score
        $resultTypeId = DB::table('result_types')->insertGetId([
            'category_id' => $this->categoryId,
            'name' => 'Score',
            'value_type' => 'score',
        ]);

        // Create a market template with market_type_id = 5 (Outcome list)
        $outcomeTypeIds = range(4, 52); // All score variants
        $marketTemplateId = DB::table('market_templates')->insertGetId([
            'name' => 'Exact Score',
            'description' => 'Predict the exact score',
            'market_type_id' => $marketTypeId,
            'outcome_type_ids' => json_encode($outcomeTypeIds),
            'result_type_id' => $resultTypeId,
        ]);

        // Add template to tournament config
        DB::table('tournament_configs')
            ->where('id', $this->configId)
            ->update(['market_template_ids' => json_encode([$marketTemplateId])]);

        // Create markets
        $created = $this->service->createDefaultsMarket($this->eventId);

        // Should create 1 market
        $this->assertEquals(1, $created);

        // Should create 49 outcomes (0:0 through 6:6)
        $market = Market::where('event_id', $this->eventId)->first();
        $this->assertNotNull($market);
        $this->assertEquals($marketTemplateId, $market->market_template_id);

        $outcomes = Outcome::where('market_id', $market->id)->get();
        $this->assertEquals(49, $outcomes->count());

        // Verify first outcome is 0:0 (outcome_type_id = 4)
        $firstOutcome = $outcomes->first();
        $this->assertEquals(4, $firstOutcome->outcome_type_id);
        $this->assertEquals($resultTypeId, $firstOutcome->result_type_id);
        $this->assertEquals(1.00, $firstOutcome->coef);

        // Verify last outcome is 6:6 (outcome_type_id = 52)
        $lastOutcome = $outcomes->last();
        $this->assertEquals(52, $lastOutcome->outcome_type_id);
    }

    public function test_does_not_duplicate_market_for_same_template(): void
    {
        $marketTypeId = DB::table('market_types')->insertGetId(['name' => 'Outcome list']);

        $resultTypeId = DB::table('result_types')->insertGetId([
            'category_id' => $this->categoryId,
            'name' => 'Score',
            'value_type' => 'score',
        ]);

        $marketTemplateId = DB::table('market_templates')->insertGetId([
            'name' => 'Exact Score',
            'market_type_id' => $marketTypeId,
            'outcome_type_ids' => json_encode([4, 5, 6]),
            'result_type_id' => $resultTypeId,
        ]);

        DB::table('tournament_configs')
            ->where('id', $this->configId)
            ->update(['market_template_ids' => json_encode([$marketTemplateId])]);

        // Create markets twice
        $created1 = $this->service->createDefaultsMarket($this->eventId);
        $created2 = $this->service->createDefaultsMarket($this->eventId);

        $this->assertEquals(1, $created1);
        $this->assertEquals(0, $created2); // Should not create duplicate

        // Only one market should exist
        $markets = Market::where('event_id', $this->eventId)->get();
        $this->assertEquals(1, $markets->count());
    }
}
