<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Casts\Attribute;
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

    protected $appends = [
        'genres',
    ];

    /**
     * Get the genres associated with the movie
     */
    public function genres(): BelongsToMany
    {
        return $this->belongsToMany(Genre::class, 'movie_genres');
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
    protected function duration(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                /* $hours = floor($value / 60);
                $minutes = $value % 60;

                if ($hours > 0 && $minutes > 0) {
                    return "{$hours}h {$minutes}m";
                }

                if ($hours > 0) {
                    return "{$hours}h";
                }

                return "{$minutes}m"; */
                return "{$value} phút";
            },
            set: fn($value) => $value
        );
    }
    protected function genresList(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $this->genres->pluck('name')->implode(', '),
            set: fn($value) => $value
        );
    }
    protected function releaseDate(): Attribute
    {
        return Attribute::make(
            get: fn($value) => Carbon::parse($value)->format('d/m/Y'),
            set: fn($value) => Carbon::parse($value)->format('Y-m-d')
        );
    }
}
