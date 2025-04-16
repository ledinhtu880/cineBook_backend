<?php

namespace App\Repositories;

use App\Models\Room;

class RoomRepository
{
    protected $model;
    public function __construct(Room $model)
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
    public function delete($id)
    {
        return $this->model->destroy($id);
    }
}
