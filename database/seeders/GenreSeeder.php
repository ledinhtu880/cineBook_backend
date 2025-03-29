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
            ['name' => 'Hành động', 'english_name' => 'Action'],
            ['name' => 'Phiêu lưu', 'english_name' => 'Adventure'],
            ['name' => 'Hoạt hình', 'english_name' => 'Animation'],
            ['name' => 'Hài', 'english_name' => 'Comedy'],
            ['name' => 'Tội phạm', 'english_name' => 'Crime'],
            ['name' => 'Tài liệu', 'english_name' => 'Documentary'],
            ['name' => 'Chính kịch', 'english_name' => 'Drama'],
            ['name' => 'Gia đình', 'english_name' => 'Family'],
            ['name' => 'Giả tưởng', 'english_name' => 'Fantasy'],
            ['name' => 'Kinh dị', 'english_name' => 'Horror'],
            ['name' => 'Nhạc', 'english_name' => 'Music'],
            ['name' => 'Lãng mạn', 'english_name' => 'Romance'],
            ['name' => 'Khoa học viễn tưởng', 'english_name' => 'Science Fiction'],
            // Thêm các thể loại khác mà bạn có thể cần
            ['name' => 'Bí ẩn', 'english_name' => 'Mystery'],
            ['name' => 'Lịch sử', 'english_name' => 'History'],
            ['name' => 'Chiến tranh', 'english_name' => 'War'],
            ['name' => 'Viễn tây', 'english_name' => 'Western'],
            ['name' => 'Thần thoại', 'english_name' => 'Mythology'],
            ['name' => 'Tiểu sử', 'english_name' => 'Biography'],
            ['name' => 'Tâm lý', 'english_name' => 'Psychological'],
            ['name' => 'Thể thao', 'english_name' => 'Sport'],
            ['name' => 'Võ thuật', 'english_name' => 'Martial Arts'],
        ];

        foreach ($genres as $genre) {
            DB::table('genres')->insert([
                'name' => $genre['name'],
                'english_name' => $genre['english_name'],
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
