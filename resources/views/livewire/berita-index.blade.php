<div>
    <!-- Page Header -->
    <section class="bg-gradient-to-br from-primary to-primary-dark py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl md:text-5xl font-black text-white mb-4">Berita Ranting</h1>
            <p class="text-white/80 text-lg max-w-2xl mx-auto">Informasi terkini seputar kegiatan dan perkembangan PRNU
                Baktijaya</p>
        </div>
    </section>

    <!-- Filter & Search -->
    <section
        class="bg-white dark:bg-background-dark border-b border-primary/10 dark:border-white/10 sticky top-20 z-30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex flex-col md:flex-row gap-4 items-center justify-between">
                <!-- Search -->
                <div class="relative w-full md:w-96">
                    <span
                        class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">search</span>
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari berita..."
                        class="w-full pl-10 pr-4 py-3 rounded-xl border border-primary/10 dark:border-white/10 bg-gray-50 dark:bg-white/5 text-background-dark dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent">
                </div>
                <!-- Category Filter -->
                <div class="flex flex-wrap gap-2">
                    <button wire:click="$set('categoryId', null)"
                        class="px-4 py-2 rounded-lg text-sm font-bold transition-all {{ !$categoryId ? 'bg-primary text-white' : 'bg-gray-100 dark:bg-white/10 text-gray-600 dark:text-white/70 hover:bg-primary/10' }}">
                        Semua
                    </button>
                    @foreach($categories as $category)
                        <button wire:click="$set('categoryId', {{ $category->id }})"
                            class="px-4 py-2 rounded-lg text-sm font-bold transition-all {{ $categoryId == $category->id ? 'bg-primary text-white' : 'bg-gray-100 dark:bg-white/10 text-gray-600 dark:text-white/70 hover:bg-primary/10' }}">
                            {{ $category->name }}
                        </button>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <!-- News Grid -->
    <section class="py-16 bg-background-light dark:bg-background-dark">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($news->isEmpty())
                <div class="text-center py-20">
                    <span class="material-symbols-outlined text-6xl text-gray-300 dark:text-white/20">article</span>
                    <p class="text-gray-500 dark:text-white/50 mt-4 text-lg">Belum ada berita yang dipublikasikan</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($news as $item)
                        <article
                            class="group bg-white dark:bg-white/5 rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 border border-primary/5 dark:border-white/5">
                            <!-- Image -->
                            <a href="{{ route('berita.show', $item->slug) }}" wire:navigate
                                class="block relative h-52 overflow-hidden">
                                @if($item->featured_image)
                                    <img src="{{ Storage::url($item->featured_image) }}" alt="{{ $item->title }}"
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                @else
                                    <div
                                        class="w-full h-full bg-gradient-to-br from-primary/20 to-primary/10 flex items-center justify-center">
                                        <span class="material-symbols-outlined text-5xl text-primary/40">image</span>
                                    </div>
                                @endif
                                @if($item->category)
                                    <span
                                        class="absolute top-4 left-4 px-3 py-1 bg-primary text-white text-xs font-bold rounded-full">
                                        {{ $item->category->name }}
                                    </span>
                                @endif
                            </a>
                            <!-- Content -->
                            <div class="p-6">
                                <div class="flex items-center gap-3 text-xs text-gray-500 dark:text-white/50 mb-3">
                                    <span class="flex items-center gap-1">
                                        <span class="material-symbols-outlined text-sm">calendar_today</span>
                                        {{ $item->published_at?->format('d M Y') ?? $item->created_at->format('d M Y') }}
                                    </span>
                                    <span class="flex items-center gap-1">
                                        <span class="material-symbols-outlined text-sm">visibility</span>
                                        {{ number_format($item->views ?? 0) }}x
                                    </span>
                                </div>
                                <h3
                                    class="text-lg font-bold text-background-dark dark:text-white mb-2 line-clamp-2 group-hover:text-primary transition-colors">
                                    <a href="{{ route('berita.show', $item->slug) }}" wire:navigate>{{ $item->title }}</a>
                                </h3>
                                <p class="text-gray-600 dark:text-white/60 text-sm line-clamp-3 mb-4">
                                    {{ $item->excerpt }}
                                </p>
                                <a href="{{ route('berita.show', $item->slug) }}" wire:navigate
                                    class="inline-flex items-center text-primary font-bold text-sm hover:text-accent transition-colors">
                                    Baca Selengkapnya
                                    <span
                                        class="material-symbols-outlined text-lg ml-1 group-hover:translate-x-1 transition-transform">arrow_forward</span>
                                </a>
                            </div>
                        </article>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-12">
                    {{ $news->links() }}
                </div>
            @endif
        </div>
    </section>
</div>