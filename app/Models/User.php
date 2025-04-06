<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'role'
    ];
    protected $hidden = [
        'password',
        'remember_token',
    ];
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'role' => \App\Casts\RoleCast::class,
        ];
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->last_name . ' ' . $this->first_name
        );
    }

    protected function formattedRole(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->role === 1 ? 'Quản trị viên' : 'Người dùng'
        );
    }
}
