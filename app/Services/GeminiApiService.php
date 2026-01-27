<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiApiService
{
    protected string $apiKey;
    protected string $baseUrl = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent';

    public function __construct()
    {
        $this->apiKey = config('services.gemini.key', env('GEMINI_API_KEY'));
    }

    public function askQuestion(string $question): ?string
    {
        if (!$this->apiKey) {
            Log::error('Gemini API Key is not set.');
            return 'Mohon maaf, layanan tanya kiai sedang tidak tersedia (API Key missing).';
        }

        $systemInstruction = "Anda adalah 'KH. Baktijaya', seorang kiai virtual dari Pengurus Ranting Nahdlatul Ulama (PRNU) Baktijaya. 
        Tugas Anda adalah memberikan jawaban atas pertanyaan masalah agama, sosial, dan NU berdasarkan tradisi Ahlussunnah wal Jamaah (Aswaja) An-Nahdliyah. 
        Gunakan gaya bahasa yang santun, sejuk, dan merangkul. 
        Ringkas jawaban Anda agar mudah dibaca di mobile. 
        Jika pertanyaan di luar masalah agama atau NU, arahkan dengan sopan agar bertanya seputar keislaman.";

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post("{$this->baseUrl}?key={$this->apiKey}", [
                        'system_instruction' => [
                            'parts' => [
                                ['text' => $systemInstruction]
                            ]
                        ],
                        'contents' => [
                            [
                                'parts' => [
                                    ['text' => $question]
                                ]
                            ]
                        ],
                        'generationConfig' => [
                            'temperature' => 0.7,
                            'maxOutputTokens' => 800,
                        ]
                    ]);

            if ($response->successful()) {
                $data = $response->json();
                return $data['candidates'][0]['content']['parts'][0]['text'] ?? 'Maaf, saya tidak bisa menemukan jawaban untuk itu.';
            }

            Log::error('Gemini API Error: ' . $response->body());
            return 'Maaf, terjadi gangguan saat menghubungi sistem AI. (' . $response->status() . ')';

        } catch (\Exception $e) {
            Log::error('Gemini Service Exception: ' . $e->getMessage());
            return 'Mohon maaf, hamba sakhaya sedang mengalami gangguan teknis.';
        }
    }
}
