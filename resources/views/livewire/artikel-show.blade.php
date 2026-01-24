<div>
    <article>
        <!-- Hero -->
        <header class="relative h-[400px] md:h-[500px] overflow-hidden">
            @if($article->featured_image)
                <div class="absolute inset-0 bg-cover bg-center"
                    style="background-image: url('{{ Storage::url($article->featured_image) }}');"></div>
            @else
                <div class="absolute inset-0 bg-gradient-to-br from-accent to-primary-dark"></div>
            @endif
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent"></div>

            <div class="absolute bottom-0 left-0 right-0 p-8">
                <div class="max-w-4xl mx-auto">
                    @if($article->type)
                        <span
                            class="inline-block px-4 py-1.5 bg-accent text-white text-xs font-bold rounded-full uppercase mb-4">
                            {{ $article->type }}
                        </span>
                    @endif
                    <h1 class="text-3xl md:text-5xl font-black text-white leading-tight mb-4">{{ $article->title }}</h1>
                    <div class="flex flex-wrap items-center gap-4 text-white/80 text-sm">
                        <span class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-lg">person</span>
                            {{ $article->author_name ?? $article->author?->name ?? 'Anonim' }}
                        </span>
                        <span class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-lg">calendar_today</span>
                            {{ $article->published_at?->format('d F Y') }}
                        </span>
                        <span class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-lg">visibility</span>
                            {{ number_format($article->views ?? 0) }} views
                        </span>
                    </div>
                </div>
            </div>
        </header>

        <!-- Content -->
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="prose prose-lg dark:prose-invert max-w-none">
                {!! $article->content !!}
            </div>

            <div class="mt-12 pt-8 border-t border-primary/10 dark:border-white/10">
                <a href="{{ route('artikel.index') }}" wire:navigate
                    class="inline-flex items-center text-primary font-bold hover:text-accent transition-colors">
                    <span class="material-symbols-outlined mr-2">arrow_back</span>
                    Kembali ke Daftar Artikel
                </a>
            </div>
        </div>
    </article>

    @if($relatedArticles->isNotEmpty())
        <section class="py-16 bg-background-light dark:bg-background-dark border-t border-primary/5 dark:border-white/5">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-2xl font-black text-background-dark dark:text-white mb-8">Artikel Terkait</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @foreach($relatedArticles as $related)
                        <article
                            class="group bg-white dark:bg-white/5 rounded-xl overflow-hidden shadow-sm hover:shadow-lg transition-all border border-primary/5 dark:border-white/5">
                            <a href="{{ route('artikel.show', $related->slug) }}" wire:navigate
                                class="block relative h-40 overflow-hidden">
                                @if($related->featured_image)
                                    <img src="{{ Storage::url($related->featured_image) }}" alt="{{ $related->title }}"
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                @else
                                    <div class="w-full h-full bg-accent/10 flex items-center justify-center">
                                        <span class="material-symbols-outlined text-3xl text-accent/40">menu_book</span>
                                    </div>
                                @endif
                            </a>
                            <div class="p-4">
                                <h3
                                    class="font-bold text-background-dark dark:text-white line-clamp-2 group-hover:text-primary transition-colors">
                                    <a href="{{ route('artikel.show', $related->slug) }}"
                                        wire:navigate>{{ $related->title }}</a>
                                </h3>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
</div>