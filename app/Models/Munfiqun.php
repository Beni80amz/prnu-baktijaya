<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Volunteer; // Added this line

class Munfiqun extends Model
{
    protected $fillable = ['name', 'code', 'volunteer_id'];

    public function volunteer()
    {
        return $this->belongsTo(Volunteer::class);
    }

    protected static function booted()
    {
        static::creating(function ($munfiqun) {
            if (empty($munfiqun->code)) {
                $volunteer = $munfiqun->volunteer;
                if ($volunteer && $volunteer->region) {
                    $regionCode = $volunteer->region->code;

                    // Get latest sequence for this region
                    $lastMunfiqun = static::whereHas('volunteer.region', function ($query) use ($regionCode) {
                        $query->where('code', $regionCode);
                    })->where('code', 'like', $regionCode . '0%')->orderByRaw('CAST(SUBSTRING(code, LENGTH(?) + 2) AS UNSIGNED) DESC', [$regionCode])->first();

                    $sequence = 1;
                    if ($lastMunfiqun) {
                        // Extract sequence from code (RegionCode + '0' + Sequence)
                        // e.g. 21001 -> Region 21, Separator 0, Seq 01
                        // Length of region code + 1 (for '0')
                        $prefixLength = strlen($regionCode) + 1;
                        $sequence = (int) substr($lastMunfiqun->code, $prefixLength) + 1;
                    }

                    $munfiqun->code = $regionCode . '0' . str_pad($sequence, 2, '0', STR_PAD_LEFT);
                }
            }
        });
    }
}
