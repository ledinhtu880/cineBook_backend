<?php

namespace App\Repositories;

use App\Models\Cinema;

class CinemaRepository
{
    protected $model;

    public function __construct(Cinema $model)
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
        $Cinema = $this->model->findOrFail($id);
        $Cinema->update($data);

        return $Cinema;
    }

    public function delete($id)
    {
        return $this->model->destroy($id);
    }
}
