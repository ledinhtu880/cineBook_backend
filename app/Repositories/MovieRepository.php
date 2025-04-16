<?php

namespace App\Repositories;

use Illuminate\Support\Str;
use App\Models\Movie;

class MovieRepository
{
    protected $model;

    public function __construct(Movie $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model->all();
    }

    public function find($id)
    {
        return $this->model->findOrFail($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $movie = $this->model->findOrFail($id);
        $movie->update($data);

        return $movie;
    }

    public function delete($id)
    {
        return $this->model->destroy($id);
    }
    public function getNowShowing()
    {
        return $this->model->nowShowing()->get();
    }

    public function getComingSoon()
    {
        return $this->model->comingSoon()->get();
    }
    public function findBySlug(string $slug)
    {
        return Movie::where('slug', $slug)->firstOrFail();
    }
}
