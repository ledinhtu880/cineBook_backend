<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MovieGenre extends Model
{
    protected $table = 'movie_genre';

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'movie_id',
        'genre_id'
    ];

    /**
     * Get the movie that owns the genre.
     */
    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }

    /**
     * Get the genre that belongs to the movie.
     */
    public function genre()
    {
        return $this->belongsTo(Genre::class);
    }
}
