<?php

namespace Tests\Feature\Trading;

use App\Models\Category;
use App\Models\Tournament;
use App\Models\TournamentConfig;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TournamentsByCategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_tournaments_by_category_slug(): void
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

        $response = $this->getJson(route('api.categories.tournaments', $category->id));

        $response->assertOk()
            ->assertJson([
                'status' => true,
                'data' => [
                    [
                        'id' => $tournament->id,
                        'name' => '2026 Season',
                        'slug' => '2026-season',
                        'status' => 'active',
                        'category' => [
                            'id' => $category->id,
                            'name' => 'Formula 1',
                            'slug' => 'formula-1',
                        ],
                    ],
                ],
            ]);
    }

    public function test_get_tournaments_by_category_slug_returns_404_when_not_found(): void
    {
        $response = $this->getJson(route('api.categories.tournaments', 0));

        $response->assertNotFound()
            ->assertJson([
                'status' => false,
                'message' => 'No tournaments found for this category',
            ]);
    }

    public function test_get_tournaments_by_category_slug_returns_empty_when_category_has_no_tournaments(): void
    {
        $category = Category::create([
            'name' => 'Empty Category',
            'slug' => 'empty-category',
            'is_active' => true,
        ]);

        $response = $this->getJson(route('api.categories.tournaments', $category->id));

        $response->assertNotFound()
            ->assertJson([
                'status' => false,
                'message' => 'No tournaments found for this category',
            ]);
    }
}
