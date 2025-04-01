<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'employee_id',
        'hire_date',
        'position',
        'country_code',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'hire_date' => 'date',
    ];

    /**
     * Check if user is a super admin
     */
    public function isSuperAdmin(): bool
    {
        return (bool) $this->is_admin;
    }

    /**
     * Get full name attribute
     */
    public function getNameAttribute(): string
    {
        return $this->first_name.' '.$this->last_name;
    }

    // Relationship with assets
    public function assets()
    {
        return $this->hasMany(Asset::class, 'assigned_to');
    }

    // Relationship with asset histories
    public function assetHistories()
    {
        return $this->hasMany(AssetHistory::class);
    }
}