<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cities = [
            [
                'name' => 'Hà Nội',
                'code' => 'HN',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Hồ Chí Minh',
                'code' => 'HCM',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Đà Nẵng',
                'code' => 'DN',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];

        foreach ($cities as $city) {
            DB::table('cities')->insert($city);
        }
    }
}
