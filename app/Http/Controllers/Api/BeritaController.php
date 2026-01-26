<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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
        return response()->json($query->paginate(10)->through(function ($news) {
            return [
                'id' => $news->id,
                'title' => $news->title,
                'slug' => $news->slug,
                'excerpt' => $news->excerpt,
                'content' => $news->content,
                'image' => ($news->featured_image && !Str::startsWith($news->featured_image, ['http://', 'https://']))
                    ? url('storage/' . $news->featured_image)
                    : $news->featured_image,
                'status' => $news->status,
                'published_at' => $news->published_at,
                'views' => $news->views,
            ];
        }));
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

        return response()->json([
            'id' => $news->id,
            'title' => $news->title,
            'slug' => $news->slug,
            'excerpt' => $news->excerpt,
            'content' => $news->content,
            'image' => ($news->featured_image && !Str::startsWith($news->featured_image, ['http://', 'https://']))
                ? url('storage/' . $news->featured_image)
                : $news->featured_image,
            'status' => $news->status,
            'published_at' => $news->published_at,
            'views' => $news->views,
        ]);
    }
}
