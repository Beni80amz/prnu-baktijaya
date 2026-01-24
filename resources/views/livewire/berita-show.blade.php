<div>
    <!-- Article Header -->
    <article>
        <!-- Hero Image -->
        <header class="relative h-[400px] md:h-[500px] overflow-hidden">
            @if($news->featured_image)
                <div class="absolute inset-0 bg-cover bg-center"
                    style="background-image: url('{{ Storage::url($news->featured_image) }}');"></div>
            @else
                <div class="absolute inset-0 bg-gradient-to-br from-primary to-primary-dark"></div>
            @endif
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent"></div>

            <div class="absolute bottom-0 left-0 right-0 p-8">
                <div class="max-w-4xl mx-auto">
                    @if($news->category)
                        <span class="inline-block px-4 py-1.5 bg-primary text-white text-xs font-bold rounded-full mb-4">
                            {{ $news->category->name }}
                        </span>
                    @endif
                    <h1 class="text-3xl md:text-5xl font-black text-white leading-tight mb-4">{{ $news->title }}</h1>
                    <div class="flex flex-wrap items-center gap-4 text-white/80 text-sm">
                        @if($news->author)
                            <span class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-lg">person</span>
                                {{ $news->author->name }}
                            </span>
                        @endif
                        <span class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-lg">calendar_today</span>
                            {{ $news->published_at?->format('d F Y') ?? $news->created_at->format('d F Y') }}
                        </span>
                        <span class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-lg">visibility</span>
                            {{ number_format($news->views ?? 0) }} views
                        </span>
                    </div>
                </div>
            </div>
        </header>

        <!-- Content -->
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <!-- Share Buttons -->
            <div class="flex items-center gap-4 mb-8 pb-8 border-b border-primary/10 dark:border-white/10">
                <span class="text-sm font-bold text-gray-500 dark:text-white/50">Bagikan:</span>
                <a href="https://wa.me/?text={{ urlencode($news->title . ' - ' . request()->url()) }}" target="_blank"
                    class="w-10 h-10 flex items-center justify-center rounded-full bg-green-500 text-white hover:bg-green-600 transition-colors">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                    </svg>
                </a>
                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" target="_blank"
                    class="w-10 h-10 flex items-center justify-center rounded-full bg-blue-600 text-white hover:bg-blue-700 transition-colors">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                    </svg>
                </a>
                <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($news->title) }}"
                    target="_blank"
                    class="w-10 h-10 flex items-center justify-center rounded-full bg-black text-white hover:bg-gray-800 transition-colors">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z" />
                    </svg>
                </a>
            </div>

            <!-- Article Content -->
            <style>
                .news-content p {
                    margin-bottom: 1.5rem !important;
                    line-height: 1.8 !important;
                }
                .news-content li {
                    margin-bottom: 0.5rem;
                }
            </style>
            <div class="news-content prose prose-lg dark:prose-invert max-w-none text-justify">
                {!! $news->content !!}
            </div>

            <!-- Back Button -->
            <div class="mt-12 pt-8 border-t border-primary/10 dark:border-white/10">
                <a href="{{ route('berita.index') }}" wire:navigate
                    class="inline-flex items-center text-primary font-bold hover:text-accent transition-colors">
                    <span class="material-symbols-outlined mr-2">arrow_back</span>
                    Kembali ke Daftar Berita
                </a>
            </div>
        </div>
    </article>

    <!-- Related News -->
    @if($relatedNews->isNotEmpty())
        <section class="py-16 bg-background-light dark:bg-background-dark border-t border-primary/5 dark:border-white/5">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-2xl font-black text-background-dark dark:text-white mb-8">Berita Terkait</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @foreach($relatedNews as $related)
                        <article
                            class="group bg-white dark:bg-white/5 rounded-xl overflow-hidden shadow-sm hover:shadow-lg transition-all border border-primary/5 dark:border-white/5">
                            <a href="{{ route('berita.show', $related->slug) }}" wire:navigate
                                class="block relative h-40 overflow-hidden">
                                @if($related->featured_image)
                                    <img src="{{ Storage::url($related->featured_image) }}" alt="{{ $related->title }}"
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                @else
                                    <div class="w-full h-full bg-primary/10 flex items-center justify-center">
                                        <span class="material-symbols-outlined text-3xl text-primary/40">image</span>
                                    </div>
                                @endif
                            </a>
                            <div class="p-4">
                                <h3
                                    class="font-bold text-background-dark dark:text-white line-clamp-2 group-hover:text-primary transition-colors">
                                    <a href="{{ route('berita.show', $related->slug) }}" wire:navigate>{{ $related->title }}</a>
                                </h3>
                                <p class="text-xs text-gray-500 dark:text-white/50 mt-2">
                                    {{ $related->published_at?->format('d M Y') }}</p>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
</div>