<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OutcomeTypeSeeder extends Seeder
{
    public function run(): void
    {
        $outcomeTypes = [
            ['name' => 'yes'],
            ['name' => 'no'],
            ['name' => 'participant'],
        ];

        DB::table('outcome_types')->insert($outcomeTypes);
    }
}
