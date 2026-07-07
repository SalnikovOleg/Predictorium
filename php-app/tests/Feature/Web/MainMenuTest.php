<?php

namespace Tests\Feature\Web;

use VanOns\FilamentNavigation\Models\Navigation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MainMenuTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_main_menu_returns_navigation_items(): void
    {
        $navigation = Navigation::create([
            'name' => 'Main Menu',
            'handle' => 'main_menu',
            'items' => [
                ['label' => 'Home', 'data' => ['url' => '/'], 'children'=>[]],
                ['label' => 'About', 'data' => ['url' => '/about'], 'children'=>[]],
            ],
        ]);

        $response = $this->getJson(route('api.main-menu'));

        $response->assertOk()
            ->assertJson([
                'status' => true,
                'data' => [
                    'items' => [
                        ['label' => 'Home', 'url' => '/', 'children'=>[]],
                        ['label' => 'About', 'url' => '/about', 'children'=>[]],
                    ],
                ],
            ]);
    }

    public function test_get_main_menu_returns_404_when_not_found(): void
    {
        $response = $this->getJson(route('api.main-menu'));

        $response->assertNotFound()
            ->assertJson([
                'status' => false,
                'message' => 'Main menu not found',
            ]);
    }
}
