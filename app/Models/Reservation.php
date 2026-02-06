<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reservation extends Model
{
    protected $fillable = [
        'product_id',
        'order_id',
        'quantity',
        'expires_at',
    ];

    protected $dates = [
        'expires_at',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function isExpired()
    {
        return $this->expires_at && $this->expires_at->isPast();
    }
}