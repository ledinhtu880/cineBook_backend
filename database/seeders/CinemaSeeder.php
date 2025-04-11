<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CinemaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cinemas = [
            [
                'name' => 'CGV Nguyễn Chí Thanh',
                'address' => 'Số 54 Nguyễn Chí Thanh, Hà Nội',
                'phone' => '19006017',
                'city_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'BHD Star Bitexco',
                'address' => 'Số 2 Hải Triều, Quận 1, Hồ Chí Minh',
                'phone' => '0909910670',
                'city_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Lotte Cinema Landmark',
                'address' => 'Số 5 Phạm Hùng, Ha Noi',
                'phone' => '02466607737',
                'city_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Galaxy Cinema Nguyễn Du',
                'address' => 'Số 116 Nguyễn Du, Hồ Chí Minh',
                'phone' => '19002224',
                'city_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'CGV Aeon Mall',
                'address' => 'Tầng 4, TTTM AEON Mega Maill, 27 Đ. Cổ Linh, Long Biên, Hà Nội',
                'phone' => '19006017',
                'city_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'BHD Star Discovery',
                'address' => 'Số 302 Cầu Giấy, Ha Noi',
                'phone' => '19002099',
                'city_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Galaxy Tân Bình',
                'address' => '246 Đ. Nguyễn Hồng Đào, Tân Bình, Hồ Chí Minh',
                'phone' => '19002224',
                'city_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'CGV Crescent Mall',
                'address' => 'Crescent Mall, Đ. Nguyễn Văn Linh, Tân Phú, Quận 7, Hồ Chí Minh',
                'phone' => '19006017',
                'city_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'BHD Star The Garden',
                'address' => 'Tầng 4 & 5, TTTM The Garden, khu đô thị The Manor, đường Mễ Trì, phường Mỹ Đình 1, quận Nam Từ Liêm, Hà Nội',
                'phone' => '02432068678',
                'city_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];
        foreach ($cinemas as $cinema) {
            DB::table('cinemas')->insert($cinema);
        }
    }
}
