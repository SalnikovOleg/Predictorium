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

        // Soccer match score outcomes (0-0 through 6-6)
        for ($home = 0; $home <= 6; $home++) {
            for ($away = 0; $away <= 6; $away++) {
                $outcomeTypes[] = ['name' => "{$home}:{$away}"];
            }
        }

        DB::table('outcome_types')->insert($outcomeTypes);
    }
}
