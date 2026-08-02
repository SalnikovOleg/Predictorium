<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TaxonomySeeder extends Seeder
{
    public function run(): void
    {
        $values = [
            ["country", "Австралия"],
            ["country", "Австрия"],
            ["country", "Алжир"],
            ["country", "Англия"],
            ["country", "Аргентина"],
            ["country", "Бельгия"],
            ["country", "Босния и Герцеговина"],
            ["country", "Бразилия"],
            ["country", "Гаити"],
            ["country", "Гана"],
            ["country", "Германия"],
            ["country", "ДР Конго"],
            ["country", "Египет"],
            ["country", "Иордания"],
            ["country", "Ирак"],
            ["country", "Иран"],
            ["country", "Испания"],
            ["country", "Кабо-Верде"],
            ["country", "Канада"],
            ["country", "Катар"],
            ["country", "Колумбия"],
            ["country", "Кот-д\'Ивуар"],
            ["country", "Кюрасао"],
            ["country", "Марокко"],
            ["country", "Мексика"],
            ["country", "Нидерланды"],
            ["country", "Новая Зеландия"],
            ["country", "Норвегия"],
            ["country", "Панама"],
            ["country", "Парагвай"],
            ["country", "Португалия"],
            ["country", "Республика Корея"],
            ["country", "Саудовская Аравия"],
            ["country", "Сенегал"],
            ["country", "США"],
            ["country", "Тунис"],
            ["country", "Турция"],
            ["country", "Узбекистан"],
            ["country", "Уругвай"],
            ["country", "Франция"],
            ["country", "Хорватия"],
            ["country", "Чехия"],
            ["country", "Швейцария"],
            ["country", "Швеция"],
            ["country", "Шотландия"],
            ["country", "Эквадор"],
            ["country", "ЮАР"],
            ["country", "Япония"],
            ["general", "Persons"],
            ["general", "Brands"],
            ["general", "Games"],
        ];

        DB::table('result_types')->insert($values);
    }
}
