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
        $movies = DB::table('movies')->get()->toArray();
        $today = Carbon::now()->startOfDay();
        $showtimeCount = 0;
        $maxShowtimes = 5000;

        // Tạo lịch chiếu cho 7 ngày tới
        for ($day = 0; $day < 7; $day++) {
            $currentDate = $today->copy()->addDays($day);

            $cinemaRooms = [];
            foreach ($rooms as $room) {
                if (!isset($cinemaRooms[$room->cinema_id])) {
                    $cinemaRooms[$room->cinema_id] = [];
                }
                $cinemaRooms[$room->cinema_id][] = $room;
            }

            foreach ($cinemaRooms as $cinemaId => $roomsInCinema) {
                $availableMovies = array_filter($movies, function ($movie) use ($currentDate) {
                    $releaseDate = $movie->release_date
                        ? Carbon::parse($movie->release_date)->startOfDay()
                        : null;

                    return !$releaseDate || !$releaseDate->gt($currentDate);
                });

                foreach ($availableMovies as $movie) {
                    if ($showtimeCount >= $maxShowtimes)
                        break;

                    $timeSlot = $this->generatePrimeTimeSlot($currentDate, $movie->duration);

                    $availableRoom = $this->findAvailableRoom($roomsInCinema, $timeSlot, $movie->duration);

                    if (!$availableRoom) {
                        for ($attempt = 0; $attempt < 5; $attempt++) {
                            $timeSlot = $this->generateRandomTimeSlot($currentDate, $movie->duration);
                            $availableRoom = $this->findAvailableRoom($roomsInCinema, $timeSlot, $movie->duration);
                            if ($availableRoom)
                                break;
                        }
                    }

                    if ($availableRoom) {
                        $endTime = $timeSlot->copy()->addMinutes($movie->duration);

                        DB::table('showtimes')->insert([
                            'movie_id' => $movie->id,
                            'cinema_id' => $cinemaId,
                            'room_id' => $availableRoom->id,
                            'start_time' => $timeSlot,
                            'end_time' => $endTime,
                        ]);

                        $showtimeCount++;
                    }
                }

                if ($showtimeCount < $maxShowtimes) {
                    shuffle($availableMovies);
                    $moviesForExtraShowtimes = array_slice($availableMovies, 0, ceil(count($availableMovies) * (rand(60, 80) / 100)));

                    foreach ($moviesForExtraShowtimes as $movie) {
                        if ($showtimeCount >= $maxShowtimes)
                            break;

                        $additionalShowtimes = rand(0, 3);

                        if ($additionalShowtimes > 0) {
                            $extraTimeSlots = $this->generateTimeSlots($currentDate, $movie->duration, $additionalShowtimes);

                            foreach ($extraTimeSlots as $timeSlot) {
                                if ($showtimeCount >= $maxShowtimes)
                                    break;

                                $availableRoom = $this->findAvailableRoom($roomsInCinema, $timeSlot, $movie->duration);

                                if ($availableRoom) {
                                    $endTime = $timeSlot->copy()->addMinutes($movie->duration);

                                    DB::table('showtimes')->insert([
                                        'movie_id' => $movie->id,
                                        'cinema_id' => $cinemaId,
                                        'room_id' => $availableRoom->id,
                                        'start_time' => $timeSlot,
                                        'end_time' => $endTime,
                                    ]);

                                    $showtimeCount++;
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    private function generatePrimeTimeSlot($date, $duration)
    {
        $hour = rand(18, 20);
        $minute = [0, 15, 30, 45][rand(0, 3)];

        return $date->copy()->setTime($hour, $minute);
    }

    private function generateRandomTimeSlot($date, $duration)
    {
        $operatingHours = [
            'start' => 9,
            'end' => 22,
        ];

        $availableMinutes = ($operatingHours['end'] - $operatingHours['start']) * 60;
        $randomMinutes = rand(0, $availableMinutes);

        $hour = $operatingHours['start'] + floor($randomMinutes / 60);
        $minute = $randomMinutes % 60;

        $minute = round($minute / 15) * 15;
        if ($minute == 60) {
            $hour++;
            $minute = 0;
        }

        return $date->copy()->setTime($hour, $minute);
    }

    private function generateTimeSlots($date, $duration, $count)
    {
        $slots = [];
        $operatingHours = [
            'start' => 9,
            'end' => 23,
        ];

        $movieBlockTime = $duration + 30;

        $totalHours = $operatingHours['end'] - $operatingHours['start'];

        $availableBlocks = floor(($totalHours * 60) / $movieBlockTime);

        if ($availableBlocks < $count) {
            $count = $availableBlocks;
        }

        $step = floor($availableBlocks / $count);

        for ($i = 0; $i < $count; $i++) {
            $blockIndex = $i * $step + rand(0, max(1, $step - 1));
            $minutesFromStart = $blockIndex * $movieBlockTime;

            $hour = $operatingHours['start'] + floor($minutesFromStart / 60);
            $minute = $minutesFromStart % 60;

            $minute = round($minute / 15) * 15;
            if ($minute == 60) {
                $hour++;
                $minute = 0;
            }

            if ($hour < $operatingHours['end']) {
                $timeSlot = $date->copy()->setTime($hour, $minute);
                $slots[] = $timeSlot;
            }
        }

        usort($slots, function ($a, $b) {
            return $a->timestamp - $b->timestamp;
        });

        return $slots;
    }

    /**
     * Tìm phòng trống cho suất chiếu
     */
    private function findAvailableRoom($rooms, $startTime, $duration)
    {
        $endTime = $startTime->copy()->addMinutes($duration);

        // Trộn ngẫu nhiên phòng để không ưu tiên phòng nào
        shuffle($rooms);

        foreach ($rooms as $room) {
            $existingShowtimes = $this->getExistingShowtimes($room->id, $startTime->copy()->startOfDay());

            $isAvailable = true;
            foreach ($existingShowtimes as $existing) {
                $existingStart = $existing['start_time'];
                $existingEnd = $existing['end_time'];

                // Thêm 30 phút cho việc dọn dẹp trước và sau suất chiếu
                $bufferStart = $startTime->copy()->subMinutes(15);
                $bufferEnd = $endTime->copy()->addMinutes(15);

                if (
                    ($bufferStart <= $existingEnd && $bufferEnd >= $existingStart)
                ) {
                    $isAvailable = false;
                    break;
                }
            }

            if ($isAvailable) {
                return $room;
            }
        }

        return null;
    }

    private function getExistingShowtimes($roomId, $date)
    {
        $dateStart = $date->copy()->startOfDay();
        $dateEnd = $date->copy()->addDay()->startOfDay();

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
}