<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class ProductCombo extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'name',
        'description',
        'price',
        'image',
        'is_active'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean'
    ];
    public function bookingCombos()
    {
        return $this->hasMany(BookingCombo::class);
    }

    public function scopeActive(Builder $query)
    {
        return $query->where('is_active', true);
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->image ? asset($this->image) : null;
    }

    public function getFormattedPriceAttribute(): string
    {
        return number_format($this->price, 0, ',', '.') . ' VND';
    }


    public function getTotalSalesAttribute(): int
    {
        return $this->bookingCombos()->sum('quantity');
    }
}
