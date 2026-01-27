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

    // Traditional Form Fields
    public string $name = '';
    public string $email = '';
    public string $category = 'ibadah';
    public string $question = '';

    public function mount()
    {
        $this->chatHistory[] = [
            'role' => 'kiai',
            'message' => 'Assalamualaikum wr. wb. Saya KH. Baktijaya dari PRNU Baktijaya. Ada yang bisa saya bantu terkait masalah keagamaan atau ke-NU-an?'
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
        ]);

        TanyaKiaiModel::create([
            'name' => $this->name,
            'email' => $this->email,
            'category' => $this->category,
            'question' => $this->question,
            'status' => 'pending',
            'is_public' => false,
        ]);

        $this->reset(['name', 'email', 'question']);

        $this->dispatch('form-submitted');
    }

    public function render()
    {
        return view('livewire.tanya-kiai')->layout('components.layouts.app');
    }
}
