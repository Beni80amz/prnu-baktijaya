<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PrayerRequest;
use Illuminate\Http\Request;

class RuangDoaController extends Controller
{
    public function index()
    {
        $prayers = PrayerRequest::where('is_approved', true)
            ->latest()
            ->take(20)
            ->get()
            ->map(function ($prayer) {
                return [
                    'id' => $prayer->id,
                    'name' => $prayer->is_anonymous ? 'Hamba Allah' : $prayer->name,
                    'prayer' => $prayer->prayer,
                    'created_at' => $prayer->created_at->diffForHumans(),
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $prayers
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'prayer' => 'required|string|min:5',
            'is_anonymous' => 'boolean'
        ]);

        $prayer = PrayerRequest::create([
            'name' => $validated['name'],
            'prayer' => $validated['prayer'],
            'is_anonymous' => $validated['is_anonymous'] ?? false,
            'is_approved' => false // Moderated first
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Doa Anda telah dititipkan dan akan tampil setelah moderasi.',
            'data' => $prayer
        ], 201);
    }
}
