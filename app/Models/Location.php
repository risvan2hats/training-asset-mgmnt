<?php

namespace App\Models;

use App\Scopes\CountryCodeScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'floor_number',
        'country_code',
    ];

    public function assets()
    {
        return $this->hasMany(Asset::class);
    }

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope(new CountryCodeScope);
    }
}