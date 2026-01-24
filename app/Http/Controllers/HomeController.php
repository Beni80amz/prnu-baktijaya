<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Slider;
use App\Models\News;
use App\Models\Dawuh;

class HomeController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function index(Request $request)
    {
        return view('home', [
            'sliders' => Slider::where('is_active', true)->orderBy('order')->get(),
            'news' => News::where('status', 'published')->latest()->take(3)->get(),
            'dawuh' => Dawuh::where('is_active', true)->latest()->first(),
        ]);
    }
}
