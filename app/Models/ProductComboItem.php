<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductComboItem extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'product_combo_id',
        'product_id',
        'quantity'
    ];

    protected $casts = [
        'quantity' => 'integer'
    ];

    /**
     * Get the product combo that owns this item
     */
    public function productCombo()
    {
        return $this->belongsTo(ProductCombo::class);
    }

    /**
     * Get the product for this combo item
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the total price for this item (product price * quantity)
     */
    public function getTotalPriceAttribute(): float
    {
        return $this->product->price * $this->quantity;
    }

    /**
     * Get formatted total price with currency
     */
    public function getFormattedTotalPriceAttribute(): string
    {
        return number_format($this->total_price, 0, ',', '.') . ' đ';
    }
}
