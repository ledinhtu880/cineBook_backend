<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class MovieSeeder extends Seeder
{
    public function run()
    {
        $posterPath = public_path('storage/posters');
        if (File::exists($posterPath)) {
            File::cleanDirectory($posterPath); // Xóa toàn bộ file trong folder
        }

        $json = File::get(base_path('python/movies.json'));
        $data = json_decode($json, true);

        $nowShowing = $data['now_showing'] ?? [];
        $comingSoon = $data['coming_soon'] ?? [];

        $nowShowingTitles = [];
        foreach ($nowShowing as $movie) {
            $nowShowingTitles[] = strtolower(trim($movie['title']));
        }

        $uniqueComingSoon = [];
        $duplicateCount = 0;
        foreach ($comingSoon as $movie) {
            $title = strtolower(trim($movie['title']));
            if (!in_array($title, $nowShowingTitles)) {
                $uniqueComingSoon[] = $movie;
            } else {
                $duplicateCount++;
            }
        }

        $movies = array_merge($nowShowing, $uniqueComingSoon);

        $genresMap = [];
        $existingGenres = DB::table('genres')->get();

        $foundGenres = [];
        foreach ($movies as $movie) {
            if (isset($movie['genres']) && is_array($movie['genres'])) {
                foreach ($movie['genres'] as $genre) {
                    $foundGenres[] = trim($genre);
                }
            }
        }

        $foundGenres = array_unique(array_map('trim', $foundGenres));

        foreach ($foundGenres as $genreName) {
            $existingGenre = $existingGenres->first(function ($genre) use ($genreName) {
                return strtolower($genre->name) === strtolower($genreName);
            });

            if (!$existingGenre) {
                $genreId = DB::table('genres')->insertGetId([
                    'name' => $genreName,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $genresMap[strtolower($genreName)] = $genreId;
            } else {
                $genresMap[strtolower($genreName)] = $existingGenre->id;
            }
        }

        foreach ($movies as $movie) {
            $releaseDate = null;
            if (isset($movie['release_date']) && $movie['release_date'] !== 'Không xác định') {
                try {
                    // Xử lý định dạng dd/mm/yyyy
                    if (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $movie['release_date'])) {
                        $releaseDate = Carbon::createFromFormat('d/m/Y', $movie['release_date'])->format('Y-m-d');
                    }
                } catch (\Exception $e) {
                    $releaseDate = null;
                }
            }

            // Xử lý poster
            $posterPath = null;
            if (isset($movie['poster_url'])) {
                $posterPath = $this->downloadImageIfNotExists($movie['poster_url'],  'posters');
            }

            // Xử lý banner
            $bannerPath = null;
            if (isset($movie['backdrop_url'])) {
                $bannerPath = $this->downloadImageIfNotExists($movie['backdrop_url'],  'banners');
            }

            $duration = 0;
            if (isset($movie['duration'])) {
                preg_match('/(\d+)/', $movie['duration'], $matches);
                if (isset($matches[1])) {
                    $duration = (int)$matches[1];
                }
            }

            $rating = null;
            if (isset($movie['rating']) && is_string($movie['rating'])) {
                $rating = floatval($movie['rating']);
            }

            // Lưu phim vào DB
            $movieId = DB::table('movies')->insertGetId([
                'title' => $movie['title'],
                'slug' => Str::slug($movie['title']),
                'description' => $movie['description'] ?? 'Chưa có mô tả',
                'duration' => $duration,
                'release_date' => $releaseDate,
                'poster_url' => $posterPath ?? 'posters/default.jpg',
                'banner_url' => $bannerPath ?? 'posters/default.jpg',
                'trailer_url' => $movie['trailer_url'] ?? null,
                'age_rating' => $movie['age_rating'] ?? 'P',
                'country' => $movie['country'] ?? null,
                'rating' => $rating,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Liên kết phim với thể loại
            if (isset($movie['genres']) && is_array($movie['genres'])) {
                foreach ($movie['genres'] as $genreName) {
                    $normalizedGenreName = strtolower(trim($genreName));

                    // Tìm ID thể loại
                    $genreId = $genresMap[$normalizedGenreName] ?? null;

                    // Liên kết phim với thể loại
                    if ($genreId) {
                        DB::table('movie_genres')->insert([
                            'movie_id' => $movieId,
                            'genre_id' => $genreId,
                        ]);
                    }
                }
            }
        }

        Log::info('Đã import ' . count($movies) . ' phim từ file JSON');
    }
    private function downloadImageIfNotExists($url, $folder)
    {
        $filename = basename($url);
        $localPath = public_path("storage/$folder/$filename");

        // Kiểm tra nếu file đã tồn tại
        if (File::exists($localPath)) {
            return "storage/$folder/$filename";
        }

        try {
            // Tải ảnh về
            $imageContent = file_get_contents($url);
            File::ensureDirectoryExists(public_path("storage/$folder"));
            File::put($localPath, $imageContent);

            return "storage/$folder/$filename";
        } catch (\Exception $e) {
            Log::error("Không thể tải ảnh từ URL: $url - Lỗi: " . $e->getMessage());
            return null;
        }
    }
}
