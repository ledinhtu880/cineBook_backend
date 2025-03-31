<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ShowtimeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $movies = DB::table('movies')->get();
        $rooms = DB::table('rooms')->get();

        foreach ($rooms as $room) {
            $showtimes = [];

            for ($i = 0; $i < rand(2, 3); $i++) {
                $movie = $movies->random();

                // Lấy ngày bắt đầu là max giữa ngày hiện tại và release_date
                $startDate = Carbon::parse($movie->release_date)
                    ->startOfDay()
                    ->max(Carbon::now()->startOfDay());

                // Random ngày chiếu trong 30 ngày tiếp theo
                $date = $startDate->copy()->addDays(rand(0, 7));

                $startTime = $this->getAvailableStartTime($showtimes, $date);
                if (!$startTime) continue;

                $endTime = $startTime->copy()->addMinutes($movie->duration);

                // Kiểm tra thời gian kết thúc hợp lệ
                if ($endTime->hour < 23 || ($endTime->hour == 23 && $endTime->minute <= 30)) {
                    $showtimes[] = [
                        'movie_id' => $movie->id,
                        'cinema_id' => $room->cinema_id,
                        'room_id' => $room->id,
                        'start_time' => $startTime,
                        'end_time' => $endTime,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }

            if (!empty($showtimes)) {
                DB::table('showtimes')->insert($showtimes);
            }
        }
    }

    private function getAvailableStartTime($showtimes, $date)
    {
        $earliest = $date->copy()->addHours(9)->addMinutes(30); // 09:30
        $latest = $date->copy()->addHours(23)->addMinutes(30); // 23:30
        $existingTimes = collect($showtimes)->pluck('end_time');

        while ($earliest < $latest) {
            if ($existingTimes->every(fn($time) => $earliest->gte($time))) {
                return $earliest;
            }
            $earliest->addMinutes(10); // Tăng lên bội số của 10
        }
        return null;
    }
}
