<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\PrayerTimesService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
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

    public function getSettings()
    {
        try {
            $settings = \App\Models\Setting::whereIn('key', ['site_name', 'site_logo'])
                ->get()
                ->pluck('value', 'key');
            $siteLogo = $settings['site_logo'] ?? null;
            if ($siteLogo && !Str::startsWith($siteLogo, ['http://', 'https://'])) {
                $siteLogo = url('storage/' . $siteLogo);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'site_name' => $settings['site_name'] ?? 'PRNU Baktijaya',
                    'site_logo' => $siteLogo,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil pengaturan',
                'error' => $e->getMessage()
            ], 500);
        }
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
        $dawuhs = \App\Models\Dawuh::where('is_active', true)->latest()->take(5)->get();
        return response()->json([
            'success' => true,
            'data' => $dawuhs
        ]);
    }

    public function getCategories(Request $request)
    {
        try {
            \Illuminate\Support\Facades\Log::info('API Categories Request', ['type' => $request->type, 'all' => $request->all()]);

            $query = \App\Models\Category::where('is_active', true);

            if ($request->has('type')) {
                $query->where('type', $request->type);
            }

            $result = $query->get();
            \Illuminate\Support\Facades\Log::info('API Categories Count', ['count' => $result->count()]);

            return response()->json([
                'success' => true,
                'data' => $result
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('API Categories Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'data' => [],
                'message' => $e->getMessage()
            ], 200);
        }
    }

    public function getOrganization()
    {
        try {
            $settings = \App\Models\Setting::whereIn('key', [
                'site_name',
                'site_logo',
                'visi',
                'misi_1',
                'misi_2',
                'misi_3',
                'contact_address',
                'contact_email',
                'contact_phone',
                'contact_map_link',
                'social_instagram',
                'profile_description',
                'profile_image'
            ])->pluck('value', 'key')->toArray();

            // Process URLs
            foreach (['site_logo', 'profile_image'] as $key) {
                if (isset($settings[$key]) && !\Illuminate\Support\Str::startsWith($settings[$key], ['http://', 'https://'])) {
                    $settings[$key] = url('storage/' . $settings[$key]);
                }
            }

            $structure = \App\Models\OrganizationStructure::where('is_active', true)
                ->orderBy('order', 'asc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'settings' => $settings,
                    'structure' => $structure->map(function ($item) {
                        return [
                            'id' => $item->id,
                            'name' => $item->name,
                            'type' => $item->type,
                            'photo' => ($item->image && !Str::startsWith($item->image, ['http://', 'https://']))
                                ? url('storage/' . $item->image)
                                : $item->image,
                        ];
                    })
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data organisasi',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
