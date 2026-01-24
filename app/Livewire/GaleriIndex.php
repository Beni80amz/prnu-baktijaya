<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Gallery;

class GaleriIndex extends Component
{
    use WithPagination;

    public ?string $type = null;

    public function render()
    {
        $query = Gallery::where('is_active', true)
            ->latest();

        if ($this->type) {
            $query->where('type', $this->type);
        }

        return view('livewire.galeri-index', [
            'galleries' => $query->paginate(12),
        ]);
    }
}
