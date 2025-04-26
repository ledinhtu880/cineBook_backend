<?php

namespace App\Repositories;

use App\Models\Booking;
use App\Models\BookingDetail;
use App\Models\BookingCombo;
use Illuminate\Support\Facades\DB;

class BookingRepository
{
    protected $booking;
    protected $bookingDetail;
    protected $bookingCombo;

    public function __construct(Booking $booking, BookingDetail $bookingDetail, BookingCombo $bookingCombo)
    {
        $this->booking = $booking;
        $this->bookingDetail = $bookingDetail;
        $this->bookingCombo = $bookingCombo;
    }
    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            $booking = $this->booking->create([
                'user_id' => $data['user_id'],
                'showtime_id' => $data['showtime_id'],
                'total_price' => $data['total_amount'],
                // 'payment_method' => $data['payment_method'],
                'payment_status' => 'unpaid',
            ]);

            // 2. Lưu thông tin ghế
            if (!empty($data['seats'])) {
                foreach ($data['seats'] as $seat) {
                    $this->bookingDetail->create([
                        'booking_id' => $booking->id,
                        'seat_id' => $seat['id'],
                        'ticket_price' => $seat['price']
                    ]);
                }
            }

            // 3. Lưu thông tin combo nếu có
            if (!empty($data['combos'])) {
                foreach ($data['combos'] as $combo) {
                    $this->bookingCombo->create([
                        'booking_id' => $booking->id,
                        'product_id' => $combo['id'],
                        'quantity' => $combo['quantity'],
                        'price' => $combo['price']
                    ]);
                }
            }

            return $booking;
        });
    }
}
