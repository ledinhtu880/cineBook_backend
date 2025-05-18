<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'code',
        'user_id',
        'showtime_id',
        'total_price',
        'payment_method',
        'payment_status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function showtime()
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

    // Scopes
    public function scopeWithBookingRelations($query)
    {
        return $query->with(
            'user:id,first_name,last_name,email',
            'showtime:id,movie_id,cinema_id,start_time',
            'showtime.movie:id,title,poster_url',
            'showtime.cinema:id,name',
            'bookingDetails',
            'bookingCombos',
            'bookingCombos.combo'
        );
    }

    // Accessors
    public function getTotalPriceFormattedAttribute()
    {
        return number_format($this->total_price, 0, ',', '.') . ' VNĐ';
    }
    public function getSeatsAttribute()
    {
        $seats = $this->bookingDetails->pluck('seat_code')->toArray();
        sort($seats);
        return implode(', ', $seats);
    }
}
