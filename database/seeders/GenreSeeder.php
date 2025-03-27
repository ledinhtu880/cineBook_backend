<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GenreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $genres = [
            'Hành động',
            'Phiêu lưu',
            'Hoạt hình',
            'Hài',
            'Tội phạm',
            'Tài liệu',
            'Chính kịch',
            'Gia đình',
            'Giả tưởng',
            'Lịch sử',
            'Kinh dị',
            'Nhạc',
            'Bí ẩn',
            'Lãng mạn',
            'Khoa học viễn tưởng',
            'Gây cấn',
            'Chiến tranh'
        ];

        foreach ($genres as $genre) {
            DB::table('genres')->insert([
                'name' => $genre,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
