<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Article;
use App\Models\Category;

class ArtikelIndex extends Component
{
    use WithPagination;

    public ?int $categoryId = null;
    public ?string $type = null;
    public string $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Article::where('status', 'published')
            ->latest('published_at');

        if ($this->categoryId) {
            $query->where('category_id', $this->categoryId);
        }

        if ($this->type) {
            $query->where('type', $this->type);
        }

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                    ->orWhere('excerpt', 'like', '%' . $this->search . '%');
            });
        }

        return view('livewire.artikel-index', [
            'articles' => $query->paginate(9),
            'categories' => Category::all(),
        ]);
    }
}
