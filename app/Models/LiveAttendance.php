<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LiveAttendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'message',
    ];
}
