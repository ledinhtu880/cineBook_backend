<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

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

    public function getStartTimeFormattedAttribute()
    {
        return Carbon::parse($this->start_time)->format('H:i');
    }
    public function getEndTimeFormattedAttribute()
    {
        return Carbon::parse($this->end_time)->format('H:i');
    }
    public function getDateAttribute()
    {
        return Carbon::parse($this->start_time)->format('d/m/Y');
    }
}
