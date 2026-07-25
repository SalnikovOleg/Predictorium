<?php

namespace Tests\Feature\Trading;

use App\Enums\OutcomeResult;
use App\Models\Outcome;
use App\Services\Trading\OutcomeResultService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use Tests\TestCase;

class OutcomeResultServiceTest extends TestCase
{
    use RefreshDatabase;

    private OutcomeResultService $service;
    private int $categoryId;
    private int $tournamentId;
    private int $participantId1;
    private int $participantId2;

    // Result type IDs matching the spec (must exist in DB)
    private int $resultTypeId1; // For positions-based (value_type = 'positions')
    private int $resultTypeId2; // For participant-based (value_type = 'participant')

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = app(OutcomeResultService::class);

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

        $configId = DB::table('tournament_configs')->insertGetId([
            'category_id' => $this->categoryId,
            'name' => 'Default',
        ]);

        $this->tournamentId = DB::table('tournaments')->insertGetId([
            'category_id' => $this->categoryId,
            'taxonomy_id' => $taxonomyId,
            'name' => 'Test Tournament',
            'slug' => 'test-tournament',
            'config_id' => $configId,
        ]);

        $this->participantId1 = DB::table('participants')->insertGetId([
            'category_id' => $this->categoryId,
            'name' => 'Participant 1',
            'taxonomy_id' => $taxonomyId,
        ]);

        $this->participantId2 = DB::table('participants')->insertGetId([
            'category_id' => $this->categoryId,
            'name' => 'Participant 2',
            'taxonomy_id' => $taxonomyId,
        ]);

        // Create result types with specific IDs matching the spec
        $this->resultTypeId1 = $this->createResultTypeWithId(1, 'Race Winner', 'positions');
        $this->resultTypeId2 = $this->createResultTypeWithId(3, 'Best Lap', 'participant');

