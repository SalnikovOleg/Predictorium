<?php

namespace Tests\Feature\Trading;

use App\Models\Group;
use App\Models\Stake;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class StakeTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Group $group;
    private int $eventId;
    private int $marketId;
    private int $outcomeId;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->group = Group::create(['name' => 'general', 'owner_id' => $this->user->id]);

        $categoryId = DB::table('categories')->insertGetId([
            'taxonomy_type' => 'community',
            'name' => 'Test',
            'slug' => 'test',
            'is_active' => true,
        ]);

        $taxonomyId = DB::table('taxonomies')->insertGetId([
            'type' => 'community',
            'name' => 'Test Taxonomy',
            'icon' => 'heroicon-o-test',
        ]);

        $configId = DB::table('tournament_configs')->insertGetId([
            'category_id' => $categoryId,
            'name' => 'Default',
        ]);

        $tournamentId = DB::table('tournaments')->insertGetId([
            'category_id' => $categoryId,
            'taxonomy_id' => $taxonomyId,
            'name' => 'Test Tournament',
            'slug' => 'test-tournament',
            'config_id' => $configId,
        ]);

        $this->eventId = DB::table('events')->insertGetId([
            'tournament_id' => $tournamentId,
            'name' => 'Test Event',
            'slug' => 'test-event',
            'status' => 'active',
        ]);

        $marketTypeId = DB::table('market_types')->insertGetId(['name' => 'Boolean']);

        $marketTemplateId = DB::table('market_templates')->insertGetId([
            'name' => 'Winner',
            'category_id' => $categoryId,
            'market_type_id' => $marketTypeId,
        ]);

        $this->marketId = DB::table('markets')->insertGetId([
            'event_id' => $this->eventId,
            'market_template_id' => $marketTemplateId,
        ]);

        $outcomeTypeId = DB::table('outcome_types')->insertGetId(['name' => 'Yes']);

        $this->outcomeId = DB::table('outcomes')->insertGetId([
            'market_id' => $this->marketId,
            'outcome_type_id' => $outcomeTypeId,
            'coef' => 1.50,
        ]);
    }

    public function test_authenticated_user_can_store_stake(): void
    {
        $response = $this->actingAs($this->user)
            ->postJson(route('api.stakes.store'), [
                'group_id' => $this->group->id,
                'user_id' => $this->user->id,
                'event_id' => $this->eventId,
                'market_id' => $this->marketId,
                'outcome_id' => $this->outcomeId,
            ]);

        $response->assertCreated()
            ->assertJson([
                'status' => true,
                'message' => 'Stake saved successfully',
            ])
            ->assertJsonStructure([
                'data' => ['id', 'group_id', 'user_id', 'event_id', 'market_id', 'outcome_id'],
            ]);

        $this->assertDatabaseHas('stakes', [
            'group_id' => $this->group->id,
            'user_id' => $this->user->id,
            'event_id' => $this->eventId,
            'market_id' => $this->marketId,
            'outcome_id' => $this->outcomeId,
        ]);
    }

    public function test_unauthenticated_user_cannot_store_stake(): void
    {
        $response = $this->postJson(route('api.stakes.store'), [
            'group_id' => $this->group->id,
            'user_id' => $this->user->id,
            'event_id' => $this->eventId,
            'market_id' => $this->marketId,
            'outcome_id' => $this->outcomeId,
        ]);

        $response->assertStatus(401);
    }

    public function test_stake_is_updated_when_record_exists(): void
    {
        $existingStake = Stake::create([
            'group_id' => $this->group->id,
            'user_id' => $this->user->id,
            'event_id' => $this->eventId,
            'market_id' => $this->marketId,
            'outcome_id' => $this->outcomeId,
        ]);

        $newOutcomeId = DB::table('outcomes')->insertGetId([
            'market_id' => $this->marketId,
            'outcome_type_id' => DB::table('outcome_types')->first()->id,
            'coef' => 2.00,
        ]);

        $response = $this->actingAs($this->user)
            ->postJson(route('api.stakes.store'), [
                'group_id' => $this->group->id,
                'user_id' => $this->user->id,
                'event_id' => $this->eventId,
                'market_id' => $this->marketId,
                'outcome_id' => $newOutcomeId,
            ]);

        $response->assertOk();

        $this->assertDatabaseHas('stakes', [
            'id' => $existingStake->id,
            'outcome_id' => $newOutcomeId,
        ]);

        $this->assertDatabaseCount('stakes', 1);
    }

    public function test_stake_rejected_when_event_is_not_active(): void
    {
        DB::table('events')->where('id', $this->eventId)->update(['status' => 'finished']);

        $response = $this->actingAs($this->user)
            ->postJson(route('api.stakes.store'), [
                'group_id' => $this->group->id,
                'user_id' => $this->user->id,
                'event_id' => $this->eventId,
                'market_id' => $this->marketId,
                'outcome_id' => $this->outcomeId,
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('event_id');
    }

    public function test_store_stake_requires_group_id(): void
    {
        $response = $this->actingAs($this->user)
            ->postJson(route('api.stakes.store'), [
                'user_id' => $this->user->id,
                'event_id' => $this->eventId,
                'market_id' => $this->marketId,
                'outcome_id' => $this->outcomeId,
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('group_id');
    }

    public function test_store_stake_requires_market_id(): void
    {
        $response = $this->actingAs($this->user)
            ->postJson(route('api.stakes.store'), [
                'group_id' => $this->group->id,
                'user_id' => $this->user->id,
                'event_id' => $this->eventId,
                'outcome_id' => $this->outcomeId,
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('market_id');
    }

    public function test_store_stake_requires_outcome_id(): void
    {
        $response = $this->actingAs($this->user)
            ->postJson(route('api.stakes.store'), [
                'group_id' => $this->group->id,
                'user_id' => $this->user->id,
                'event_id' => $this->eventId,
                'market_id' => $this->marketId,
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('outcome_id');
    }

    public function test_authenticated_user_can_get_stakes(): void
    {
        Stake::create([
            'group_id' => $this->group->id,
            'user_id' => $this->user->id,
            'event_id' => $this->eventId,
            'market_id' => $this->marketId,
            'outcome_id' => $this->outcomeId,
        ]);

        $response = $this->actingAs($this->user)
            ->getJson(route('api.stakes.index', [
                'group_id' => $this->group->id,
                'user_id' => $this->user->id,
                'event_id' => $this->eventId,
            ]));

        $response->assertOk()
            ->assertJson([
                'status' => true,
                'data' => [
                    [
                        'market_id' => $this->marketId,
                        'outcome_id' => $this->outcomeId,
                        'outcome_ids' => null,
                    ],
                ],
            ]);
    }

    public function test_get_stakes_returns_empty_array_when_no_stakes(): void
    {
        $response = $this->actingAs($this->user)
            ->getJson(route('api.stakes.index', [
                'group_id' => $this->group->id,
                'user_id' => $this->user->id,
                'event_id' => $this->eventId,
            ]));

        $response->assertOk()
            ->assertJson([
                'status' => true,
                'data' => [],
            ]);
    }

    public function test_unauthenticated_user_cannot_get_stakes(): void
    {
        $response = $this->getJson(route('api.stakes.index', [
            'group_id' => $this->group->id,
            'user_id' => $this->user->id,
            'event_id' => $this->eventId,
        ]));

        $response->assertStatus(401);
    }

    public function test_get_stakes_filters_by_group_user_event(): void
    {
        Stake::create([
            'group_id' => $this->group->id,
            'user_id' => $this->user->id,
            'event_id' => $this->eventId,
            'market_id' => $this->marketId,
            'outcome_id' => $this->outcomeId,
        ]);

        $otherUser = User::factory()->create();
        Stake::create([
            'group_id' => $this->group->id,
            'user_id' => $otherUser->id,
            'event_id' => $this->eventId,
            'market_id' => $this->marketId,
            'outcome_id' => $this->outcomeId,
        ]);

        $response = $this->actingAs($this->user)
            ->getJson(route('api.stakes.index', [
                'group_id' => $this->group->id,
                'user_id' => $this->user->id,
                'event_id' => $this->eventId,
            ]));

        $response->assertOk()
            ->assertJsonCount(1, 'data');
    }
}
