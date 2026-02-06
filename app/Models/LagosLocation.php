<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LagosLocation extends Model
{
    use HasFactory;

    protected $table = 'lagos_locations';

    protected $fillable = [
        'name',
        'shipping_price',
    ];

    protected $casts = [
        'shipping_price' => 'decimal:2',
    ];

    /**
     * Optional: relationship back to State (Lagos)
     * Only needed if you later add state_id to lagos_locations
     */
    public function state()
    {
        return $this->belongsTo(State::class);
    }
}
