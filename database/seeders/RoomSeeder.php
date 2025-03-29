<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Lấy danh sách ID của 9 rạp phim
        $cinemaIds = DB::table('cinemas')->pluck('id');

        foreach ($cinemaIds as $cinemaId) {
            // Số phòng ngẫu nhiên từ 4 đến 6
            $roomCount = rand(4, 6);

            for ($i = 1; $i <= $roomCount; $i++) {
                $seatRows = rand(8, 12); // Số hàng ghế (8 - 12)
                $seatColumns = $seatRows; // Số cột ghế bằng với số hàng
                $sweetboxRows = rand(0, 2); // 0 hoặc 1-2 hàng sweetbox

                DB::table('rooms')->insert([
                    'cinema_id' => $cinemaId,
                    'name' => "Phòng $i",
                    'seat_rows' => $seatRows,
                    'seat_columns' => $seatColumns,
                    'sweetbox_rows' => $sweetboxRows,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
