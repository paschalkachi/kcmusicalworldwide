<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'order_id',
        'full_name',
        'phone',
        'state_id',
        'lagos_location_id',
        'lga',
        'street',
        'landmark',
        'description',
        'additional_info',
        'is_default'
    ];
    
    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'is_default' => 'boolean',
    ];
    
    /**
     * Relationships
     */

    // Address belongs to a User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Address belongs to an Order
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Optional: if you normalize states later
    public function state()
    {
        return $this->belongsTo(State::class);
    }

    // Optional: Lagos delivery zones
    public function lagosLocation()
    {
        return $this->belongsTo(LagosLocation::class);
    }
    
    /**
     * Scope to get the default address for a user
     */
    public function scopeDefault($query)
    {
        return $query->where('is_default', true)->orderByDesc('updated_at');
    }
    
    /**
     * Scope to get non-default addresses for a user
     */
    public function scopeNonDefault($query)
    {
        return $query->where('is_default', false)->orderByDesc('updated_at');
    }
}