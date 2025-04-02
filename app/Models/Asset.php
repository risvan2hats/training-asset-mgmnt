<?php

namespace App\Models;

use App\Scopes\CountryCodeScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    use HasFactory;

    protected $fillable = [
        'serial_no',
        'asset_type',
        'hardware_standard',
        'location_id',
        'asset_value',
        'assigned_to',
        'country_code',
        'status'
    ];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope(new CountryCodeScope);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function histories()
    {
        return $this->hasMany(AssetHistory::class);
    }

    public function getLastMovedDateAttribute()
    {
        $moveHistory = $this->histories()->where('action', 'moved')->latest()->first();
        return $moveHistory ? $moveHistory->created_at : null;
    }
}