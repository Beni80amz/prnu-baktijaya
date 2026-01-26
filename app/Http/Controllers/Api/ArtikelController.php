<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;

class ArtikelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Article::where('status', 'published')
            ->latest('published_at');

        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                    ->orWhere('excerpt', 'like', '%' . $search . '%');
            });
        }

        return response()->json($query->paginate(10));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $val)
    {
        $article = Article::where('slug', $val)
            ->orWhere('id', $val)
            ->where('status', 'published')
            ->firstOrFail();

        return response()->json($article);
    }
}
