<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Illuminate\Http\Request;

class GaleriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Gallery::where('is_active', true)
            ->latest();

        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        return response()->json($query->paginate(12));
    }
}
