<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ZakatController extends Controller
{
    public function config()
    {
        // Return constants needed for calculation on client side
        return response()->json([
            'success' => true,
            'data' => [
                'gold_price' => 1200000, // Hardcoded for now, ideal to fetch realtime
                'silver_price' => 12000,
                'nishob_gold' => 85, // grams
                'nishob_silver' => 595, // grams
                'nishob_agriculture' => 653, // kg
                'rate_gold' => 0.025, // 2.5%
                'rate_profesi' => 0.025, // 2.5%
            ]
        ]);
    }
}
