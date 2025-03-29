<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Room;
use App\Models\Seat;

class SeatSeeder extends Seeder
{
    public function run()
    {
        $rooms = Room::all();

        foreach ($rooms as $room) {
            $rows = $room->seat_rows;
            $cols = $room->seat_columns;
            $has_sweetbox = $room->sweetbox_rows > 0;

            // Xác định hàng bắt đầu của Sweetbox
            $sweetbox_start_row = $rows - $room->sweetbox_rows + 1;

            // Xác định hàng VIP (40% -> 70% số hàng)
            $vip_start_row = max(2, floor($rows * 0.4));
            $vip_end_row = min($rows - 1, floor($rows * 0.7));

            // Xác định cột giữa
            $mid_col = ($cols % 2 == 0) ? ($cols / 2) : ceil($cols / 2);

            // Định nghĩa chiều rộng VIP
            $vip_width = ($cols % 2 == 0) ? 6 : 5;
            $half_vip = floor($vip_width / 2);

            // Cột bắt đầu và kết thúc của VIP
            $start_col = max(1, $mid_col - $half_vip + ($cols % 2 == 0 ? 1 : 0));
            $end_col = min($cols, $mid_col + $half_vip);

            for ($i = 1; $i <= $rows; $i++) {
                $row_label = chr(64 + $i); // A, B, C, D...

                for ($j = 1; $j <= $cols; $j++) {
                    $seat_code = $row_label . $j;
                    $seat_type = 'normal';
                    $is_sweetbox = false;

                    // Nếu là dãy Sweetbox
                    if ($has_sweetbox && $i >= $sweetbox_start_row) {
                        $seat_type = 'sweetbox';
                        $is_sweetbox = true;
                    }
                    // Nếu là ghế VIP
                    elseif ($i >= $vip_start_row && $i <= $vip_end_row && $j >= $start_col && $j <= $end_col) {
                        $seat_type = 'vip';
                    }

                    Seat::create([
                        'room_id' => $room->id,
                        'seat_code' => $seat_code,
                        'seat_type' => $seat_type,
                        'is_sweetbox' => $is_sweetbox,
                    ]);
                }
            }
        }
    }
}
