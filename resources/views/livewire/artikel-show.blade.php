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
            <!-- Share Buttons (Synced with News Page & High Visibility) -->
            <div class="flex items-center gap-4 mb-8 pb-8 border-b border-primary/10 dark:border-white/10">
                <span class="text-sm font-bold text-gray-500 dark:text-white/50">Bagikan:</span>

                @php
                    $shareUrl = urlencode(request()->fullUrl());
                    $shareTitle = urlencode($article->title);
                @endphp

                <!-- WhatsApp -->
                <a href="https://api.whatsapp.com/send?text={{ $shareTitle }}%20{{ $shareUrl }}" target="_blank"
                    class="w-10 h-10 flex items-center justify-center rounded-full bg-green-500 text-white hover:bg-green-600 transition-colors shadow-sm"
                    title="Bagikan ke WhatsApp">
                    <svg class="w-5 h-5" fill="white" viewBox="0 0 24 24">
                        <path
                            d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                    </svg>
                </a>

                <!-- Instagram -->
                <a href="https://www.instagram.com/" target="_blank"
                    class="w-10 h-10 flex items-center justify-center rounded-full text-white hover:opacity-90 transition-opacity shadow-sm"
                    style="background-color: #E4405F;"
                    title="Bagikan ke Instagram">
                    <svg class="w-5 h-5" fill="white" viewBox="0 0 24 24">
                        <path
                            d="M12 2.163c3.204 0 3.584.012 4.85.07 1.366.062 2.633.332 3.608 1.308.975.975 1.247 2.242 1.308 3.608.058 1.266.07 1.646.07 4.85s-.012 3.584-.07 4.85c-.062 1.366-.332 2.633-1.308 3.608-.975.975-2.242 1.247-3.608 1.308-1.266.058-1.646.07-4.85.07s-3.584-.012-4.85-.07c-1.366-.062-2.633-.332-3.608-1.308-.975-.975-1.247-2.242-1.308-3.608-.058-1.266-.07-1.646-.07-4.85s.012-3.584.07-4.85c.062-1.366.332-2.633 1.308-3.608.975-.975 2.242-1.247 3.608-1.308 1.266-.058 1.646-.07 4.85-.07zm0-2.163c-3.259 0-3.667.014-4.947.072-1.303.06-2.193.264-2.97.567-.803.312-1.484.73-2.163 1.41-.68.679-1.097 1.36-1.41 2.163-.303.777-.507 1.667-.567 2.97-.058 1.279-.071 1.687-.071 4.947s.013 3.668.071 4.948c.06 1.303.264 2.193.567 2.97.312.802.73 1.483 1.41 2.162.679.68 1.36 1.097 2.163 1.41.777.303 1.667.507 2.97.567 1.279.058 1.688.072 4.947.072s3.668-.014 4.948-.072c1.303-.06 2.193-.264 2.97-.567.802-.312 1.483-.73 2.162-1.41.68-.679 1.097-1.36 1.41-2.163.303-.777.507-1.667.567-2.97.058-1.279.072-1.688.072-4.947s-.014-3.668-.072-4.947c-.06-1.303-.264-2.193-.567-2.97-.312-.803-.73-1.484-1.41-2.163-.679-.68-1.36-1.097-2.163-1.41-.777-.303-1.667-.507-2.97-.567-1.28-.058-1.688-.072-4.948-.072zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.791-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.209-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
                    </svg>
                </a>

                <!-- Twitter/X -->
                <a href="https://twitter.com/intent/tweet?text={{ $shareTitle }}&url={{ $shareUrl }}" target="_blank"
                    class="w-10 h-10 flex items-center justify-center rounded-full bg-black text-white hover:bg-gray-800 transition-colors shadow-sm border border-white/10"
                    title="Bagikan ke X (Twitter)">
                    <svg class="w-5 h-5" fill="white" viewBox="0 0 24 24">
                        <path
                            d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z" />
                    </svg>
                </a>
            </div>

            <div class="prose prose-lg dark:prose-invert max-w-none">
                {!! $article->content !!}
            </div>

            <div class="mt-12 pt-8 border-t border-gray-100 dark:border-white/10">
                <a href="{{ route('artikel.index') }}" wire:navigate
                    class="inline-flex items-center text-primary dark:text-gray-300 font-bold hover:text-accent dark:hover:text-white transition-colors group">
                    <span
                        class="material-symbols-outlined mr-2 transition-transform group-hover:-translate-x-1">arrow_back</span>
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