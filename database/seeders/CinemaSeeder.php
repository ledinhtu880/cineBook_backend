<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CinemaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cinemas = [
            [
                'name' => 'CGV Vincom Center Bà Triệu',
                'address' => 'Tầng 6, Vincom Center Hà Nội, 191 Bà Triệu, Quận Hai Bà Trưng, Hà Nội',
                'phone' => '19006017',
                'opening_hours' => '09:00 - 22:30',
                'city_id' => 1,
                'image' => 'storage/logos/cgv.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'CGV Vincom Times City',
                'address' => 'Tầng B1, Vincom Mega Mall Times City, 458 Minh Khai, Quận Hai Bà Trưng, Hà Nội',
                'phone' => '19006017',
                'opening_hours' => '09:00 - 22:30',
                'city_id' => 1,
                'image' => 'storage/logos/cgv.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'CGV Vincom Trần Duy Hưng',
                'address' => 'Tầng 5, TTTM Vincom Center Trần Duy Hưng, Trần Duy Hưng, P. Trung Hòa, Quận Cầu Giấy, Hà Nội',
                'phone' => '19006017',
                'opening_hours' => '09:00 - 22:30',
                'city_id' => 1,
                'image' => 'storage/logos/cgv.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'CGV Vincom Royal City',
                'address' => 'Tầng B2, TTTM Vincom Royal City, 72A Nguyễn Trãi, Quận Thanh Xuân, Hà Nội',
                'phone' => '19006017',
                'opening_hours' => '09:00 - 22:30',
                'city_id' => 1,
                'image' => 'storage/logos/cgv.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'CGV Vincom Nguyễn Chí Thanh',
                'address' => 'Số 54A Nguyễn Chí Thanh, P. Láng Thượng, Quận Đống Đa, Hà Nội',
                'phone' => '19006017',
                'opening_hours' => '09:00 - 22:30',
                'city_id' => 1,
                'image' => 'storage/logos/cgv.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'CGV Vincom Thủ Đức',
                'address' => 'Tầng 5, TTTM Vincom Thủ Đức, 216 Võ Văn Ngân, P. Bình Thọ, Quận Thủ Đức, Hồ Chí Minh',
                'phone' => '19006017',
                'opening_hours' => '10:00 - 23:00',
                'city_id' => 2,
                'image' => 'storage/logos/cgv.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'CGV Vincom Landmark 81',
                'address' => 'B1, Vincom Center Landmark 81, 722 Điện Biên Phủ, P. 22, Quận Bình Thạnh, Hồ Chí Minh',
                'phone' => '19006017',
                'opening_hours' => '10:00 - 23:00',
                'city_id' => 2,
                'image' => 'storage/logos/cgv.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'CGV Vincom Gò Vấp',
                'address' => 'Tầng 5 TTTM Vincom Plaza Gò Vấp, 12 Phan Văn Trị, P.7, Quận Gò Vấp, Hồ Chí Minh',
                'phone' => '19006017',
                'opening_hours' => '10:00 - 23:00',
                'city_id' => 2,
                'image' => 'storage/logos/cgv.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'CGV Vincom Đồng Khởi',
                'address' => 'Tầng 3, TTTM Vincom Center Đồng Khởi, 72 Lê Thánh Tôn & 45A Lý Tự Trọng, Quận 1, Hồ Chí Minh',
                'phone' => '19006017',
                'opening_hours' => '10:00 - 23:00',
                'city_id' => 2,
                'image' => 'storage/logos/cgv.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'CGV Vincom Đà Nẵng',
                'address' => 'Tầng 4, TTTM Vincom Đà Nẵng, Ngô Quyền, P. An Hải Bắc, Quận Sơn Trà, Tp. Đà Nẵng',
                'phone' => '19006017',
                'opening_hours' => '10:00 - 23:00',
                'city_id' => 3,
                'image' => 'storage/logos/cgv.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'CGV Vĩnh Trung Plaza',
                'address' => '255-257 đường Hùng Vương, Q. Thanh Khê, Tp. Đà Nẵng',
                'phone' => '19006017',
                'opening_hours' => '10:00 - 23:00',
                'city_id' => 3,
                'image' => 'storage/logos/cgv.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'BHD Star Discovery',
                'address' => 'Tầng 8, TTTM Discovery – 302 Cầu Giấy, P.Dịch Vọng, Quận Cầu Giấy, Hà Nội',
                'phone' => '19002099',
                'opening_hours' => '09:30 - 22:30',
                'city_id' => 1,
                'image' => 'storage/logos/bhd.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'BHD Star The Garden',
                'address' => 'Tầng 4, TTTM Garden Shopping Center, Phố Mễ Trì, P.Mỹ Đình 1, Quận Nam Từ Liêm, Hà Nội',
                'phone' => '02432068678',
                'opening_hours' => '09:00 - 22:30',
                'city_id' => 1,
                'image' => 'storage/logos/bhd.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'BHD Star Phạm Ngọc Thạch',
                'address' => 'Tầng 8, Vincom Center Phạm Ngọc Thạch, 02 Phạm Ngọc Thạch, P.Trung Tự, Quận Đống Đa, Hà Nội',
                'phone' => '02436373355',
                'opening_hours' => '09:00 - 22:30',
                'city_id' => 1,
                'image' => 'storage/logos/bhd.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'BHD Star Quang Trung',
                'address' => 'Tầng B2, Vincom Plaza Quang Trung, 190 Quang Trung, P.10, Quận Gò Vấp, Hồ Chí Minh',
                'phone' => '02839892468',
                'opening_hours' => '08:30 - 22:30',
                'city_id' => 2,
                'image' => 'storage/logos/bhd.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'BHD Star Thảo Điền',
                'address' => 'Tầng 5, Vincom Mega Mall Thảo Điền, 159 Xa Lộ Hà Nội, P.Thảo Điền, TP.Thủ Đức, Hồ Chí Minh',
                'phone' => '02837446969',
                'opening_hours' => '08:30 - 22:30',
                'city_id' => 2,
                'image' => 'storage/logos/bhd.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($cinemas as $cinema) {
            $cinema['slug'] = Str::slug($cinema['name']);

            DB::table('cinemas')->insert($cinema);
        }
    }
}
