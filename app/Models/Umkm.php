<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Umkm extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_name',
        'slug',
        'owner_name',
        'phone',
        'whatsapp',
        'email',
        'address',
        'category',
        'description',
        'images',
        'featured_image',
        'is_featured',
        'is_active',
    ];

    protected $casts = [
        'images' => 'array',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function getNameAttribute()
    {
        return $this->business_name;
    }

    public function getImageAttribute()
    {
        return $this->featured_image;
    }
}
