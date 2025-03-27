<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Product extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'image',
        'category'
    ];

    protected $casts = [
        'price' => 'decimal:2'
    ];

    /**
     * The possible category values
     */
    const CATEGORIES = [
        'food' => 'Đồ ăn',
        'beverage' => 'Đồ uống'
    ];

    /**
     * Get the booking products for this pro\duct
     */
    public function bookingProducts()
    {
        return $this->hasMany(BookingProduct::class);
    }

    /**
     * Scope to get only food products
     */
    public function scopeFood(Builder $query): Builder
    {
        return $query->where('category', 'food');
    }

    /**
     * Scope to get only beverage products
     */
    public function scopeBeverage(Builder $query): Builder
    {
        return $query->where('category', 'beverage');
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
     * Get the category name in Vietnamese
     */
    public function getCategoryNameAttribute(): string
    {
        return self::CATEGORIES[$this->category] ?? $this->category;
    }

    /**
     * Get total sales quantity
     */
    public function getTotalSalesAttribute(): int
    {
        return $this->bookingProducts()->sum('quantity');
    }
}
