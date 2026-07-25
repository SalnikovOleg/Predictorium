<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MarketTypeSeeder extends Seeder
{
    public function run(): void
    {
        $marketTypes = [
            ['name' => 'Boolean ( Yes / No )'],
            ['name' => 'Participant ( Yes / No )'],
            ['name' => 'Select participants from the list'],
            ['name' => 'Select one from two (participants)'],
            ['name' => 'Outcome list'],
        ];

        DB::table('market_types')->insert($marketTypes);
    }
}
