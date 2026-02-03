<?php

namespace App\Livewire;

use Livewire\Component;

use App\Models\ContributorApplication;
use Illuminate\Support\Facades\Auth;

class StatusVerifikasi extends Component
{
    public $application;

    public function mount()
    {
        if (!Auth::check()) {
            return redirect()->route('home');
        }

        $this->application = ContributorApplication::where('user_id', Auth::id())
            ->latest()
            ->first();

        if (!$this->application) {
            return redirect()->route('daftar.kontributor');
        }
    }

    public function render()
    {
        return view('livewire.status-verifikasi')
            ->layout('components.layouts.app');
    }
}
