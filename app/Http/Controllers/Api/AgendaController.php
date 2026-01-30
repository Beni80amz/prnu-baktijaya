<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Agenda;
use Illuminate\Http\Request;

class AgendaController extends Controller
{
    public function index()
    {
        $agendas = Agenda::where('date', '>=', now()->toDateString())
            ->orderBy('date', 'asc')
            ->orderBy('time', 'asc')
            ->take(10)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $agendas,
        ]);
    }
}
