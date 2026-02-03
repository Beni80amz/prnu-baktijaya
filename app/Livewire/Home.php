<?php

namespace App\Livewire;

use Livewire\Component;
use App\Services\HijriService;
use App\Services\PrayerTimesService;
use App\Models\Slider;
use App\Models\News;
use App\Models\Dawuh;
use App\Models\Transaction;
use App\Models\Gallery;
use App\Models\Mosque;
use App\Models\Agenda;
use App\Models\Setting;
use App\Models\Region;
use App\Models\Donation;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class Home extends Component
{
    public int $cityId = 1225; // Default: KOTA DEPOK
    public string $cityName = 'KOTA DEPOK';
    public array $prayerTimes = [];
    public array $allCities = [];
    public string $activePrayer = 'isya'; // Default active prayer
    public string $hijriDate = '';
    public ?string $liveStreamUrl = null;

    public function mount()
    {
        try {
            $this->loadPrayerTimes();
        } catch (\Exception $e) {
            Log::error('Prayer times load error: ' . $e->getMessage());
            $this->prayerTimes = [];
        }

        try {
            $this->loadCities();
        } catch (\Exception $e) {
            Log::error('Cities load error: ' . $e->getMessage());
            $this->allCities = [];
        }

        try {
            $hijriService = new HijriService();
            $this->hijriDate = $hijriService->getTodayHijri();
        } catch (\Exception $e) {
            Log::error('Hijri service error: ' . $e->getMessage());
            $this->hijriDate = '';
        }

        // Fetch live stream from settings
        try {
            $this->liveStreamUrl = Setting::where('key', 'youtube_live_url')->value('value');
        } catch (\Exception $e) {
            $this->liveStreamUrl = null;
        }
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
        try {
            // Prepare sliders data array so Blade JSON encoding is simple
            $rawSliders = Slider::where('is_active', true)->orderBy('order')->get();
            $sliders = $rawSliders->map(fn($s) => [
                'image' => asset('storage/' . $s->image),
                'title' => $s->title,
                'description' => $s->description,
                'link_url' => $s->link_url,
                'button_text' => $s->button_text
            ])->values()->toArray();

            // Fetch Campaigns
            $campaigns = \App\Models\Campaign::where('is_active', true)
                ->orderBy('order')
                ->get()
                ->map(fn($c) => [
                    'id' => $c->id,
                    'title' => $c->title,
                    'image' => asset('storage/' . $c->image),
                    'subtitle' => $c->description,
                    'target_amount' => $c->target_amount,
                ]);

            // Fallback if no campaigns in DB
            if ($campaigns->isEmpty()) {
                $campaigns = collect([
                    [
                        'id' => 0,
                        'title' => 'Donasi Umum',
                        'image' => 'https://placehold.co/800x400/166534/FFFFFF?text=Donasi+Umum',
                        'subtitle' => 'Bantu kami dalam berbagai program kebaikan',
                        'target_amount' => 0,
                    ],
                ]);
            }

            return view('livewire.home', [
                'settings' => Setting::pluck('value', 'key')->toArray(),
                'sliders' => $sliders,
                'donationCampaigns' => $campaigns->toArray(), // Pass campaigns to view
                'news' => News::with('category')->where('status', 'published')->latest()->take(2)->get(),
                'dawuhs' => Dawuh::where('is_active', true)->latest()->get()->map(fn($d) => [
                    'quote' => $d->quote,
                    'ulama_name' => $d->ulama_name,
                    'ulama_title' => $d->ulama_title,
                ])->toArray(),
                'galleries' => Gallery::where('is_active', true)->latest()->take(6)->get(),
                'mosques' => Mosque::where('is_active', true)->take(4)->get(),
                'agendas' => Agenda::where('date', '>=', now()->toDateString())
                    ->orderBy('date', 'asc')
                    ->orderBy('time', 'asc')
                    ->take(3)
                    ->get(),
                'regions' => Region::orderBy('name')->get(),
                'totalInfaq' => Transaction::where('type', 'income')
                    ->whereMonth('transaction_date', Carbon::now()->month)
                    ->whereYear('transaction_date', Carbon::now()->year)
                    ->sum('amount'),
                'totalZakat' => Transaction::where('type', 'expense')
                    ->sum('amount'),
            ]);
        } catch (\Exception $e) {
            Log::error('Home render error: ' . $e->getMessage());
            // Fallback empty view or simple error message
            return view('livewire.home', [
                'settings' => [],
                'sliders' => [],
                'news' => collect(),
                'dawuh' => null,
                'galleries' => collect(),
                'mosques' => collect(),
                'agendas' => collect(),
                'totalInfaq' => 0,
                'totalZakat' => 0,
            ]);
        }
    }
    public function saveDonation($data)
    {
        try {
            // Basic cleaning/validation manually since data comes from JS object
            $amount = (int) preg_replace('/[^0-9]/', '', $data['amount']);

            // Generate ID if not provided or just generate new one to be safe
            $transactionId = 'DON-' . Carbon::now()->format('dmY') . '-' . mt_rand(1000, 9999);

            \App\Models\Donation::create([
                'transaction_id' => $transactionId,
                'campaign_name' => $data['campaign_name'] ?? 'Donasi Umum',
                'donor_name' => $data['donor_name'] ?? 'Hamba Allah',
                'donor_phone' => $data['donor_phone'] ?? null,
                'region_id' => !empty($data['region_id']) ? $data['region_id'] : null,
                'amount' => $amount,
                'payment_method' => $data['payment_method'] ?? 'Transfer',
                'bank_name' => $data['bank_name'] ?? null,
                'is_anonymous' => $data['is_anonymous'] ?? false,
                'status' => 'pending',
            ]);

            return $transactionId;
        } catch (\Exception $e) {
            Log::error('Donation save error: ' . $e->getMessage());
            return null;
        }
    }
}
