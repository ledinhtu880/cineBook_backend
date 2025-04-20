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

    public function getWithCinemas()
    {
        return $this->model->with('cinemas')->get();
    }
}
