<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;

class BeritaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = News::where('status', 'published')
            ->latest('published_at');

        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                    ->orWhere('excerpt', 'like', '%' . $search . '%');
            });
        }

        // Return pagination result
        return response()->json($query->paginate(10));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $val)
    {
        // Accept slug or ID
        $news = News::where('slug', $val)
            ->orWhere('id', $val)
            ->where('status', 'published')
            ->firstOrFail();

        return response()->json($news);
    }
}
