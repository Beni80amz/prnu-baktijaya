<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;

class TanfidziyahSection extends Component
{
    use WithPagination;

    public function paginationView()
    {
        return 'vendor.livewire.tailwind-no-scroll';
    }

    public function render()
    {
        return view('livewire.tanfidziyah-section', [
            'tanfidziyah' => \App\Models\OrganizationStructure::where('is_active', true)
                ->where('type', 'tanfidziyah')
                ->orderBy('order')
                ->paginate(8),
        ]);
    }
}
