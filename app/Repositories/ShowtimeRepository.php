<?php

namespace App\Repositories;

use App\Models\Showtime;

class ShowtimeRepository
{
    protected $model;

    public function __construct(Showtime $model)
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
        $showtime = $this->model->findOrFail($id);
        $showtime->update($data);

        return $showtime;
    }

    public function delete($id)
    {
        return $this->model->destroy($id);
    }
}
