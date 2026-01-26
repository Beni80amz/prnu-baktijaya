<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\PrayerTimesService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class UtilityController extends Controller
{
    public function getJadwalSholat(Request $request)
    {
        $cityId = $request->input('city_id', 1225); // Default Depok
        $service = new PrayerTimesService();

        try {
            $schedule = $service->getSchedule($cityId);
            return response()->json($schedule);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal mengambil jadwal sholat'], 500);
        }
    }

    public function getCities(Request $request)
    {
        $service = new PrayerTimesService();
        if ($request->has('search')) {
            $result = $service->searchCity($request->search);
            return response()->json($result['cities'] ?? []);
        }
        return response()->json($service->getAllCities());
    }

    public function getAgenda()
    {
        // Placeholder for Agenda logic. 
        // If there is an Agenda model, we usually query it here.
        // For now returning empty or checking if there is a 'Post' type 'agenda'
        return response()->json([
            'message' => 'Agenda feature under construction',
            'data' => []
        ]);
    }

    public function getDawuh()
    {
        $dawuh = \App\Models\Dawuh::where('is_active', true)->latest()->first();
        return response()->json($dawuh);
    }
}
