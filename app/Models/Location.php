<?php

namespace App\Models;

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
}