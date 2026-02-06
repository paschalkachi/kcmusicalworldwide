<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class State extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'is_lagos',
        'base_shipping_price',
    ];

    protected $casts = [
        'is_lagos' => 'boolean',
        'base_shipping_price' => 'decimal:2',
    ];

    /**
     * Relationship: Lagos LGAs (only relevant if is_lagos = true)
     */
    public function lagosLocations()
    {
        return $this->hasMany(LagosLocation::class);
    }

    /**
     * Helper: check if state is Lagos
     */
    public function isLagos(): bool
    {
        return $this->is_lagos === true;
    }

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }   
}
