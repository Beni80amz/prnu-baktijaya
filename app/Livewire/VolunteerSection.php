<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;

class VolunteerSection extends Component
{
    use WithPagination;

    public function paginationView()
    {
        return 'vendor.livewire.tailwind-no-scroll';
    }

    public function render()
    {
        return view('livewire.volunteer-section', [
            'volunteers' => \App\Models\Volunteer::with('region')
                ->paginate(8),
        ]);
    }
}
