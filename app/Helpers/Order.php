<?php
// Order.php
namespace App\Helpers;

class Order
{
    public function getFormattedOrderIdAttribute()
    {
        return 'KMW-ORD-' . $this->created_at->format('Y') . '-' . str_pad($this->id, 6, '0', STR_PAD_LEFT);
    }
}
