<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cinema extends Model
{
    protected $fillable = [
        'name',
        'address',
        'city_id',
        'opening_hours',
        'slug',
        'phone'
    ];

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }
    public function city()
    {
        return $this->belongsTo(City::class);
    }
    public function seatPrices()
    {
        return $this->hasMany(SeatPrice::class);
    }
}
