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
        'region_id',
        'donor_purpose',
        'amount',
        'payment_method',
        'bank_name',
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

    public function region()
    {
        return $this->belongsTo(Region::class);
    }
}
