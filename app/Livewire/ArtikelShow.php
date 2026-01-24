<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Article;

class ArtikelShow extends Component
{
    public Article $article;

    public function mount(string $slug)
    {
        $this->article = Article::where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        // Increment views
        $this->article->increment('views');
    }

    public function render()
    {
        $relatedArticles = Article::where('status', 'published')
            ->where('id', '!=', $this->article->id)
            ->where('category_id', $this->article->category_id)
            ->latest()
            ->take(3)
            ->get();

        return view('livewire.artikel-show', [
            'relatedArticles' => $relatedArticles,
        ]);
    }
}
