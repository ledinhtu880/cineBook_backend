<?php

namespace App\Repositories;

use App\Models\City;

class CityRepository
{
    protected $model;

    public function __construct(City $model)
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
        $city = $this->model->findOrFail($id);
        $city->update($data);

        return $city;
    }

    public function delete($id)
    {
        return $this->model->destroy($id);
    }
}
