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

        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $results = $query->paginate(12);

        $results->getCollection()->transform(function ($item) {
            $images = $item->images;
            if (is_array($images)) {
                $item->images = array_map(function ($img) {
                    if ($img && !\Illuminate\Support\Str::startsWith($img, ['http://', 'https://'])) {
                        return url('storage/' . $img);
                    }
                    return $img;
                }, $images);
            } elseif (is_string($images)) {
                // Handle if it's stored as a single string path or JSON string
                $decoded = json_decode($images, true);
                if (is_array($decoded)) {
                    $item->images = array_map(function ($img) {
                        if ($img && !\Illuminate\Support\Str::startsWith($img, ['http://', 'https://'])) {
                            return url('storage/' . $img);
                        }
                        return $img;
                    }, $decoded);
                } elseif ($images && !\Illuminate\Support\Str::startsWith($images, ['http://', 'https://'])) {
                    $item->images = url('storage/' . $images);
                }
            }
            return $item;
        });

        return response()->json($results);
    }
}
