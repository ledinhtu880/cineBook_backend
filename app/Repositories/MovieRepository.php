<?php

namespace App\Repositories;

use App\Helpers\ApiHelper;
use App\Models\Movie;

class MovieRepository
{
    protected $model;

    public function __construct(Movie $model)
    {
        $this->model = $model;
    }

    public function all(array $params = [])
    {
        $query = $this->model->query();
        $query = ApiHelper::applyFilters($query, $params);

        return $query->get();
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
    public function getNowShowing(array $params = [])
    {
        $query = $this->model->nowShowing();
        $query = ApiHelper::applyFilters($query, $params);

        return $query->get();
    }

    public function getComingSoon(array $params = [])
    {
        $query = $this->model->comingSoon();
        $query = ApiHelper::applyFilters($query, $params);

        return $query->get();
    }
    public function findBySlug(string $slug)
    {
        return $this->model->where('slug', $slug)->firstOrFail();
    }
}
