<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index()
    {
        $settings = Setting::pluck('value', 'key')->all();

        return response()->json([
            'success' => true,
            'data' => [
                'name' => $settings['site_name'] ?? 'PRNU Baktijaya',
                'description' => $settings['site_description'] ?? 'Portal Resmi Nahdlatul Ulama Ranting Baktijaya',
                'history' => $settings['organization_history'] ?? 'Sejarah belum diisi.',
                'vision' => $settings['organization_vision'] ?? 'Visi belum diisi.',
                'mission' => $settings['organization_mission'] ?? 'Misi belum diisi.',
                'address' => $settings['contact_address'] ?? '',
                'phone' => $settings['contact_phone'] ?? '',
                'email' => $settings['contact_email'] ?? '',
                'socials' => [
                    'facebook' => $settings['social_facebook'] ?? '#',
                    'instagram' => $settings['social_instagram'] ?? '#',
                    'youtube' => $settings['social_youtube'] ?? '#',
                ]
            ]
        ]);
    }
}
