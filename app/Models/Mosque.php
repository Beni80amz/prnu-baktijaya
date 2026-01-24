<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mosque extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'type',
        'address',
        'village',
        'latitude',
        'longitude',
        'takmir_name',
        'phone',
        'description',
        'image',
        'capacity',
        'has_wudu_facility',
        'has_parking',
        'is_active',
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'has_wudu_facility' => 'boolean',
        'has_parking' => 'boolean',
        'is_active' => 'boolean',
    ];
}
