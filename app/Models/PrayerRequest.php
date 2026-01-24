<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrayerRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'requester_name',
        'requester_phone',
        'requester_email',
        'type',
        'names',
        'notes',
        'requested_date',
        'status',
    ];

    protected $casts = [
        'names' => 'array',
        'requested_date' => 'date',
    ];
}
