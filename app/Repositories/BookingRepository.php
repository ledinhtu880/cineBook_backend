<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\BookingDetail;
use App\Models\BookingCombo;
use App\Models\Booking;

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
    public function find(string $id)
    {
        return $this->booking->with(
            'user:id,first_name,last_name,email',
            'showtime:id,movie_id,cinema_id,start_time',
            'showtime.movie:id,title',
            'showtime.cinema:id,name',
            'bookingDetails',
            'bookingCombos',
            'bookingCombos.combo'
        )->findOrFail($id);
    }
    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {

            $booking = $this->booking->create([
                'code' => $this->generateBookingCode(),
                'user_id' => $data['user_id'],
                'showtime_id' => $data['showtime_id'],
                'total_price' => $data['total_amount'],
                'payment_method' => $data['payment_method'],
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
                        'product_combo_id' => $combo['id'],
                        'quantity' => $combo['quantity'],
                        'price' => $combo['price']
                    ]);
                }
            }

            return $booking;
        });
    }
    public function update(string $id)
    {
        return DB::transaction(function () use ($id) {
            $booking = $this->find($id);
            $booking->update([
                'payment_status' => 'paid',
            ]);
            return $booking;
        });
    }
    protected function generateBookingCode()
    {
        $date = now()->format('ymd');
        $randomString = strtoupper(Str::random(5));

        return "BK-{$date}-{$randomString}";
    }
}
