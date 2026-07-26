<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Simracing', 'slug' => 'simracing', 'is_active' => true, 'sort_order' => 1],
            ['name' => 'Soccer', 'slug' => 'soccer', 'is_active' => true, 'sort_order' => 2],
            ['name' => 'Other', 'slug' => 'other', 'is_active' => true, 'sort_order' => 3],
        ];

        DB::table('categories')->insert($categories);
    }
}
