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

    public function bookingDetails()
    {
        return $this->hasMany(BookingDetail::class);
    }
    public function bookingProducts()
    {
        return $this->hasMany(BookingProduct::class);
    }
    public function bookingCombos()
    {
        return $this->hasMany(BookingCombo::class);
    }
}
