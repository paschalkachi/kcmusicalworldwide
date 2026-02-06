<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'short_description',
        'description',
        'sale_price',
        'regular_price',
        'SKU',
        'stock_status',
        'featured',
        'quantity',
        'preorder_limit',
        'image',
        'images',
        'category_id',
        'brand_id',
        'shipping_class_id',
        'shipping_unit',
    ];

    protected $casts = [
        'images' => 'array',
    ];

    /**
     * 🔁 Auto-sync stock_status on save
     */
    protected static function booted()
    {
        static::saving(function ($product) {
            $product->stock_status = $product->calculateStockStatus();
        });
    }

    /**
     * 🧠 Stock logic (AND condition)
     */
    public function calculateStockStatus(): string
{
    if ($this->quantity > 0) {
        return 'instock';
    }

    if ($this->preorder_limit > 0) {
        return 'preorder';
    }

    return 'outofstock';
}


    /**
     * 🧩 Optional helper for views
     */
    public function getIsOutOfStockAttribute(): bool
    {
        return $this->stock_status === 'outofstock';
    }

        public function getIsPreorderAttribute(): bool
    {
        return $this->stock_status === 'preorder';
    }

    // Relationships
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function shippingClass()
    {
        return $this->belongsTo(ShippingClass::class);
    }

    /**
     * Get active reservations for this product
     */
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    /**
     * Get total reserved quantity for this product
     */
    public function getReservedQuantityAttribute()
    {
        return $this->reservations()
            ->whereNull('expires_at')
            ->orWhere('expires_at', '>', now())
            ->sum('quantity');
    }

    /**
     * Get available quantity (total minus reserved)
     */
    public function getAvailableQuantityAttribute()
    {
        if ($this->stock_status === 'instock') {
            return max(0, $this->quantity - $this->reserved_quantity);
        }
        
        if ($this->stock_status === 'preorder') {
            return max(0, $this->preorder_limit - $this->reserved_quantity);
        }
        
        return 0;
    }

    /**
     * Reserve stock for an order based on payment method
     */
    public function reserveForOrder($orderId, $quantity, $paymentMethod = 'default')
    {
        // Define reservation times based on payment method
        $reservationTimes = [
            'credit_card' => 15,
            'paypal' => 30,
            'bank_transfer' => 60,
            'default' => 30,
        ];

        $reservationTimeInMinutes = $reservationTimes[$paymentMethod] ?? $reservationTimes['default'];

        // Check if enough stock is available
        if ($this->getAvailableQuantityAttribute() < $quantity) {
            return false;
        }

        Reservation::create([
            'product_id' => $this->id,
            'order_id' => $orderId,
            'quantity' => $quantity,
            'expires_at' => now()->addMinutes($reservationTimeInMinutes),
        ]);

        return true;
    }

    /**
     * Release a reservation
     */
    public function releaseReservation($orderId)
    {
        $this->reservations()
            ->where('order_id', $orderId)
            ->where(function($query) {
                $query->whereNull('expires_at')
                      ->orWhere('expires_at', '>', now());
            })
            ->delete();
    }

    /**
     * Confirm a reservation (reduce actual stock)
     */
    public function confirmReservation($orderId)
    {
        $reservation = $this->reservations()
            ->where('order_id', $orderId)
            ->where(function($query) {
                $query->whereNull('expires_at')
                      ->orWhere('expires_at', '>', now());
            })
            ->first();

        if (!$reservation) {
            return false;
        }

        // Reduce the appropriate stock based on status
        if ($this->stock_status === 'instock') {
            $this->decrement('quantity', $reservation->quantity);
        } elseif ($this->stock_status === 'preorder') {
            $this->decrement('preorder_limit', $reservation->quantity);
        }

        // Delete the reservation
        $reservation->delete();

        return true;
    }

    /**
     * Cancel a reservation (release and restore stock)
     */
    public function cancelReservation($orderId)
    {
        $this->releaseReservation($orderId);
    }
}
