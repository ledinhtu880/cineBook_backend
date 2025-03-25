<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'user_id',
        'show_time_id',
        'total_price',
        'payment_status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function showTime()
    {
        return $this->belongsTo(ShowTime::class);
    }

    public function bookingSeats()
    {
        return $this->hasMany(BookingDetail::class);
    }
}
