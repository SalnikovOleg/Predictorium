<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Simracing', 'slug' => 'simracing', 'is_active' => true, 'sort_order' => 1, 'icon' => 'heroicon-o-football'],
        ];

        DB::table('categories')->insert($categories);
    }
}
