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
        'slug',
        'description',
        'duration',
        'release_date',
        'banner_url',
        'poster_url',
        'trailer_url',
        'age_rating'
    ];

    public function genres(): BelongsToMany
    {
        return $this->belongsToMany(Genre::class, 'movie_genres');
    }
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
    public function getIsNowShowingAttribute(): bool
    {
        $hasFutureShowtimes = $this->showtimes()->where('start_time', '>=', now())->exists();

        return $hasFutureShowtimes;
    }
    public function getDurationLabelAttribute()
    {
        return $this->duration . ' phút';
    }
    public function getReleaseDateLabelAttribute()
    {
        return Carbon::parse($this->release_date)->format('d/m/Y');
    }
    public function getPosterUrlLabelAttribute(): ?string
    {
        return $this->poster_url ? asset($this->poster_url) : null;
    }
    public function getBannerUrlLabelAttribute(): ?string
    {
        return $this->banner_url ? asset($this->banner_url) : null;
    }
    protected function genresList(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->genres->pluck('name')->isEmpty()
            ? "Chưa có thể loại"
            : $this->genres->pluck('name')->implode(', '),
            set: fn($value) => $value
        );
    }
}
