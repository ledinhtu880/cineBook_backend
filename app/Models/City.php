<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $fillable = [
        'name',
        'code'
    ];

    public function cinemas()
    {
        return $this->hasMany(Cinema::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
