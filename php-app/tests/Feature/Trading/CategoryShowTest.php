<?php

namespace Tests\Feature\Trading;

use App\Models\Category;
use App\Models\Tournament;
use App\Models\TournamentConfig;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryShowTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_category_by_slug(): void
    {
        $category = Category::create([
            'name' => 'Formula 1',
            'slug' => 'formula-1',
            'is_active' => true,
            'sort_order' => 1,
            'icon' => 'flag',
        ]);

        $config = TournamentConfig::create([
            'category_id' => $category->id,
            'name' => 'F1 Config',
        ]);

        Tournament::create([
            'category_id' => $category->id,
            'config_id' => $config->id,
            'name' => '2026 Season',
            'slug' => '2026-season',
            'status' => 'active',
            'start_date' => '2026-03-01',
            'end_date' => '2026-11-30',
        ]);

        $response = $this->getJson(route('api.categories.show', 'formula-1'));

        $response->assertOk()
            ->assertJson([
                'status' => true,
                'data' => [
                    'name' => 'Formula 1',
                    'slug' => 'formula-1',
                    'is_active' => true,
                    'icon' => 'flag',
                ],
            ])
            ->assertJsonStructure([
                'data' => [
                    'id', 'name', 'slug', 'is_active', 'sort_order', 'icon',
                    'contents',
                    'tournaments' => [
                        ['id', 'name', 'slug', 'status'],
                    ],
                ],
            ]);
    }

    public function test_get_category_by_slug_includes_contents(): void
    {
        $category = Category::create([
            'name' => 'Formula 1',
            'slug' => 'formula-1',
            'is_active' => true,
        ]);

        $category->contents()->create([
            'title' => 'About F1',
            'content' => 'Formula 1 is the highest class...',
            'lang' => 'en',
        ]);

        $response = $this->getJson(route('api.categories.show', 'formula-1'));

        $response->assertOk()
            ->assertJsonPath('data.contents.0.title', 'About F1');
    }

    public function test_get_category_by_slug_excludes_inactive_tournaments(): void
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

        Tournament::create([
            'category_id' => $category->id,
            'config_id' => $config->id,
            'name' => 'Draft Season',
            'slug' => 'draft-season',
            'status' => 'draft',
        ]);

        $response = $this->getJson(route('api.categories.show', 'formula-1'));

        $response->assertOk()
            ->assertJsonPath('data.tournaments', []);
    }

    public function test_get_category_by_slug_returns_404_when_not_found(): void
    {
        $response = $this->getJson(route('api.categories.show', 'non-existent'));

        $response->assertNotFound()
            ->assertJson([
                'status' => false,
                'message' => 'Category not found',
            ]);
    }
}
