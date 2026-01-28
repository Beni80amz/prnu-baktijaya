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
            'deceased_name' => 'required|string|max:255',
            'request_type' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        $prayer = PrayerRequest::create([
            'requester_name' => $validated['name'],
            'names' => [$validated['deceased_name']], // Cast as array in model
            'type' => $validated['request_type'],
            'notes' => $validated['notes'],
            'status' => 'pending',
            'requested_date' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Doa Anda telah dititipkan dan akan tampil setelah moderasi.',
            'data' => $prayer
        ], 201);
    }
}
