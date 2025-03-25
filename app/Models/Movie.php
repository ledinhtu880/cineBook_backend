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
        'trailer_url'
    ];

    public function showTimes()
    {
        return $this->hasMany(ShowTime::class);
    }
}
