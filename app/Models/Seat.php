<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Seat extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'room_id',
        'seat_code',
        'seat_type',
        'is_sweetbox'
    ];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}
