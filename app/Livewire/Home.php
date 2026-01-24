<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Slider;
use App\Models\News;
use App\Models\Dawuh;
use App\Models\Transaction;
use App\Services\PrayerTimesService;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Home extends Component
{
    public int $cityId = 1225; // Default: KOTA DEPOK
    public string $cityName = 'KOTA DEPOK';
    public array $prayerTimes = [];
    public array $allCities = [];
    public string $activePrayer = 'isya'; // Default active prayer

    public function mount()
    {
        $this->loadPrayerTimes();
        $this->loadCities();
    }

    public function loadPrayerTimes()
    {
        $service = new PrayerTimesService();
        $schedule = $service->getSchedule($this->cityId);

        $this->prayerTimes = $schedule['times'];
        $this->cityName = $schedule['city_name'];
        $this->activePrayer = $this->getCurrentActivePrayer();
    }

    /**
     * Determine which prayer is currently active based on current time
     */
    protected function getCurrentActivePrayer(): string
    {
        // Use Asia/Jakarta timezone for Indonesian prayer times
        $now = Carbon::now('Asia/Jakarta');
        $today = $now->format('Y-m-d');

        $prayers = ['subuh', 'dzuhur', 'ashar', 'maghrib', 'isya'];
        $activePrayer = 'isya'; // Default to isya (after isya or before subuh)

        foreach ($prayers as $prayer) {
            if (isset($this->prayerTimes[$prayer])) {
                $prayerTime = Carbon::parse($today . ' ' . $this->prayerTimes[$prayer], 'Asia/Jakarta');

                if ($now->gte($prayerTime)) {
                    $activePrayer = $prayer;
                }
            }
        }

        return $activePrayer;
    }

    public function loadCities()
    {
        $service = new PrayerTimesService();
        $this->allCities = $service->getAllCities();
    }

    public function updateCity(int $cityId)
    {
        $this->cityId = $cityId;
        $this->loadPrayerTimes();
    }

    public function searchCity(string $keyword)
    {
        if (strlen($keyword) < 3) {
            return [];
        }

        $service = new PrayerTimesService();
        $result = $service->searchCity($keyword);

        return $result['cities'] ?? [];
    }

    public function render()
    {
        return view('livewire.home', [
            'settings' => \App\Models\Setting::pluck('value', 'key')->toArray(),
            'sliders' => Slider::where('is_active', true)->orderBy('order')->get(),
            'news' => News::where('status', 'published')->latest()->take(2)->get(),
            'dawuh' => Dawuh::where('is_active', true)->latest()->first(),
            'galleries' => \App\Models\Gallery::where('is_active', true)->latest()->take(6)->get(),
            'mosques' => \App\Models\Mosque::where('is_active', true)->take(4)->get(),
            'totalInfaq' => Transaction::where('type', 'income')
                ->where('category', 'infaq')
                ->whereMonth('transaction_date', Carbon::now()->month)
                ->whereYear('transaction_date', Carbon::now()->year)
                ->sum('amount'),
            'totalZakat' => Transaction::where('type', 'expense')
                ->where('category', 'zakat')
                ->sum('amount'),
        ]);
    }
}
