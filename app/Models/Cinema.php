<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cinema extends Model
{
    protected $fillable = [
        'name',
        'address',
        'phone'
    ];

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }
    public function city()
    {
        return $this->belongsTo(City::class);
    }
}
