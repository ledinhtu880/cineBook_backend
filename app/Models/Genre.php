<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Model;

class Genre extends Model
{
    protected $fillable = [
        'name'
    ];
    public function movies(): BelongsToMany
    {
        return $this->belongsToMany(Movie::class, 'movie_genres');
    }
}
