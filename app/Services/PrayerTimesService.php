<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class PrayerTimesService
{
    protected string $baseUrl = 'https://api.myquran.com/v2/sholat';
    protected int $defaultCityId = 1225; // KOTA DEPOK

    /**
     * Get prayer schedule for a city on a specific date
     */
    public function getSchedule(int $cityId = null, ?Carbon $date = null): array
    {
        $cityId = $cityId ?? $this->defaultCityId;
        $date = $date ?? Carbon::now();

        $cacheKey = "prayer_times_{$cityId}_{$date->format('Y_m_d')}_v2";

        return Cache::remember($cacheKey, 3600, function () use ($cityId, $date) {
            try {
                $response = Http::timeout(5)->get("{$this->baseUrl}/jadwal/{$cityId}/{$date->format('Y/m/d')}");

                if ($response->successful() && $response->json('status')) {
                    $data = $response->json('data');

                    // Add Hijri Date
                    $hijriService = new HijriService();
                    $hijriDate = $hijriService->gregorianToHijri($date->day, $date->month, $date->year);

                    return [
                        'success' => true,
                        'city_id' => $data['id'],
                        'city_name' => $data['lokasi'],
                        'region' => $data['daerah'] ?? '',
                        'date' => $data['jadwal']['tanggal'] ?? $date->format('d/m/Y'),
                        'hijri' => $hijriDate,
                        'times' => [
                            'imsak' => $data['jadwal']['imsak'],
                            'subuh' => $data['jadwal']['subuh'],
                            'terbit' => $data['jadwal']['terbit'],
                            'dhuha' => $data['jadwal']['dhuha'],
                            'dzuhur' => $data['jadwal']['dzuhur'],
                            'ashar' => $data['jadwal']['ashar'],
                            'maghrib' => $data['jadwal']['maghrib'],
                            'isya' => $data['jadwal']['isya'],
                        ]
                    ];
                }
            } catch (\Exception $e) {
                \Log::error('Prayer times API error: ' . $e->getMessage());
            }

            return $this->getDefaultSchedule();
        });
    }

    /**
     * Search for a city by keyword
     */
    public function searchCity(string $keyword): array
    {
        $cacheKey = "prayer_city_search_" . md5($keyword);

        return Cache::remember($cacheKey, 86400, function () use ($keyword) {
            try {
                $response = Http::timeout(5)->get("{$this->baseUrl}/kota/cari/{$keyword}");

                if ($response->successful() && $response->json('status')) {
                    return [
                        'success' => true,
                        'cities' => $response->json('data')
                    ];
                }
            } catch (\Exception $e) {
                \Log::error('City search API error: ' . $e->getMessage());
            }

            return ['success' => false, 'cities' => []];
        });
    }

    /**
     * Get all available cities
     */
    public function getAllCities(): array
    {
        return Cache::remember('prayer_all_cities', 86400 * 7, function () {
            try {
                $response = Http::timeout(10)->get("{$this->baseUrl}/kota/semua");

                if ($response->successful() && $response->json('status')) {
                    return $response->json('data');
                }
            } catch (\Exception $e) {
                \Log::error('Get all cities API error: ' . $e->getMessage());
            }

            return [];
        });
    }

    /**
     * Default fallback schedule when API fails
     */
    protected function getDefaultSchedule(): array
    {
        return [
            'success' => false,
            'city_id' => $this->defaultCityId,
            'city_name' => 'KOTA DEPOK',
            'region' => 'JAWA BARAT',
            'date' => Carbon::now()->format('d/m/Y'),
            'hijri' => (new HijriService())->getTodayHijri(),
            'times' => [
                'imsak' => '04:20',
                'subuh' => '04:30',
                'terbit' => '05:48',
                'dhuha' => '06:16',
                'dzuhur' => '12:07',
                'ashar' => '15:30',
                'maghrib' => '18:20',
                'isya' => '19:34',
            ]
        ];
    }
}
