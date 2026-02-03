<?php

namespace App\Livewire;

use Livewire\Component;
use App\Services\GeminiApiService;
use App\Models\TanyaKiai as TanyaKiaiModel;
use Filament\Notifications\Notification;

class TanyaKiai extends Component
{
    public string $userMessage = '';
    public array $chatHistory = [];
    public bool $isChatting = true;
    public $publicQuestions = [];

    // Traditional Form Fields
    public string $name = '';
    public string $email = '';
    public string $category = 'ibadah';
    public string $question = '';
    public $kiai_id = '';
    public $kiais = [];

    public function mount()
    {
        $this->kiais = \App\Models\Kiai::where('is_active', true)->get();
        $this->publicQuestions = TanyaKiaiModel::with('kiai')
            ->where('is_public', true)
            ->where('status', 'published')
            ->latest()
            ->get();

        $this->chatHistory[] = [
            'role' => 'kiai',
            'message' => 'Assalamualaikum wr. wb. Saya Kiai AI Baktijaya dari PRNU Baktijaya. Ada yang bisa saya bantu terkait masalah keagamaan atau ke-NU-an?'
        ];
    }

    public function sendMessage()
    {
        if (trim($this->userMessage) === '')
            return;

        $userText = $this->userMessage;
        $this->chatHistory[] = ['role' => 'user', 'message' => $userText];
        $this->userMessage = '';

        $service = new GeminiApiService();
        $response = $service->askQuestion($userText);

        $this->chatHistory[] = ['role' => 'kiai', 'message' => $response];
    }

    public function submitForm()
    {
        $this->validate([
            'name' => 'required|min:3',
            'question' => 'required|min:10',
            'category' => 'required',
            'kiai_id' => 'required|exists:kiais,id',
        ]);

        $record = TanyaKiaiModel::create([
            'name' => $this->name,
            'email' => $this->email,
            'kiai_id' => $this->kiai_id,
            'category' => $this->category,
            'question' => $this->question,
            'status' => 'pending',
            'is_public' => false,
        ]);

        $selectedKiai = \App\Models\Kiai::find($this->kiai_id);
        $phone = preg_replace('/[^0-9]/', '', $selectedKiai->phone);

        $message = "Assalamualaikum Kiai, saya " . $this->name . " ingin bertanya terkait " . $this->category . ".\n\n" . $this->question;
        $whatsappUrl = "https://wa.me/" . $phone . "?text=" . urlencode($message);

        $this->reset(['name', 'email', 'question', 'kiai_id']);

        $this->dispatch('form-submitted');

        return redirect()->away($whatsappUrl);
    }

    public function render()
    {
        return view('livewire.tanya-kiai')->layout('components.layouts.app');
    }
}
