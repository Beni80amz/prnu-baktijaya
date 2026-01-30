<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LiveAttendance;
use App\Models\LiveChat;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class LiveStreamingController extends Controller
{
    public function index()
    {
        // 1. Load basic settings
        $title = Setting::getValue('youtube_live_title', 'Pengajian Rutin Lailatul Ijtima & Istighosah');
        $description = Setting::getValue('youtube_live_description', 'Pengasuh Majelis PRNU Baktijaya');
        $channelName = Setting::getValue('youtube_channel_name', 'Kiyai. Saroham Asymuni, S.Pd.I');

        // Logo logic
        $siteLogo = Setting::getValue('site_logo');
        if (!$siteLogo) {
            $rais = \App\Models\OrganizationStructure::where('position', 'RAIS')->first();
            if ($rais && $rais->image) {
                $siteLogo = $rais->image;
            }
        }
        if ($siteLogo && !str_starts_with($siteLogo, 'http')) {
            $siteLogo = url('storage/' . $siteLogo);
        }

        // Donation Settings
        $donation = [
            'qris_image' => Setting::getValue('donation_qris_image'),
            'bank_name' => Setting::getValue('donation_bank_name'),
            'bank_number' => Setting::getValue('donation_bank_number'),
            'bank_owner' => Setting::getValue('donation_bank_owner'),
        ];
        if ($donation['qris_image'] && !str_starts_with($donation['qris_image'], 'http')) {
            $donation['qris_image'] = url('storage/' . $donation['qris_image']);
        }

        // 2. Check for API Credentials & Youtube Data
        $apiKey = Setting::getValue('youtube_api_key');
        $channelId = Setting::getValue('youtube_channel_id');

        $youtubeData = [
            'is_live' => false,
            'youtube_id' => null,
            'youtube_url' => null,
            'title' => $title,
            'description' => $description,
            'thumbnail' => null,
        ];

        $upcomingSchedules = [];

        if ($apiKey && $channelId) {
            // Automatic Mode
            $fetchedData = $this->fetchYoutubeData($apiKey, $channelId);
            if ($fetchedData) {
                $youtubeData['is_live'] = $fetchedData['is_live'];
                $youtubeData['youtube_id'] = $fetchedData['video_id'];
                $youtubeData['youtube_url'] = 'https://www.youtube.com/watch?v=' . $fetchedData['video_id'];
                $youtubeData['title'] = $fetchedData['title'];
                $youtubeData['description'] = $fetchedData['description'];
                $youtubeData['thumbnail'] = $fetchedData['thumbnail'] ?? null;
            }

            $upcomingSchedules = $this->fetchUpcomingSchedule($apiKey, $channelId);
        } else {
            // Manual Mode
            $youtubeUrl = Setting::getValue('youtube_live_url');
            $isLive = Setting::getValue('youtube_is_live', false);

            $youtubeData['youtube_url'] = $youtubeUrl;
            $youtubeData['is_live'] = filter_var($isLive, FILTER_VALIDATE_BOOLEAN);
            if ($youtubeUrl) {
                $youtubeData['youtube_id'] = $this->extractYoutubeId($youtubeUrl);
            }
        }

        return response()->json([
            'success' => true,
            'data' => [
                'info' => [
                    'title' => $youtubeData['title'],
                    'description' => $youtubeData['description'],
                    'channel_name' => $channelName,
                    'speaker_avatar' => $siteLogo,
                ],
                'video' => $youtubeData,
                'donation' => $donation,
                'upcoming' => $upcomingSchedules,
            ]
        ]);
    }

    public function getChats()
    {
        $chats = LiveChat::latest()->take(50)->get()->map(function ($chat) {
            return [
                'id' => $chat->id,
                'name' => $chat->name,
                'message' => $chat->message,
                'avatar_color' => $chat->avatar_color,
                'created_at' => $chat->created_at->format('H:i'),
                'is_admin' => false, // Default for now, generic logic
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $chats
        ]);
    }

    public function sendChat(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3|max:50',
            'message' => 'required|min:1|max:200',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $colors = ['bg-red-500', 'bg-blue-500', 'bg-green-500', 'bg-yellow-500', 'bg-purple-500', 'bg-pink-500', 'bg-indigo-500'];
        $randomColor = $colors[array_rand($colors)];

        $chat = LiveChat::create([
            'name' => $request->name,
            'message' => $request->message,
            'avatar_color' => $randomColor,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Chat sent',
            'data' => $chat
        ]);
    }

    public function submitAttendance(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3|max:50',
            'address' => 'required|max:100',
            'message' => 'nullable|max:200',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        LiveAttendance::create([
            'name' => $request->name,
            'address' => $request->address,
            'message' => $request->message,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Attendance submitted'
        ]);
    }

    // --- Private Helpers (Replicated from Livewire) ---

    private function fetchUpcomingSchedule($apiKey, $channelId)
    {
        $cacheKey = 'youtube_upcoming_v2_' . $channelId;

        return Cache::remember($cacheKey, 3600, function () use ($apiKey, $channelId) {
            $response = Http::get('https://www.googleapis.com/youtube/v3/search', [
                'part' => 'snippet',
                'channelId' => $channelId,
                'type' => 'video',
                'eventType' => 'upcoming',
                'order' => 'date',
                'maxResults' => 5,
                'key' => $apiKey,
            ]);

            if ($response->successful() && !empty($response->json()['items'])) {
                return collect($response->json()['items'])->map(function ($item) {
                    return [
                        'title' => $item['snippet']['title'],
                        'thumbnail' => $item['snippet']['thumbnails']['medium']['url'],
                        'scheduled_start' => isset($item['snippet']['publishedAt'])
                            ? Carbon::parse($item['snippet']['publishedAt'])->translatedFormat('l, H:i WIB')
                            : 'Segera',
                        'description' => $item['snippet']['description'],
                        'video_id' => $item['id']['videoId'],
                    ];
                })->toArray();
            }
            return [];
        });
    }

    private function fetchYoutubeData($apiKey, $channelId)
    {
        $cacheKey = 'youtube_live_status_' . $channelId;

        return Cache::remember($cacheKey, 900, function () use ($apiKey, $channelId) {
            // 1. Check Live
            $liveResponse = Http::get('https://www.googleapis.com/youtube/v3/search', [
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
                    'thumbnail' => $item['snippet']['thumbnails']['high']['url'] ?? $item['snippet']['thumbnails']['medium']['url'],
                ];
            }

            // 2. Get Latest Video
            $videoResponse = Http::get('https://www.googleapis.com/youtube/v3/search', [
                'part' => 'snippet',
                'channelId' => $channelId,
                'order' => 'date',
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
                    'thumbnail' => $item['snippet']['thumbnails']['high']['url'] ?? $item['snippet']['thumbnails']['medium']['url'],
                ];
            }

            return null;
        });
    }

    private function extractYoutubeId($url)
    {
        // Support various YouTube URL formats including /live/
        // Examples:
        // - https://www.youtube.com/watch?v=VIDEO_ID
        // - https://youtu.be/VIDEO_ID
        // - https://www.youtube.com/embed/VIDEO_ID
        // - https://www.youtube.com/live/VIDEO_ID
        // - https://www.youtube.com/v/VIDEO_ID
        $pattern = '/(?:youtube\.com\/(?:live\/|watch\?v=|embed\/|v\/|.+\?v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/i';
        if (preg_match($pattern, $url, $matches)) {
            return $matches[1];
        }
        return null;
    }
}
