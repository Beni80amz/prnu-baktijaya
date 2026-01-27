<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\GeminiApiService;
use Illuminate\Http\Request;

class TanyaKiaiController extends Controller
{
    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string|min:2'
        ]);

        $service = new GeminiApiService();
        $response = $service->askQuestion($request->message);

        return response()->json([
            'success' => true,
            'data' => [
                'role' => 'kiai',
                'message' => $response
            ]
        ]);
    }
}
