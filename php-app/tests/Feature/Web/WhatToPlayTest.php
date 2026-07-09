<?php

namespace Tests\Feature\Web;

use App\Models\SimplePage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WhatToPlayTest extends TestCase
{
    use RefreshDatabase;

    public function test_what_to_play_returns_active_page(): void
    {
        $page = SimplePage::create([
            'slug' => 'what_to_play',
            'is_active' => true,
        ]);

        $response = $this->getJson(route('api.what_to_play'));

        $response->assertOk()
            ->assertJson([
                'status' => true,
                'data' => [
                    'slug' => 'what_to_play',
                ],
            ]);
    }

    public function test_what_to_play_returns_404_when_inactive(): void
    {
        SimplePage::create([
            'slug' => 'what_to_play',
            'is_active' => false,
        ]);

        $response = $this->getJson(route('api.what_to_play'));

        $response->assertNotFound()
            ->assertJson([
                'status' => false,
                'message' => 'Page not found',
            ]);
    }

    public function test_what_to_play_returns_404_when_not_found(): void
    {
        $response = $this->getJson(route('api.what_to_play'));

        $response->assertNotFound()
            ->assertJson([
                'status' => false,
                'message' => 'Page not found',
            ]);
    }
}
