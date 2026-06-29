<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ResultTypeSeeder extends Seeder
{
    public function run(): void
    {
        $resultTypes = [
            ['category_id' => 1, 'name' => 'Race Positions', 'value_type' => 'positions'],
            ['category_id' => 1, 'name' => 'Qualify Positions', 'value_type' => 'positions'],
            ['category_id' => 1, 'name' => 'Best Lap', 'value_type' => 'participant'],
            ['category_id' => 1, 'name' => 'Drive-Through', 'value_type' => 'participant'],
            ['category_id' => 1, 'name' => 'Stop-and-Go', 'value_type' => 'participant'],
        ];

        DB::table('result_types')->insert($resultTypes);
    }
}
