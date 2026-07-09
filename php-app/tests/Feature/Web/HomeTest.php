<?php

namespace Tests\Feature\Web;

use App\Models\ContentPage;
use App\Models\SimplePage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomeTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_returns_page_with_contents(): void
    {
        $page = SimplePage::create(['slug' => 'home']);
        $page->contents()->create([
            'model_type' => SimplePage::class,
            'lang' => 'en',
            'title' => 'Home Title',
            'content' => '<p>Home content</p>',
        ]);

        $response = $this->getJson(route('api.home'));

        $response->assertOk()
            ->assertJson([
                'status' => true,
                'data' => [
                    'slug' => 'home',
                    'contents' => [
                        ['lang' => 'en', 'title' => 'Home Title'],
                    ],
                ],
            ]);
    }

    public function test_home_returns_404_when_not_found(): void
    {
        $response = $this->getJson(route('api.home'));

        $response->assertNotFound()
            ->assertJson([
                'status' => false,
                'message' => 'Page not found',
            ]);
    }
}
