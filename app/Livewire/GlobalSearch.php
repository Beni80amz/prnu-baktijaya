<?php

namespace App\Livewire;

use App\Models\Article;
use App\Models\News;
use App\Models\Umkm;
use Illuminate\Support\Collection;
use Livewire\Component;

class GlobalSearch extends Component
{
    public string $search = '';

    public function render()
    {
        $results = [];

        if (strlen($this->search) >= 2) {
            $results['articles'] = Article::query()
                ->where('status', 'published')
                ->where('title', 'like', '%' . $this->search . '%')
                ->latest()
                ->take(5)
                ->get();

            $results['news'] = News::query()
                ->where('status', 'published')
                ->where('title', 'like', '%' . $this->search . '%')
                ->latest()
                ->take(5)
                ->get();

            $results['umkm'] = Umkm::query()
                ->where('business_name', 'like', '%' . $this->search . '%')
                ->latest()
                ->take(3)
                ->get();
        }

        return view('livewire.global-search', [
            'results' => $results,
        ]);
    }
}
