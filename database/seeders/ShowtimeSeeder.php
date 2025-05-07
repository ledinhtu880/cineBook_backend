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

            // Nhóm các phòng theo rạp
            $cinemaRooms = [];
            foreach ($rooms as $room) {
                if (!isset($cinemaRooms[$room->cinema_id])) {
                    $cinemaRooms[$room->cinema_id] = [];
                }
                $cinemaRooms[$room->cinema_id][] = $room;
            }

            // Xử lý từng rạp
            foreach ($cinemaRooms as $cinemaId => $cinemaRooms) {
                // Lọc phim hợp lệ (đã phát hành)
                $availableMovies = array_filter($movies, function ($movie) use ($currentDate) {
                    $releaseDate = $movie->release_date
                        ? Carbon::parse($movie->release_date)->startOfDay()
                        : null;

                    return !$releaseDate || !$releaseDate->gt($currentDate);
                });

                // Đảm bảo mỗi bộ phim đều có ít nhất một suất chiếu trong ngày
                foreach ($availableMovies as $movie) {
                    if ($showtimeCount >= $maxShowtimes)
                        break;

                    // Tạo một suất chiếu cố định cho phim này
                    $timeSlot = $this->generatePrimeTimeSlot($currentDate, $movie->duration);

                    // Tìm phòng trống cho suất chiếu này
                    $availableRoom = $this->findAvailableRoom($cinemaRooms, $timeSlot, $movie->duration);

                    // Nếu không tìm được phòng trống trong giờ đẹp, thử giờ khác
                    if (!$availableRoom) {
                        // Thử tối đa 5 khung giờ khác nhau
                        for ($attempt = 0; $attempt < 5; $attempt++) {
                            $timeSlot = $this->generateRandomTimeSlot($currentDate, $movie->duration);
                            $availableRoom = $this->findAvailableRoom($cinemaRooms, $timeSlot, $movie->duration);
                            if ($availableRoom)
                                break;
                        }
                    }

                    if ($availableRoom) {
                        // Tính thời gian kết thúc
                        $endTime = $timeSlot->copy()->addMinutes($movie->duration);

                        // Thêm suất chiếu
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

                // Thêm các suất chiếu ngẫu nhiên nếu còn dưới giới hạn
                if ($showtimeCount < $maxShowtimes) {
                    // Chọn ngẫu nhiên các phim cho các suất bổ sung (60-80% tổng số phim)
                    shuffle($availableMovies);
                    $moviesForExtraShowtimes = array_slice($availableMovies, 0, ceil(count($availableMovies) * (rand(60, 80) / 100)));

                    // Tạo thêm suất chiếu cho các phim được chọn
                    foreach ($moviesForExtraShowtimes as $movie) {
                        if ($showtimeCount >= $maxShowtimes)
                            break;

                        // Số suất chiếu bổ sung cho phim này (0-3 suất)
                        $additionalShowtimes = rand(0, 3);

                        if ($additionalShowtimes > 0) {
                            // Tạo các khung giờ bổ sung
                            $extraTimeSlots = $this->generateTimeSlots($currentDate, $movie->duration, $additionalShowtimes);

                            foreach ($extraTimeSlots as $timeSlot) {
                                if ($showtimeCount >= $maxShowtimes)
                                    break;

                                $availableRoom = $this->findAvailableRoom($cinemaRooms, $timeSlot, $movie->duration);

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

    /**
     * Tạo khung giờ đẹp cho suất chiếu (prime time - buổi tối)
     */
    private function generatePrimeTimeSlot($date, $duration)
    {
        // Prime time thường là 18:00 - 21:00
        $hour = rand(18, 20);
        $minute = [0, 15, 30, 45][rand(0, 3)];

        return $date->copy()->setTime($hour, $minute);
    }

    /**
     * Tạo một khung giờ ngẫu nhiên trong ngày
     */
    private function generateRandomTimeSlot($date, $duration)
    {
        $operatingHours = [
            'start' => 9,  // 9:00 AM
            'end' => 22,   // 10:00 PM (để đủ thời gian cho phim kết thúc trước 23:00)
        ];

        $availableMinutes = ($operatingHours['end'] - $operatingHours['start']) * 60;
        $randomMinutes = rand(0, $availableMinutes);

        $hour = $operatingHours['start'] + floor($randomMinutes / 60);
        $minute = $randomMinutes % 60;

        // Làm tròn phút cho dễ nhìn (0, 15, 30, 45)
        $minute = round($minute / 15) * 15;
        if ($minute == 60) {
            $hour++;
            $minute = 0;
        }

        return $date->copy()->setTime($hour, $minute);
    }

    /**
     * Tạo khung giờ chiếu linh động cho phim
     */
    private function generateTimeSlots($date, $duration, $count)
    {
        $slots = [];
        $operatingHours = [
            'start' => 9, // 9:00 AM
            'end' => 23,  // 11:00 PM
        ];

        // Thời gian cần thiết giữa các suất chiếu (thời lượng phim + 30 phút dọn dẹp)
        $movieBlockTime = $duration + 30;

        // Tổng số giờ hoạt động trong ngày
        $totalHours = $operatingHours['end'] - $operatingHours['start'];

        // Chia khung giờ hoạt động thành các khung thời gian
        $availableBlocks = floor(($totalHours * 60) / $movieBlockTime);

        if ($availableBlocks < $count) {
            $count = $availableBlocks;
        }

        // Tìm các khoảng cách đều nhau cho các suất chiếu
        $step = floor($availableBlocks / $count);

        // Tạo ra các khung giờ với khoảng cách đều nhau nhưng có thêm một chút ngẫu nhiên
        for ($i = 0; $i < $count; $i++) {
            $blockIndex = $i * $step + rand(0, max(1, $step - 1));
            $minutesFromStart = $blockIndex * $movieBlockTime;

            $hour = $operatingHours['start'] + floor($minutesFromStart / 60);
            $minute = $minutesFromStart % 60;

            // Làm tròn phút cho dễ nhìn (0, 15, 30, 45)
            $minute = round($minute / 15) * 15;
            if ($minute == 60) {
                $hour++;
                $minute = 0;
            }

            // Kiểm tra giờ có còn trong khung giờ hoạt động
            if ($hour < $operatingHours['end']) {
                $timeSlot = $date->copy()->setTime($hour, $minute);
                $slots[] = $timeSlot;
            }
        }

        // Sắp xếp các khung giờ
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