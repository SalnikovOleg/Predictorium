<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MarketTemplateSeeder extends Seeder
{
    public function run(): void
    {
        $marketTemplates = [
            ['category_id' => 1, 'name' => 'Race winner', 'market_type_id' => 3, 'outcome_type_ids' => '[3]', 'param1' => 1],
            ['category_id' => 1, 'name' => 'Pole Position', 'market_type_id' => 3, 'outcome_type_ids' => '[3]', 'param1' => 1],
            ['category_id' => 1, 'name' => 'Who will be on the podium?', 'market_type_id' => 3, 'outcome_type_ids' => '[3]', 'param1' => 3],
            ['category_id' => 1, 'name' => 'Best lap', 'market_type_id' => 3, 'outcome_type_ids' => '[3]', 'param1' => 1],
            ['category_id' => 1, 'name' => 'Drive-Through', 'market_type_id' => 1, 'outcome_type_ids' => '[1,2]', 'param1' => null],
            ['category_id' => 1, 'name' => 'Stop-and-Go', 'market_type_id' => 1, 'outcome_type_ids' => '[1,2]', 'param1' => null],
            ['category_id' => 1, 'name' => 'Drive-Through', 'market_type_id' => 2, 'outcome_type_ids' => '[1,2]', 'param1' => null],
            ['category_id' => 1, 'name' => 'Stop-and-Go', 'market_type_id' => 2, 'outcome_type_ids' => '[1,2]', 'param1' => null],
            ['category_id' => 1, 'name' => 'Which of them will finish higher?', 'market_type_id' => 4, 'outcome_type_ids' => '[3]', 'param1' => 1],
            ['category_id' => 1, 'name' => 'Which of them will qualifies higher?', 'market_type_id' => 4, 'outcome_type_ids' => '[3]', 'param1' => 1],
        ];

        DB::table('market_templates')->insert($marketTemplates);
    }
}
