<?php

namespace Database\Seeders;

use App\Enums\ValueType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ResultTypeSeeder extends Seeder
{
    public function run(): void
    {
        $resultTypes = [
            ['category_id' => 1, 'name' => 'Race Positions', 'value_type' => ValueType::Positions->value],
            ['category_id' => 1, 'name' => 'Qualify Positions', 'value_type' => ValueType::Positions->value],
            ['category_id' => 1, 'name' => 'Best Lap', 'value_type' => ValueType::Participant->value],
            ['category_id' => 1, 'name' => 'Drive-Through', 'value_type' => ValueType::Participant->value],
            ['category_id' => 1, 'name' => 'Stop-and-Go', 'value_type' => ValueType::Participant->value],
            ['category_id' => 2, 'name' => 'Winner', 'value_type' => ValueType::Participant->value],
            ['category_id' => 2, 'name' => 'Score', 'value_type' => ValueType::Score->value],
        ];

        DB::table('result_types')->insert($resultTypes);
    }
}
