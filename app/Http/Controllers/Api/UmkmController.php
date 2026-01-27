<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Umkm;
use Illuminate\Http\Request;

class UmkmController extends Controller
{
    public function index(Request $request)
    {
        $query = Umkm::where('is_active', true);

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('business_name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->has('category') && $request->category != 'all') {
            $query->where('category', $request->category);
        }

        $umkms = $query->latest()->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $umkms
        ]);
    }

    public function show($id)
    {
        $umkm = Umkm::where('is_active', true)->find($id);

        if (!$umkm) {
            return response()->json([
                'success' => false,
                'message' => 'UMKM not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $umkm
        ]);
    }
}
