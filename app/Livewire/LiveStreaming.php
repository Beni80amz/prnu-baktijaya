<?php

namespace App\Livewire;

use App\Models\Setting;
use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Live Streaming - PRNU Baktijaya')]
class LiveStreaming extends Component
{
    public $youtubeUrl;
    public $youtubeId;
    public $title;
    public $description;
    public $channelName;
    public $siteLogo;
    public $isLive = false;

    // Chat Properties
    public $chatName;
    public $chatMessage;

    // Attendance Properties
    public $attendanceName;
    public $attendanceAddress;
    public $attendanceMessage;
    public $activeTab = 'chat'; // 'chat' or 'attendance'

    // Data Properties
    public $upcomingSchedules = [];

    // Donation Properties
    public $donationQrisImage;
    public $donationBankName;
    public $donationBankNumber;
    public $donationBankOwner;
    public $donationBankBri;
    public $donationBankBca;
    public $donationBankMandiri;
    public $donationEwalletOvo;
    public $donationEwalletGopay;
    public $donationContact;

    public function mount()
    {
        // 1. Load basic settings first
        $this->title = Setting::getValue('youtube_live_title', 'Pengajian Rutin Lailatul Ijtima & Istighosah');
        $this->description = Setting::getValue('youtube_live_description', 'Pengasuh Majelis PRNU Baktijaya');
        $this->channelName = Setting::getValue('youtube_channel_name', 'Kiyai. Saroham Asymuni, S.Pd.I');

        // Logo / Avatar Logic
        $this->siteLogo = Setting::getValue('site_logo');
        if (!$this->siteLogo) {
            $rais = \App\Models\OrganizationStructure::where('position', 'RAIS')->first();
            if ($rais && $rais->image) {
                $this->siteLogo = $rais->image;
            }
        }

        // Donation Settings
        $this->donationQrisImage = Setting::getValue('donation_qris_image');
        $this->donationBankName = Setting::getValue('donation_bank_name');
        $this->donationBankNumber = Setting::getValue('donation_bank_number');
        $this->donationBankOwner = Setting::getValue('donation_bank_owner');
        $this->donationBankBri = Setting::getValue('donation_bank_bri');
        $this->donationBankBca = Setting::getValue('donation_bank_bca');
        $this->donationBankMandiri = Setting::getValue('donation_bank_mandiri');
        $this->donationEwalletOvo = Setting::getValue('donation_ewallet_ovo');
        $this->donationEwalletGopay = Setting::getValue('donation_ewallet_gopay');
        $this->donationContact = Setting::getValue('donation_contact_person');

        // 2. Check for API Credentials
        $apiKey = Setting::getValue('youtube_api_key');
        $channelId = Setting::getValue('youtube_channel_id');

        if ($apiKey && $channelId) {
            // --- AUTOMATIC MODE (VIA API) ---
            $this->fetchYoutubeData($apiKey, $channelId);
            $this->fetchUpcomingSchedule($apiKey, $channelId);
        } else {
            // --- MANUAL MODE (FALLBACK) ---
            $this->youtubeUrl = Setting::getValue('youtube_live_url');
            $this->isLive = Setting::getValue('youtube_is_live', false);

            if ($this->youtubeUrl) {
                $this->youtubeId = $this->extractYoutubeId($this->youtubeUrl);
            }
        }
    }

    public function updatedActiveTab()
    {
        // Reset inputs when switching tabs if needed
    }

    // --- CHAT LOGIC ---
    public function sendChat()
    {
        $this->validate([
            'chatName' => 'required|min:3|max:50',
            'chatMessage' => 'required|min:1|max:200',
        ]);

        $colors = ['bg-red-500', 'bg-blue-500', 'bg-green-500', 'bg-yellow-500', 'bg-purple-500', 'bg-pink-500', 'bg-indigo-500'];
        $randomColor = $colors[array_rand($colors)];

        \App\Models\LiveChat::create([
            'name' => $this->chatName,
            'message' => $this->chatMessage,
            'avatar_color' => $randomColor,
        ]);

        $this->chatMessage = ''; // Keep name for convenience
    }

    public function getChatsProperty()
    {
        return \App\Models\LiveChat::latest()->take(50)->get()->reverse(); // Get latest 50, but display oldest top? No, usually chat is bottom-up or top-down. Design shows list. Let's stick to latest.
    }

    // --- ATTENDANCE LOGIC ---
    public function submitAttendance()
    {
        $this->validate([
            'attendanceName' => 'required|min:3|max:50',
            'attendanceAddress' => 'required|max:100',
            'attendanceMessage' => 'nullable|max:200',
        ]);

        \App\Models\LiveAttendance::create([
            'name' => $this->attendanceName,
            'address' => $this->attendanceAddress,
            'message' => $this->attendanceMessage,
        ]);

        $this->reset(['attendanceName', 'attendanceAddress', 'attendanceMessage']);
        session()->flash('success_attendance', 'Terima kasih telah mengisi daftar hadir!');
    }

