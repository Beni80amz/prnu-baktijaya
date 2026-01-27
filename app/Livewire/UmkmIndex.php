<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Umkm;

class UmkmIndex extends Component
{
    use WithPagination;

    public string $search = '';
    public ?string $category = null;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Umkm::where('is_active', true)
            ->latest();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('business_name', 'like', '%' . $this->search . '%')
                    ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->category) {
            $query->where('category', $this->category);
        }

        // Get unique categories for filter
        $categories = Umkm::where('is_active', true)
            ->distinct()
            ->pluck('category')
            ->filter();

        return view('livewire.umkm-index', [
            'umkms' => $query->paginate(12),
            'categories' => $categories,
        ]);
    }
}
