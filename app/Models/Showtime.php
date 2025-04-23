<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Showtime extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'movie_id',
        'cinema_id',
        'room_id',
        'start_time',
        'end_time'
    ];

    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }

    public function cinema()
    {
        return $this->belongsTo(Cinema::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function genres()
    {
        return $this->belongsToMany(Genre::class, 'genre_showtime', 'showtime_id', 'genre_id');
    }
}