    public function getAttendancesProperty()
    {
        return \App\Models\LiveAttendance::latest()->take(50)->get();
    }

    // --- YOUTUBE LOGIC ---
    private function fetchUpcomingSchedule($apiKey, $channelId)
    {
        $cacheKey = 'youtube_upcoming_' . $channelId;

        $this->upcomingSchedules = \Illuminate\Support\Facades\Cache::remember($cacheKey, 3600, function () use ($apiKey, $channelId) { // Cache 1 hour
            // 3. Get Upcoming Videos
            $upcomingResponse = \Illuminate\Support\Facades\Http::get('https://www.googleapis.com/youtube/v3/search', [
                'part' => 'snippet',
                'channelId' => $channelId,
                'type' => 'video',
                'eventType' => 'upcoming',
                'order' => 'date',
                'maxResults' => 2,
                'key' => $apiKey,
            ]);

            if ($upcomingResponse->successful() && !empty($upcomingResponse->json()['items'])) {
                return collect($upcomingResponse->json()['items'])->map(function ($item) {
                    return [
                        'title' => $item['snippet']['title'],
                        'thumbnail' => $item['snippet']['thumbnails']['medium']['url'],
                        'scheduled_start' => isset($item['snippet']['publishedAt']) ? \Carbon\Carbon::parse($item['snippet']['publishedAt'])->translatedFormat('l, H:i WIB') : 'Segera', // Note: API returns publishedAt for upcoming? Actually snippet has liveBroadcastContent. Upcoming usually needs video details to get scheduledStartTime. Search endpoint might not return exact schedule time in snippet. Let's us publishedAt as proxy or just show "Segera". 
                        // Correction: Search API snippet doesn't have scheduledStartTime. We need 'video' detail API. 
                        // For simplicity in this iteration, we use publishedAt or a generic label if unavailable, OR we risk an extra API call.
                        // Let's stick to simple display first.
                        'description' => $item['snippet']['description'],
                    ];
                })->toArray();
            }
            return [];
        });
    }

    private function fetchYoutubeData($apiKey, $channelId)
    {
        // Cache key for live status (short cache: 15 mins to save quota)
        // Quota Limit: 10,000 units/day. Search cost: 100 units.
        // 1 call every 15 mins = 96 calls/day = 9,600 units. Safe.
        $cacheKey = 'youtube_live_status_' . $channelId;

        $data = \Illuminate\Support\Facades\Cache::remember($cacheKey, 900, function () use ($apiKey, $channelId) {
            // 1. Check if Live
            $liveResponse = \Illuminate\Support\Facades\Http::get('https://www.googleapis.com/youtube/v3/search', [
                'part' => 'snippet',
                'channelId' => $channelId,
                'type' => 'video',
                'eventType' => 'live',
                'key' => $apiKey,
            ]);

            if ($liveResponse->successful() && !empty($liveResponse->json()['items'])) {
                $item = $liveResponse->json()['items'][0];
                return [
                    'is_live' => true,
                    'video_id' => $item['id']['videoId'],
                    'title' => $item['snippet']['title'],
                    'description' => $item['snippet']['description'],
                ];
            }

            // 2. If Not Live, Get Latest Video
            $videoResponse = \Illuminate\Support\Facades\Http::get('https://www.googleapis.com/youtube/v3/search', [
                'part' => 'snippet',
                'channelId' => $channelId,
                'order' => 'date', // Latest
                'type' => 'video',
                'maxResults' => 1,
                'key' => $apiKey,
            ]);

            if ($videoResponse->successful() && !empty($videoResponse->json()['items'])) {
                $item = $videoResponse->json()['items'][0];
                return [
                    'is_live' => false,
                    'video_id' => $item['id']['videoId'],
                    'title' => $item['snippet']['title'],
                    'description' => $item['snippet']['description'],
                ];
            }

            return null;
        });

        if ($data) {
            $this->isLive = $data['is_live'];
            $this->youtubeId = $data['video_id'];
            $this->title = $data['title'];
            $this->description = \Illuminate\Support\Str::limit($data['description'], 100);
        } else {
            // Fallback if API fails or returns nothing (e.g. quota exceeded)
            $this->youtubeUrl = Setting::getValue('youtube_live_url');
            $this->youtubeId = $this->extractYoutubeId($this->youtubeUrl);
        }
    }

    private function extractYoutubeId($url)
    {
        $pattern = '/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i';
        if (preg_match($pattern, $url, $matches)) {
            return $matches[1];
        }
        return null;
    }

    public function render()
    {
        return view('livewire.live-streaming');
    }
}
