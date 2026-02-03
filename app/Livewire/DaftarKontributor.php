<?php

namespace App\Livewire;

use Livewire\Component;

use App\Models\ContributorApplication;
use Illuminate\Support\Facades\Auth;

class DaftarKontributor extends Component
{
    public $name;
    public $email;
    public $phone;
    public $address;
    public $experience;
    public $bio;

    public function mount()
    {
        if (Auth::check()) {
            $user = Auth::user();
            $this->name = $user->name;
            $this->email = $user->email;

            // Check if already applied
            $existing = ContributorApplication::where('user_id', $user->id)
                ->whereIn('status', ['pending', 'approved'])
                ->first();

            if ($existing) {
                return redirect()->route('status.verifikasi');
            }
        }
    }

    public function submit()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $this->validate([
            'name' => 'required|min:3',
            'email' => 'required|email',
            'phone' => 'required|min:10',
            'address' => 'required',
            'experience' => 'required|min:20',
        ]);

        ContributorApplication::create([
            'user_id' => Auth::id(),
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'experience' => $this->experience,
            'bio' => $this->bio,
            'status' => 'pending',
        ]);

        return redirect()->route('status.verifikasi');
    }

    public function render()
    {
        return view('livewire.daftar-kontributor')
            ->layout('components.layouts.app');
    }
}
