<?php

namespace App\Repositories;

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
        $Movie = $this->model->findOrFail($id);
        $Movie->update($data);

        return $Movie;
    }

    public function delete($id)
    {
        return $this->model->destroy($id);
    }
}
