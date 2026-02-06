<?php

namespace App\Models;

use App\Models\Role;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Order;

class User extends Authenticatable implements MustVerifyEmail, CanResetPassword
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'bio',
        'profile_image',
        'social_links',
        'address',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'social_links' => 'array',
        'address' => 'array',
    ];

    protected static function booted()
    {
        static::created(function ($user) {
            if ($user->roles()->count() === 0) {
                $user->roles()->attach(Role::where('slug', 'user')->first());
            }
        });
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function hasRole($roleSlug)
    {
        return $this->roles()->where('slug', $roleSlug)->exists();
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    public function getPrimaryRoleAttribute()
    {
        $role = $this->roles()->first();
        return $role ? $role->name : 'None';
    }

    /**
     * ✅ Stable: Always return social_links as array, even if DB has weird JSON strings.
     */
    public function getSocialLinksAttribute($value)
    {
        return $this->normalizeJsonToArray($value);
    }

    /**
     * ✅ Stable: Always return address as array, even if DB has weird JSON strings.
     */
    public function getAddressAttribute($value)
    {
        return $this->normalizeJsonToArray($value);
    }

    /**
     * Convert:
     * - array -> array
     * - json string -> array
     * - double-encoded json string -> array
     * - null/empty -> []
     */
    private function normalizeJsonToArray($value): array
    {
        if (is_array($value)) {
            return $value;
        }

        if (is_null($value) || $value === '') {
            return [];
        }

        if (!is_string($value)) {
            return [];
        }

        // Try decode once
        $decoded = json_decode($value, true);

        // If it's a string again, try decode twice (handles "\"{...}\"")
        if (is_string($decoded)) {
            $decoded = json_decode($decoded, true);
        }

        return is_array($decoded) ? $decoded : [];
    }
}
