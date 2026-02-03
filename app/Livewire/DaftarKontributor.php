<?php

namespace App\Livewire;

use Livewire\Component;

use App\Models\ContributorApplication;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DaftarKontributor extends Component
{
    public $name;
    public $email;
    public $phone;
    public $address;
    public $experience;
    public $bio;
    public $password;
    public $password_confirmation;

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
        $rules = [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users,email' . (Auth::check() ? ',' . Auth::id() : ''),
            'phone' => 'required|min:10',
            'address' => 'required',
            'experience' => 'required|min:20',
        ];

        if (!Auth::check()) {
            $rules['password'] = 'required|min:8|confirmed';
        }

        $this->validate($rules);

        if (!Auth::check()) {
            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => \Illuminate\Support\Facades\Hash::make($this->password),
                'status' => 'active',
            ]);

            Auth::login($user);
        }

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
