<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'image',
        'description',
        'target_amount',
        'order',
        'is_active',
    ];

    protected $casts = [
        'target_amount' => 'decimal:2',
        'is_active' => 'boolean',
    ];
}
