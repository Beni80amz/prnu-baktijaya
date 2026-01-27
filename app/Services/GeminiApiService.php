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
            // Using legacy prompt structure (system instruction inside content) plus gemini-pro for maximum compatibility
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post("{$this->baseUrl}?key={$this->apiKey}", [
                        'contents' => [
                            [
                                'parts' => [
                                    ['text' => $systemInstruction . "\n\nPertanyaan: " . $question]
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

            // Expose the actual error message for debugging purposes
            $errorBody = json_decode($response->body(), true);
            $errorMessage = $errorBody['error']['message'] ?? $response->body();
            Log::error('Gemini API Error: ' . $response->body());

            // IF 404, Try to list available models to help user debug
            if ($response->status() === 404) {
                $availableModelsMsg = "";
                try {
                    $modelsResponse = Http::get("https://generativelanguage.googleapis.com/v1beta/models?key={$this->apiKey}");
                    if ($modelsResponse->successful()) {
                        $modelsData = $modelsResponse->json();
                        $availableModels = [];
                        if (isset($modelsData['models'])) {
                            foreach ($modelsData['models'] as $m) {
                                $availableModels[] = str_replace('models/', '', $m['name']);
                            }
                        }
                        $availableModelsMsg = "\n\nMODEL YANG TERSEDIA UNTUK KEY ANDA: \n- " . implode("\n- ", $availableModels);
                    } else {
                        $availableModelsMsg = "\n\nGAGAL LIST MODELS: " . $modelsResponse->body();
                    }
                } catch (\Exception $ex) {
                    $availableModelsMsg = "\n\nEXCEPTION LIST MODELS: " . $ex->getMessage();
                }

                return "GOOGLE ERROR (404): Model tidak ditemukan. " . $availableModelsMsg;
            }

            return 'GOOGLE ERROR (' . $response->status() . '): ' . substr($errorMessage, 0, 150) . '...';

        } catch (\Exception $e) {
            Log::error('Gemini Service Exception: ' . $e->getMessage());
            return 'Mohon maaf, hamba sakhaya sedang mengalami gangguan teknis. ' . $e->getMessage();
        }
    }
}
