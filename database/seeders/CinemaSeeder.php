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
                'address' => 'Số Số 54 Nguyễn Chí Thanh, Hà Nội',
                'phone' => '024-1234-5678',
                'city_id' => 1
            ],
            [
                'name' => 'BHD Star Bitexco',
                'address' => 'Số 2 Hải Triều, Quận 1, Hồ Chí Minh',
                'phone' => '028-2345-6789',
                'city_id' => 2
            ],
            [
                'name' => 'Lotte Cinema Landmark',
                'address' => 'Số 5 Phạm Hùng, Ha Noi',
                'phone' => '024-3456-7890',
                'city_id' => 1
            ],
            [
                'name' => 'Galaxy Cinema Nguyễn Du',
                'address' => 'Số 116 Nguyễn Du, Hồ Chí Minh',
                'phone' => '028-4567-8901',
                'city_id' => 2
            ],
            [
                'name' => 'CGV Aeon Mall',
                'address' => 'Số 30 Bờ Bao Tân Thắng, Quận Tân Phú, Hồ Chí Minh',
                'phone' => '028-5678-9012',
                'city_id' => 2
            ],
            [
                'name' => 'BHD Star Discovery',
                'address' => 'Số 302 Cầu Giấy, Ha Noi',
                'phone' => '024-6789-0123',
                'city_id' => 1
            ],
            [
                'name' => 'Galaxy Tân Bình',
                'address' => '246 Đ. Nguyễn Hồng Đào, Tân Bình, Hồ Chí Minh',
                'phone' => '028-8901-2345',
                'city_id' => 2
            ],
            [
                'name' => 'CGV Crescent Mall',
                'address' => 'Crescent Mall, Đ. Nguyễn Văn Linh, Tân Phú, Quận 7, Hồ Chí Minh',
                'phone' => '028-9012-3456',
                'city_id' => 2
            ],
            [
                'name' => 'BHD Star The Garden',
                'address' => 'Tầng 4 & 5, TTTM The Garden, khu đô thị The Manor, đường Mễ Trì, phường Mỹ Đình 1, quận Nam Từ Liêm, Hà Nội',
                'phone' => '024-0123-4567',
                'city_id' => 1
            ]
        ];
        foreach ($cinemas as $cinema) {
            DB::table('cinemas')->insert($cinema);
        }
    }
}
