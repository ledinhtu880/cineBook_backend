<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingCombo extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'booking_id',
        'product_combo_id',
        'quantity',
        'price'
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function combo()
    {
        return $this->belongsTo(ProductCombo::class);
    }
}
