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

    /**
     * Get the booking combos for this product combo
     */
    public function bookingCombos()
    {
        return $this->hasMany(BookingCombo::class);
    }

    /**
     * Scope to get only active combos
     */
    public function scopeActive(Builder $query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get the image URL
     */
    public function getImageUrlAttribute(): ?string
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }

    /**
     * Get formatted price with currency
     */
    public function getFormattedPriceAttribute(): string
    {
        return number_format($this->price, 0, ',', '.') . ' đ';
    }

    /**
     * Get total sales count
     */
    public function getTotalSalesAttribute(): int
    {
        return $this->bookingCombos()->sum('quantity');
    }
}
