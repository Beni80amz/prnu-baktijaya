<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TanyaKiai extends Model
{
    use HasFactory;

    protected $fillable = [
        'answered_by',
        'kiai_id',
        'name',
        'email',
        'phone',
        'category',
        'question',
        'answer',
        'status',
        'is_public',
        'answered_at',
    ];

    protected $casts = [
        'is_public' => 'boolean',
        'answered_at' => 'datetime',
    ];

    public function answerer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'answered_by');
    }

    public function kiai(): BelongsTo
    {
        return $this->belongsTo(Kiai::class);
    }
}
