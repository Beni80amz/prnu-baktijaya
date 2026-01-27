<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\PrayerRequest;

class RuangDoa extends Component
{
    public string $name = '';
    public string $deceased_name = '';
    public string $request_type = 'tahlil'; // tahlil, doa_khusus
    public string $notes = '';

    public function submit()
    {
        $this->validate([
            'name' => 'required|min:3',
            'deceased_name' => 'required|min:3',
        ]);

        PrayerRequest::create([
            'name' => $this->name,
            'deceased_name' => $this->deceased_name,
            'request_type' => $this->request_type,
            'notes' => $this->notes,
            'status' => 'pending',
        ]);

        $this->reset(['deceased_name', 'notes']);
        session()->flash('message', 'Nama telah diterima untuk didoakan kolektif. Jazakallahu Khairan.');
    }

    public function render()
    {
        return view('livewire.ruang-doa')->layout('components.layouts.app');
    }
}
