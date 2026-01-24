<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Agenda extends Model
{
    protected $fillable = [
        'title',
        'date',
        'time',
        'location',
        'description',
    ];

    protected $casts = [
        'date' => 'date',
    ];
    //
}
