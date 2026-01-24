<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\News;
use App\Models\Category;

class BeritaIndex extends Component
{
    use WithPagination;

    public ?int $categoryId = null;
    public string $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingCategoryId()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = News::where('status', 'published')
            ->latest('published_at');

        if ($this->categoryId) {
            $query->where('category_id', $this->categoryId);
        }

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                    ->orWhere('excerpt', 'like', '%' . $this->search . '%');
            });
        }

        return view('livewire.berita-index', [
            'news' => $query->paginate(9),
            'categories' => Category::all(),
        ]);
    }
}
