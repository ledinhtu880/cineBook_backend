<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    protected $model;

    public function __construct(User $model)
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
    public function getNowShowing()
    {
        return $this->model->nowShowing()->get();
    }

    public function getComingSoon()
    {
        return $this->model->comingSoon()->get();
    }
}
