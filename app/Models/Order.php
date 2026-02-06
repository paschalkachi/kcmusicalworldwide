<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'address_id',
        'status',
        'total_amount',
        'shipping_cost',
        'tax_amount',
        'discount_amount',
        'payment_method',
        'transaction_id',
        'notes',
        'state_id',
        'lagos_location_id',
        'subtotal',
        'tax',
        'shipping_price',
        'total',
        'shipping_class_id',
        'total_units',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function address()
    {
        return $this->hasOne(Address::class, 'order_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function transaction()
    {
        return $this->hasOne(Transaction::class);
    }
    
    /**
     * Scope to get pending orders
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
    
    /**
     * Scope to get paid orders
     */
    public function scopePaid($query)
    {
        return $query->whereIn('status', ['paid', 'confirmed', 'processing', 'shipped', 'delivered']);
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }
    
    /**
     * Scope to get cancelled orders
     */
    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }
}