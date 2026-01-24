<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dawuh extends Model
{
    use HasFactory;

    protected $fillable = [
        'quote',
        'quote_arabic',
        'ulama_name',
        'ulama_title',
        'source',
        'display_date',
        'is_active',
    ];

    protected $casts = [
        'display_date' => 'date',
        'is_active' => 'boolean',
    ];
}
