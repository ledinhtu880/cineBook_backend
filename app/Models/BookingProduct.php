<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingProduct extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'booking_id',
        'product_id',
        'quantity',
        'price'
    ];

    protected $casts = [
        'quantity' => 'integer',
        'price' => 'decimal:2'
    ];

    /**
     * Get the booking that owns this product
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Get the product
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the subtotal for this booking product
     */
    public function getSubtotalAttribute(): float
    {
        return $this->price * $this->quantity;
    }

    /**
     * Get formatted price
     */
    public function getFormattedPriceAttribute(): string
    {
        return number_format($this->price, 0, ',', '.') . ' đ';
    }

    /**
     * Get formatted subtotal
     */
    public function getFormattedSubtotalAttribute(): string
    {
        return number_format($this->subtotal, 0, ',', '.') . ' đ';
    }
}
