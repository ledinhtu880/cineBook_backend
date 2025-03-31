<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Helpers\ImageHelper;
use Carbon\Carbon;

class MovieSeeder extends Seeder
{
    public function run()
    {
        // Đọc dữ liệu từ file JSON
        $json = File::get(base_path('python/movies.json'));
        $data = json_decode($json, true);

        $movies = array_merge($data['now_showing'], $data['coming_soon']);

        $genresMap = DB::table('genres')
            ->select('id', 'english_name')
            ->whereNotNull('english_name')
            ->pluck('id', 'english_name')
            ->toArray();

        foreach ($movies as $movie) {
            $releaseDate = null;

            if (isset($movie['full_release_date']) && $movie['full_release_date'] !== 'Không xác định') {
                try {
                    $releaseDate = Carbon::createFromFormat('d/m/Y', $movie['full_release_date'])->format('Y-m-d');
                } catch (\Exception $e) {
                    throw new \Exception("Không thể phân tích ngày: " . ($movie['full_release_date'] ?? 'null'));
                }
            }

            if ($releaseDate === null && isset($movie['release_date']) && $movie['release_date'] !== 'Không xác định') {
                try {
                    // Thử với định dạng đầy đủ trước
                    if (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $movie['release_date'])) {
                        $releaseDate = Carbon::createFromFormat('d/m/Y', $movie['release_date'])->format('Y-m-d');
                    }
                    // Thử với định dạng ngày/tháng (giả định năm hiện tại)
                    else if (preg_match('/^\d{2}\/\d{2}$/', $movie['release_date'])) {
                        $parts = explode('/', $movie['release_date']);
                        $guessedYear = date('Y');
                        $releaseDate = Carbon::createFromFormat('d/m/Y', $parts[0] . '/' . $parts[1] . '/' . $guessedYear)->format('Y-m-d');
                    }
                } catch (\Exception $e) {
                    Log::warning("Không thể phân tích ngày: " . ($movie['release_date'] ?? 'null'));
                    $releaseDate = null;
                }
            }

            $posterPath = null;
            if (isset($movie['poster_url'])) {
                $posterPath = ImageHelper::downloadImage($movie['poster_url']);
            }

            $movieId = DB::table('movies')->insertGetId([
                'title' => $movie['title'],
                'description' => $movie['description'] ?? 'Chưa có mô tả',
                'duration' => $movie['duration'] ?? 0,
                'release_date' => $releaseDate,
                'poster_url' => $posterPath ?? 'posters/default.jpg',
                'trailer_url' => $movie['trailer_url'] ?? null,
                'age_rating' => $movie['age_rating'] ?? 'P',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            if (isset($movie['genres']) && !empty($movie['genres'])) {
                foreach ($movie['genres'] as $genreName) {
                    // Chuẩn hóa tên thể loại
                    $normalizedGenreName = trim($genreName);

                    // Tìm ID thể loại dựa trên tên tiếng Anh
                    $genreId = $genresMap[$normalizedGenreName] ?? null;

                    // Nếu không tìm thấy, thử tìm kiếm tương đối
                    if (!$genreId) {
                        foreach ($genresMap as $englishName => $id) {
                            // Kiểm tra xem tên thể loại có chứa trong danh sách đã biết không
                            if (
                                stripos($normalizedGenreName, $englishName) !== false ||
                                stripos($englishName, $normalizedGenreName) !== false
                            ) {
                                $genreId = $id;
                                break;
                            }
                        }
                    }

                    // Nếu tìm thấy genre, thêm vào bảng liên kết
                    if ($genreId) {
                        DB::table('movie_genres')->insert([
                            'movie_id' => $movieId,
                            'genre_id' => $genreId
                        ]);
                    }
                }
            }
        }
    }
}
