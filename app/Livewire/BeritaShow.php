<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\News;

class BeritaShow extends Component
{
    public News $news;

    public function mount(string $slug)
    {
        $this->news = News::where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        // Increment views
        $this->news->increment('views');
    }

    public function render()
    {
        $relatedNews = News::where('status', 'published')
            ->where('id', '!=', $this->news->id)
            ->where('category_id', $this->news->category_id)
            ->latest()
            ->take(3)
            ->get();

        return view('livewire.berita-show', [
            'relatedNews' => $relatedNews,
        ]);
    }
}
