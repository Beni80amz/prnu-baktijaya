<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Gallery extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'description',
        'type',
        'images',
        'video_url',
        'event_date',
        'is_featured',
        'is_active',
    ];

    protected $casts = [
        'images' => 'array',
        'event_date' => 'date',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function getImageAttribute()
    {
        // Prioritize manually uploaded image
        if (!empty($this->images)) {
            if (is_array($this->images) && isset($this->images[0])) {
                return $this->images[0];
            }
            if (is_string($this->images)) {
                return $this->images; // Handle case where it might be stored as string
            }
        }

        if (strtolower($this->type) === 'video' && $this->video_url) {
            return $this->getYouTubeThumbnail(trim($this->video_url));
        }

        return null;
    }

    public function getDisplayImageAttribute()
    {
        $image = $this->image;
        if (!$image)
            return null;
        if (str_starts_with($image, 'http'))
            return $image;
        try {
            return Storage::url($image);
        } catch (\Exception $e) {
            return asset('storage/' . $image);
        }
    }

    public function getThumbnailAttribute()
    {
        // Prioritize manually uploaded image
        if (!empty($this->images)) {
            if (is_array($this->images) && isset($this->images[0])) {
                return $this->images[0];
            }
            if (is_string($this->images)) {
                return $this->images;
            }
        }

        if (strtolower($this->type) === 'video' && $this->video_url) {
            return $this->getYouTubeThumbnail(trim($this->video_url));
        }

        return null;
    }

    private function getYouTubeThumbnail($url)
    {
        $pattern = '%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=|live/)|youtu\.be/)([^"&?/ ]{11})%i';
        if (preg_match($pattern, $url, $match)) {
            return 'https://img.youtube.com/vi/' . $match[1] . '/maxresdefault.jpg';
        }
        return null;
    }
}
