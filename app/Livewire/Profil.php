<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Setting;

class Profil extends Component
{
    public function render()
    {
        $settings = Setting::whereIn('group', ['profile', 'general'])->pluck('value', 'key')->toArray();
        $structures = \App\Models\OrganizationStructure::where('is_active', true)->orderBy('order')->get();
        $banoms = \App\Models\Banom::where('is_active', true)->orderBy('order')->get();

        return view('livewire.profil', [
            'settings' => $settings,
            'syuriyah' => $structures->where('type', 'syuriyah'),
            'tanfidziyah' => $structures->where('type', 'tanfidziyah'),
            'banoms' => $banoms,
        ]);
    }
}
