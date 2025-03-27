<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $fillable = [
        'name',
        'code'
    ];

    /**
     * Get all movies in this city
     */
    public function movies()
    {
        return $this->hasMany(Movie::class);
    }

    /**
     * Get active movies count
     */
    public function getActiveMoviesCountAttribute(): int
    {
        return $this->movies()->where('is_active', true)->count();
    }

    /**
     * Scope to get cities that have active movies
     */
    public function scopeHasActiveMovies($query)
    {
        return $query->whereHas('movies', function ($query) {
            $query->where('is_active', true);
        });
    }

    /**
     * Get current showtimes count in this city
     */
    public function getCurrentShowtimesCountAttribute(): int
    {
        return $this->movies()
            ->whereHas('showtimes', function ($query) {
                $query->where('start_time', '>=', now());
            })
            ->count();
    }
}