        // Create score outcome types (IDs 4-52)
        for ($i = 4; $i <= 52; $i++) {
            DB::table('outcome_types')->insert([
                'id' => $i,
                'name' => $this->getScoreName($i),
            ]);
        }
    }

    private function getScoreName(int $id): string
    {
        // ID 4 = 0:0, 5 = 0:1, ..., 10 = 0:6
        // ID 11 = 1:0, ..., 17 = 1:6
        // ID 18 = 2:0, ..., 24 = 2:6
        // etc.
        $index = $id - 4;
        $home = intdiv($index, 7);
        $away = $index % 7;
        return "{$home}:{$away}";
    }

    private function createResultTypeWithId(int $id, string $name, string $valueType): int
    {
        DB::table('result_types')->insert([
            'id' => $id,
            'category_id' => $this->categoryId,
            'name' => $name,
            'value_type' => $valueType,
        ]);

        return $id;
    }

    private function createEvent(string $status = 'finished'): int
    {
        return DB::table('events')->insertGetId([
            'tournament_id' => $this->tournamentId,
            'name' => 'Test Event',
            'slug' => 'test-event-' . uniqid(),
            'status' => $status,
        ]);
    }

    private function createMarket(int $eventId, int $marketTypeId): int
    {
        $marketTemplateId = DB::table('market_templates')->insertGetId([
            'name' => 'Test Template',
            'market_type_id' => $marketTypeId,
            'result_type_id' => 0,
        ]);

        return DB::table('markets')->insertGetId([
            'event_id' => $eventId,
            'market_template_id' => $marketTemplateId,
        ]);
    }

    private function createOutcome(
        int $marketId,
        int $outcomeTypeId,
        int $resultTypeId,
        int $participantId
    ): int {
        return DB::table('outcomes')->insertGetId([
            'market_id' => $marketId,
            'outcome_type_id' => $outcomeTypeId,
            'result_type_id' => $resultTypeId,
            'participant_id' => $participantId,
            'coef' => 1.50,
        ]);
    }

    private function createResult(
        int $eventId,
        int $resultTypeId,
        ?int $participantId,
        ?array $value
    ): int {
        return DB::table('results')->insertGetId([
            'event_id' => $eventId,
            'result_type_id' => $resultTypeId,
            'participant_id' => $participantId,
            'value' => $value ? json_encode($value) : null,
        ]);
    }

    public function test_throws_exception_when_event_not_found(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Event 999 not found');

        $this->service->calculateForEvent(999);
    }

    public function test_throws_exception_when_event_not_finished(): void
    {
        $eventId = $this->createEvent('active');

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('finished');

        $this->service->calculateForEvent($eventId);
    }

    // Rule 1: outcome_type_id = 3, result_type_id = 1 or 2 (Selection with positions)
    public function test_selection_with_positions_wins_when_first_place_matches(): void
    {
        $eventId = $this->createEvent();
        $marketTypeId = DB::table('market_types')->insertGetId(['name' => 'Selection']);

        $this->createResult($eventId, $this->resultTypeId1, null, [
            $this->participantId1,
            $this->participantId2,
        ]);

        $marketId = $this->createMarket($eventId, $marketTypeId);
        $outcomeId = $this->createOutcome($marketId, 3, $this->resultTypeId1, $this->participantId1);

        $this->service->calculateForEvent($eventId);

        $outcome = Outcome::find($outcomeId);
        $this->assertEquals(OutcomeResult::Win, $outcome->result);
    }

    public function test_selection_with_positions_loses_when_first_place_does_not_match(): void
    {
        $eventId = $this->createEvent();
        $marketTypeId = DB::table('market_types')->insertGetId(['name' => 'Selection']);

        $this->createResult($eventId, $this->resultTypeId1, null, [
            $this->participantId1,
            $this->participantId2,
        ]);

        $marketId = $this->createMarket($eventId, $marketTypeId);
        $outcomeId = $this->createOutcome($marketId, 3, $this->resultTypeId1, $this->participantId2);

        $this->service->calculateForEvent($eventId);

        $outcome = Outcome::find($outcomeId);
        $this->assertEquals(OutcomeResult::Lose, $outcome->result);
    }

    // Rule 2: outcome_type_id = 3, result_type_id = 3 (Selection with participant)
    public function test_selection_with_participant_wins_when_result_exists(): void
    {
        $eventId = $this->createEvent();
        $marketTypeId = DB::table('market_types')->insertGetId(['name' => 'Selection']);

        $this->createResult($eventId, $this->resultTypeId2, $this->participantId1, null);

        $marketId = $this->createMarket($eventId, $marketTypeId);
        $outcomeId = $this->createOutcome($marketId, 3, $this->resultTypeId2, $this->participantId1);

        $this->service->calculateForEvent($eventId);

        $outcome = Outcome::find($outcomeId);
        $this->assertEquals(OutcomeResult::Win, $outcome->result);
    }

    public function test_selection_with_participant_loses_when_result_not_found(): void
    {
        $eventId = $this->createEvent();
        $marketTypeId = DB::table('market_types')->insertGetId(['name' => 'Selection']);

        $this->createResult($eventId, $this->resultTypeId2, $this->participantId1, null);

        $marketId = $this->createMarket($eventId, $marketTypeId);
        $outcomeId = $this->createOutcome($marketId, 3, $this->resultTypeId2, $this->participantId2);

        $this->service->calculateForEvent($eventId);

        $outcome = Outcome::find($outcomeId);
        $this->assertEquals(OutcomeResult::Lose, $outcome->result);
    }

    // Rule 3: outcome_type_id = 2 (Participant-based Boolean)
    public function test_participant_boolean_wins_when_result_exists(): void
    {
        $eventId = $this->createEvent();
        $marketTypeId = DB::table('market_types')->insertGetId(['name' => 'Participant-based Boolean']);

        $this->createResult($eventId, $this->resultTypeId2, $this->participantId1, null);

        $marketId = $this->createMarket($eventId, $marketTypeId);
        $outcomeId = $this->createOutcome($marketId, 2, $this->resultTypeId2, $this->participantId1);

        $this->service->calculateForEvent($eventId);

        $outcome = Outcome::find($outcomeId);
        $this->assertEquals(OutcomeResult::Win, $outcome->result);
    }

    public function test_participant_boolean_loses_when_result_not_found(): void
    {
        $eventId = $this->createEvent();
        $marketTypeId = DB::table('market_types')->insertGetId(['name' => 'Participant-based Boolean']);

        $this->createResult($eventId, $this->resultTypeId2, $this->participantId1, null);

        $marketId = $this->createMarket($eventId, $marketTypeId);
        $outcomeId = $this->createOutcome($marketId, 2, $this->resultTypeId2, $this->participantId2);

        $this->service->calculateForEvent($eventId);

        $outcome = Outcome::find($outcomeId);
        $this->assertEquals(OutcomeResult::Lose, $outcome->result);
    }

    // Rule 4: outcome_type_id = 1 (Boolean)
    public function test_boolean_wins_when_result_exists(): void
    {
        $eventId = $this->createEvent();
        $marketTypeId = DB::table('market_types')->insertGetId(['name' => 'Boolean']);

        $this->createResult($eventId, $this->resultTypeId1, null, [$this->participantId1]);

        $marketId = $this->createMarket($eventId, $marketTypeId);
        $outcomeId = $this->createOutcome($marketId, 1, $this->resultTypeId1, $this->participantId1);

        $this->service->calculateForEvent($eventId);

        $outcome = Outcome::find($outcomeId);
        $this->assertEquals(OutcomeResult::Win, $outcome->result);
    }

    public function test_boolean_loses_when_result_not_found(): void
    {
        $eventId = $this->createEvent();
        $marketTypeId = DB::table('market_types')->insertGetId(['name' => 'Boolean']);

        $marketId = $this->createMarket($eventId, $marketTypeId);
        $outcomeId = $this->createOutcome($marketId, 1, $this->resultTypeId1, $this->participantId1);

        $this->service->calculateForEvent($eventId);

        $outcome = Outcome::find($outcomeId);
        $this->assertEquals(OutcomeResult::Lose, $outcome->result);
    }

    // Rule 5: outcome_type_id = 4, result_type_id = 1 or 2 (Binary Selection with positions)
    public function test_binary_selection_lower_place_wins(): void
    {
        $eventId = $this->createEvent();
        $marketTypeId = DB::table('market_types')->insertGetId(['name' => 'Binary Selection']);

        $this->createResult($eventId, $this->resultTypeId1, null, [
            $this->participantId1,
            $this->participantId2,
        ]);

        $marketId = $this->createMarket($eventId, $marketTypeId);
        $winnerOutcomeId = $this->createOutcome($marketId, 4, $this->resultTypeId1, $this->participantId1);
        $loserOutcomeId = $this->createOutcome($marketId, 4, $this->resultTypeId1, $this->participantId2);

        $this->service->calculateForEvent($eventId);

        $winner = Outcome::find($winnerOutcomeId);
        $loser = Outcome::find($loserOutcomeId);

        $this->assertEquals(OutcomeResult::Win, $winner->result);
        $this->assertEquals(OutcomeResult::Lose, $loser->result);
    }

    public function test_binary_selection_reverse_order(): void
    {
        $eventId = $this->createEvent();
        $marketTypeId = DB::table('market_types')->insertGetId(['name' => 'Binary Selection']);

        $this->createResult($eventId, $this->resultTypeId1, null, [
            $this->participantId2,
            $this->participantId1,
        ]);

        $marketId = $this->createMarket($eventId, $marketTypeId);
        $outcome1Id = $this->createOutcome($marketId, 4, $this->resultTypeId1, $this->participantId1);
        $outcome2Id = $this->createOutcome($marketId, 4, $this->resultTypeId1, $this->participantId2);

        $this->service->calculateForEvent($eventId);

        $outcome1 = Outcome::find($outcome1Id);
        $outcome2 = Outcome::find($outcome2Id);

        $this->assertEquals(OutcomeResult::Lose, $outcome1->result);
        $this->assertEquals(OutcomeResult::Win, $outcome2->result);
    }

    public function test_handles_multiple_markets_independently(): void
    {
        $eventId = $this->createEvent();
        $marketTypeId = DB::table('market_types')->insertGetId(['name' => 'Boolean']);

        $this->createResult($eventId, $this->resultTypeId1, null, [
            $this->participantId1,
        ]);
        $this->createResult($eventId, $this->resultTypeId2, $this->participantId2, null);

        $marketId1 = $this->createMarket($eventId, $marketTypeId);
        $marketId2 = $this->createMarket($eventId, $marketTypeId);

        $outcomeId1 = $this->createOutcome($marketId1, 1, $this->resultTypeId1, $this->participantId1);
        $outcomeId2 = $this->createOutcome($marketId2, 3, $this->resultTypeId2, $this->participantId2);

        $this->service->calculateForEvent($eventId);

        $this->assertEquals(OutcomeResult::Win, Outcome::find($outcomeId1)->result);
        $this->assertEquals(OutcomeResult::Win, Outcome::find($outcomeId2)->result);
    }

    // Rule 6: Exact Score (outcome_type_id 4-52, result_type_id 7)
    public function test_exact_score_wins_when_score_matches(): void
    {
        $eventId = $this->createEvent();
        $marketTypeId = DB::table('market_types')->insertGetId(['name' => 'Outcome list']);

        // Create result type for Score (id=7)
        $resultTypeId7 = $this->createResultTypeWithId(7, 'Score', 'score');

        // Create the actual score result: 2:1
        $this->createResult($eventId, $resultTypeId7, null, ['score' => '2:1']);

        $marketId = $this->createMarket($eventId, $marketTypeId);

        // Create outcome for 2:1 (outcome_type_id 21 corresponds to "2:1")
        // 0:0=4, 0:1=5, ..., 0:6=10, 1:0=11, ..., 1:6=17, 2:0=18, 2:1=19, ...
        $outcomeId = $this->createOutcome($marketId, 19, $resultTypeId7, $this->participantId1);

        $this->service->calculateForEvent($eventId);

        $outcome = Outcome::find($outcomeId);
        $this->assertEquals(OutcomeResult::Win, $outcome->result);
    }

    public function test_exact_score_loses_when_score_does_not_match(): void
    {
        $eventId = $this->createEvent();
        $marketTypeId = DB::table('market_types')->insertGetId(['name' => 'Outcome list']);

        $resultTypeId7 = $this->createResultTypeWithId(7, 'Score', 'score');

        // Actual score is 2:1
        $this->createResult($eventId, $resultTypeId7, null, ['score' => '2:1']);

        $marketId = $this->createMarket($eventId, $marketTypeId);

        // Create outcome for 1:0 (outcome_type_id 11)
        $outcomeId = $this->createOutcome($marketId, 11, $resultTypeId7, $this->participantId1);

        $this->service->calculateForEvent($eventId);

        $outcome = Outcome::find($outcomeId);
        $this->assertEquals(OutcomeResult::Lose, $outcome->result);
    }

    public function test_exact_score_loses_when_no_result(): void
    {
        $eventId = $this->createEvent();
        $marketTypeId = DB::table('market_types')->insertGetId(['name' => 'Outcome list']);

        $resultTypeId7 = $this->createResultTypeWithId(7, 'Score', 'score');

        // No result created
        $marketId = $this->createMarket($eventId, $marketTypeId);

        // Create outcome for 2:1
        $outcomeId = $this->createOutcome($marketId, 19, $resultTypeId7, $this->participantId1);

        $this->service->calculateForEvent($eventId);

        $outcome = Outcome::find($outcomeId);
        $this->assertEquals(OutcomeResult::Lose, $outcome->result);
    }

    public function test_exact_score_loses_when_result_value_missing(): void
    {
        $eventId = $this->createEvent();
        $marketTypeId = DB::table('market_types')->insertGetId(['name' => 'Outcome list']);

        $resultTypeId7 = $this->createResultTypeWithId(7, 'Score', 'score');

        // Create result with null value
        $this->createResult($eventId, $resultTypeId7, null, null);

        $marketId = $this->createMarket($eventId, $marketTypeId);

        // Create outcome for 2:1
        $outcomeId = $this->createOutcome($marketId, 19, $resultTypeId7, $this->participantId1);

        $this->service->calculateForEvent($eventId);

        $outcome = Outcome::find($outcomeId);
        $this->assertEquals(OutcomeResult::Lose, $outcome->result);
    }

    public function test_exact_score_multiple_outcomes_only_one_wins(): void
    {
        $eventId = $this->createEvent();
        $marketTypeId = DB::table('market_types')->insertGetId(['name' => 'Outcome list']);

        $resultTypeId7 = $this->createResultTypeWithId(7, 'Score', 'score');

        // Actual score is 2:1
        $this->createResult($eventId, $resultTypeId7, null, ['score' => '2:1']);

        $marketId = $this->createMarket($eventId, $marketTypeId);

        // Create multiple outcomes
        $outcomeId1 = $this->createOutcome($marketId, 11, $resultTypeId7, $this->participantId1); // 1:0
        $outcomeId2 = $this->createOutcome($marketId, 19, $resultTypeId7, $this->participantId1); // 2:1
        $outcomeId3 = $this->createOutcome($marketId, 27, $resultTypeId7, $this->participantId1); // 3:3

        $this->service->calculateForEvent($eventId);

        $this->assertEquals(OutcomeResult::Lose, Outcome::find($outcomeId1)->result); // 1:0 loses
        $this->assertEquals(OutcomeResult::Win, Outcome::find($outcomeId2)->result);  // 2:1 wins
        $this->assertEquals(OutcomeResult::Lose, Outcome::find($outcomeId3)->result); // 3:3 loses
    }
}
