<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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

    /**
     * Scope to get currently showing movies
     */
    public function scopeNowShowing($query)
    {
        return $query->whereHas('showtimes', function ($query) {
            $query->where('start_time', '>=', now());
        });
    }

    /**
     * Scope to get upcoming movies
     */
    public function scopeComingSoon($query)
    {
        return $query->where('release_date', '>', now())
            ->orWhereDoesntHave('showtimes');
    }

    /**
     * Get formatted duration (e.g., "2h 30m")
     */
    public function getFormattedDurationAttribute(): string
    {
        $hours = floor($this->duration / 60);
        $minutes = $this->duration % 60;

        return ($hours > 0 ? $hours . 'h ' : '') . ($minutes > 0 ? $minutes . 'm' : '');
    }
}
