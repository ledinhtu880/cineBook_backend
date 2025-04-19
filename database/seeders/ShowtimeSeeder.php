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
        $rooms = DB::table('rooms')->get();
        $movies = DB::table('movies')->get();
        $today = Carbon::now()->startOfDay();
        $showtimeCount = 0;
        $maxShowtimes = 2000;

        // First, ensure each movie gets at least one showtime
        foreach ($movies as $movie) {
            if ($showtimeCount >= $maxShowtimes) break;

            $hasShowtime = false;
            $releaseDate = $movie->release_date
                ? Carbon::parse($movie->release_date)->startOfDay()
                : null;
            $startDate = $releaseDate && $releaseDate->gt($today)
                ? $releaseDate
                : $today;

            // Try to schedule this movie in any room
            foreach ($rooms as $room) {
                if ($hasShowtime) break;

                for ($day = 0; $day < 7; $day++) { // Look up to 7 days ahead
                    if ($hasShowtime) break;

                    $currentDate = $startDate->copy()->addDays($day);
                    $existingShowtimes = $this->getExistingShowtimes($room->id, $currentDate);
                    $startTime = $this->getAvailableStartTime($existingShowtimes, $currentDate);

                    if ($startTime) {
                        $endTime = $startTime->copy()->addMinutes($movie->duration);

                        DB::table('showtimes')->insert([
                            'movie_id' => $movie->id,
                            'cinema_id' => $room->cinema_id,
                            'room_id' => $room->id,
                            'start_time' => $startTime,
                            'end_time' => $endTime,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);

                        $showtimeCount++;
                        $hasShowtime = true;
                    }
                }
            }
        }

        // Then continue with the original loop to fill remaining slots
        foreach ($rooms as $room) {
            if ($showtimeCount >= $maxShowtimes) break;

            foreach ($movies as $movie) {
                if ($showtimeCount >= $maxShowtimes) break;

                $releaseDate = $movie->release_date
                    ? Carbon::parse($movie->release_date)->startOfDay()
                    : null;
                $startDate = $releaseDate && $releaseDate->gt($today)
                    ? $releaseDate
                    : $today;

                for ($day = 0; $day < 3; $day++) {
                    if ($showtimeCount >= $maxShowtimes) break;

                    $currentDate = $startDate->copy()->addDays($day);
                    $existingShowtimes = $this->getExistingShowtimes($room->id, $currentDate);
                    $startTime = $this->getAvailableStartTime($existingShowtimes, $currentDate);

                    if (!$startTime) continue;

                    $endTime = $startTime->copy()->addMinutes($movie->duration);

                    if ($startTime->hour < 23 || ($startTime->hour == 23 && $startTime->minute <= 30)) {
                        DB::table('showtimes')->insert([
                            'movie_id' => $movie->id,
                            'cinema_id' => $room->cinema_id,
                            'room_id' => $room->id,
                            'start_time' => $startTime,
                            'end_time' => $endTime,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);

                        $showtimeCount++;
                        $existingShowtimes[] = [
                            'start_time' => $startTime,
                            'end_time' => $endTime
                        ];
                    }
                }
            }
        }
    }

    private function getExistingShowtimes($roomId, $date)
    {
        $dateStart = $date->copy();
        $dateEnd = $date->copy()->addDay();

        $showtimes = DB::table('showtimes')
            ->where('room_id', $roomId)
            ->where('start_time', '>=', $dateStart)
            ->where('start_time', '<', $dateEnd)
            ->select('start_time', 'end_time')
            ->get()
            ->map(function ($item) {
                return [
                    'start_time' => Carbon::parse($item->start_time),
                    'end_time' => Carbon::parse($item->end_time)
                ];
            })
            ->toArray();

        return $showtimes;
    }

    private function getAvailableStartTime($existingShowtimes, $date)
    {
        $timeSlots = [
            $date->copy()->setTime(9, 30),   // 09:30
            $date->copy()->setTime(12, 0),   // 12:00
            $date->copy()->setTime(14, 30),  // 14:30
            $date->copy()->setTime(17, 0),   // 17:00
            $date->copy()->setTime(19, 30),  // 19:30
            $date->copy()->setTime(21, 0),   // 21:00
            $date->copy()->setTime(23, 0),   // 21:00
        ];

        // Kiểm tra từng khung giờ xem có khả dụng không
        foreach ($timeSlots as $slot) {
            $isAvailable = true;

            foreach ($existingShowtimes as $existing) {
                $existingStart = $existing['start_time'];
                $existingEnd = $existing['end_time'];
                $slotEnd = $slot->copy()->addMinutes(120);

                if (
                    ($slot >= $existingStart && $slot < $existingEnd) ||
                    ($slotEnd > $existingStart && $slotEnd <= $existingEnd) ||
                    ($slot <= $existingStart && $slotEnd >= $existingEnd)
                ) {
                    $isAvailable = false;
                    break;
                }
            }

            if ($isAvailable) {
                return $slot;
            }
        }

        return null;
    }
}
