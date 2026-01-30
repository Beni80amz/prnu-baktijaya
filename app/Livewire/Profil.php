<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Setting;

class Profil extends Component
{

    public function render()
    {
        $settings = Setting::whereIn('group', ['profile', 'general'])->pluck('value', 'key')->toArray();
        // Syuriyah remains all (assumed small number)
        $syuriyah = \App\Models\OrganizationStructure::where('is_active', true)
            ->where('type', 'syuriyah')
            ->orderBy('order')
            ->get();

        $banoms = \App\Models\Banom::where('is_active', true)->orderBy('order')->get();

        return view('livewire.profil', [
            'settings' => $settings,
            'syuriyah' => $syuriyah,
            'banoms' => $banoms,
        ]);
    }
}
