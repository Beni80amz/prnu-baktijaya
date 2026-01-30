<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Region;

class Volunteer extends Model
{
    protected $fillable = ['name', 'region_id', 'photo'];

    public function region()
    {
        return $this->belongsTo(Region::class);
    }
}
