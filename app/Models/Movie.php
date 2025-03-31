<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Movie extends Model
{
    protected $fillable = [
        'title',
        'description',
        'duration',
        'release_date',
        'poster_url',
        'trailer_url',
        'age_rating'
    ];

    protected $casts = [
        'release_date' => 'date',
        'duration' => 'integer'
    ];

    /**
     * Get the genres associated with the movie
     */
    public function genres()
    {
        return $this->belongsToMany(Genre::class, 'movie_genre')
            ->using(MovieGenre::class);
    }

    /**
     * Get all showtimes for this movie
     */
    public function showtimes()
    {
        return $this->hasMany(Showtime::class);
    }
    public function scopeNowShowing($query)
    {
        return $query->where('release_date', '<=', now())
            ->whereHas('showtimes', function ($query) {
                $query->where('start_time', '>=', now());
            });
    }
    public function scopeComingSoon($query)
    {
        return $query->where('release_date', '>', now());
    }

    /**
     * Get formatted duration (e.g., "2h 30m")
     */
    public function getFormattedDurationAttribute(): string
    {
        $hours = floor($this->duration / 60);
        $minutes = $this->duration % 60;

        if ($hours > 0 && $minutes > 0) {
            return "{$hours}h {$minutes}m";
        }

        if ($hours > 0) {
            return "{$hours}h";
        }

        return "{$minutes}m";
    }
    public function getFormattedReleaseDateAttribute()
    {
        return Carbon::parse($this->release_date)->format('d/m/Y');
    }
}
