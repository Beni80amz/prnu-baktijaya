<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Mosque;
use Illuminate\Http\Request;

class MosqueController extends Controller
{
    public function index()
    {
        $mosques = Mosque::where('is_active', true)->get();

        return response()->json([
            'success' => true,
            'data' => $mosques
        ]);
    }
}
