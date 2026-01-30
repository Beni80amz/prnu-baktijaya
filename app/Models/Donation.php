<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    protected $fillable = [
        'transaction_id',
        'campaign_name',
        'donor_name',
        'donor_phone',
        'donor_purpose',
        'amount',
        'payment_method',
        'is_anonymous',
        'payment_proof',
        'status',
        'verified_at',
    ];

    protected $casts = [
        'amount' => 'integer',
        'is_anonymous' => 'boolean',
        'verified_at' => 'datetime',
    ];
}
