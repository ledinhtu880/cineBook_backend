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
        $maxShowtimes = 2000;

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
                // Chọn một số phim cho rạp này hôm nay (60-80% tổng số phim)
                shuffle($movies);
                $moviesForCinema = array_slice($movies, 0, ceil(count($movies) * (rand(60, 80) / 100)));

                // Tạo lịch chiếu cho mỗi phim
                foreach ($moviesForCinema as $movie) {
                    if ($showtimeCount >= $maxShowtimes)
                        break;

                    // Kiểm tra ngày phát hành của phim
                    $releaseDate = $movie->release_date
                        ? Carbon::parse($movie->release_date)->startOfDay()
                        : null;

                    // Nếu phim chưa phát hành, bỏ qua
                    if ($releaseDate && $releaseDate->gt($currentDate)) {
                        continue;
                    }

                    // Số suất chiếu cho phim này trong ngày (1-4 suất tùy theo độ hot)
                    $numberOfShowtimes = rand(1, 4);

                    // Tạo khung giờ linh động cho phim
                    $movieTimeSlots = $this->generateTimeSlots($currentDate, $movie->duration, $numberOfShowtimes);

                    // Duyệt qua các khung giờ của phim
                    foreach ($movieTimeSlots as $timeSlot) {
                        if ($showtimeCount >= $maxShowtimes)
                            break;

                        // Tìm phòng trống cho suất chiếu này
                        $availableRoom = $this->findAvailableRoom($cinemaRooms, $timeSlot, $movie->duration);

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
                }
            }
        }
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