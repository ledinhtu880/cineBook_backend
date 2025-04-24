<?php

namespace App\Repositories;

use App\Models\ProductCombo;

class ProductComboRepository
{
    protected $model;

    public function __construct(ProductCombo $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model->all();
    }

}
