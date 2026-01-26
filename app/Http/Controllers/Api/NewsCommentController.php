<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\NewsComment;
use Illuminate\Http\Request;

class NewsCommentController extends Controller
{
    public function index($newsId)
    {
        $comments = NewsComment::where('news_id', $newsId)
            ->where('is_active', true)
            ->latest()
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $comments
        ]);
    }

    public function store(Request $request, $newsId)
    {
        $request->validate([
            'comment' => 'required|string',
            'name' => 'nullable|string|max:255',
        ]);

        $comment = NewsComment::create([
            'news_id' => $newsId,
            'user_id' => auth('sanctum')->id(),
            'name' => auth('sanctum')->check() ? auth('sanctum')->user()->name : ($request->name ?? 'Anonim'),
            'comment' => $request->comment,
            'is_active' => true,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Komentar berhasil ditambahkan',
            'data' => $comment
        ], 210);
    }
}
