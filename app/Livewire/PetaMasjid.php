<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Mosque;
use App\Models\Setting;

class PetaMasjid extends Component
{
    public ?string $search = '';

    public function render()
    {
        $query = Mosque::where('is_active', true);

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('address', 'like', '%' . $this->search . '%');
            });
        }

        $settings = [
            'contact_address' => Setting::getValue('contact_address'),
        ];

        return view('livewire.peta-masjid', [
            'mosques' => $query->get(),
            'settings' => $settings,
        ]);
    }
}
