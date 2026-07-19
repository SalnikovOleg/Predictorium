<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MarketTemplateSeeder extends Seeder
{
    public function run(): void
    {
        $marketTemplates = [
            ['category_id' => 1,
                'name' => 'Race winner',
                'description' => 'Select who will win the race',
                'market_type_id' => 3,
                'outcome_type_ids' => '[3]',
                'param1' => 1
            ],
            ['category_id' => 1,
                'name' => 'Pole Position',
                'description' => 'Select who will win the pole',
                'market_type_id' => 3,
                'outcome_type_ids' => '[3]',
                'param1' => 1
            ],
            ['category_id' => 1,
                'name' => 'Who will be on the podium?',
                'description' => 'Select (max 3) who will be on the podium?',
                'market_type_id' => 3,
                'outcome_type_ids' => '[3]',
                'param1' => 3
            ],
            ['category_id' => 1,
                'name' => 'Best lap',
                'description' => 'Select who will set the fastest lap?',
                'market_type_id' => 3,
                'outcome_type_ids' => '[3]',
                'param1' => 1
            ],
            ['category_id' => 1,
                'name' => 'Drive-Through',
                'description' => 'Will there be DT in the race or not?',
                'market_type_id' => 1,
                'outcome_type_ids' => '[1,2]',
                'param1' => null
            ],
            ['category_id' => 1,
                'name' => 'Stop-and-Go',
                'description' => 'Will there be Stop-and-Go in the race or not?',
                'market_type_id' => 1,
                'outcome_type_ids' => '[1,2]',
                'param1' => null
            ],
            ['category_id' => 1,
                'name' => 'Drive-Through',
                'description' => 'Will there be DT for selected driver?',
                'market_type_id' => 2,
                'outcome_type_ids' => '[1,2]',
                'param1' => null
            ],
            ['category_id' => 1,
                'name' => 'Stop-and-Go',
                'description' => 'Will there be Stop-and-Go for selected driver??',
                'market_type_id' => 2,
                'outcome_type_ids' => '[1,2]',
                'param1' => null
            ],
            ['category_id' => 1,
                'name' => 'Which of them will finish higher?',
                'description' => 'Choose who will finish higher?',
                'market_type_id' => 4,
                'outcome_type_ids' => '[3]',
                'param1' => 1
            ],
            ['category_id' => 1,
                'name' => 'Which of them will qualify higher?',
                'description' => 'Choose who will qualify higher?',
                'market_type_id' => 4,
                'outcome_type_ids' => '[3]',
                'param1' => 1
            ],
        ];

        DB::table('market_templates')->insert($marketTemplates);
    }
}
