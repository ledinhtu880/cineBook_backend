<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Genre;

class GenreRepository
{
    protected $genre;

    public function __construct(Genre $genre)
    {
        $this->genre = $genre;
    }
    public function all()
    {
        return $this->genre->all();
    }
}
