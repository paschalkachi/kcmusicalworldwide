<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Order;

class Transaction extends Model
{
    use HasFactory;

    protected $table = 'transactions';

    protected $fillable = [
        'user_id',
        'order_id',
        'payment_method',
        'reference',
        'status',
        'amount',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
