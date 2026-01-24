<div>
    <!-- Page Header -->
    <section class="bg-gradient-to-br from-primary to-primary-dark py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl md:text-5xl font-black text-white mb-4">Artikel & Opini</h1>
            <p class="text-white/80 text-lg max-w-2xl mx-auto">Kajian, khutbah, dan tulisan Islami dari para ulama dan
                kontributor PRNU Baktijaya</p>
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
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari artikel..."
                        class="w-full pl-10 pr-4 py-3 rounded-xl border border-primary/10 dark:border-white/10 bg-gray-50 dark:bg-white/5 text-background-dark dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent">
                </div>
                <!-- Type Filter -->
                <div class="flex flex-wrap gap-2">
                    <button wire:click="$set('type', null)"
                        class="px-4 py-2 rounded-lg text-sm font-bold transition-all {{ !$type ? 'bg-primary text-white' : 'bg-gray-100 dark:bg-white/10 text-gray-600 dark:text-white/70 hover:bg-primary/10' }}">
                        Semua
                    </button>
                    <button wire:click="$set('type', 'opini')"
                        class="px-4 py-2 rounded-lg text-sm font-bold transition-all {{ $type == 'opini' ? 'bg-primary text-white' : 'bg-gray-100 dark:bg-white/10 text-gray-600 dark:text-white/70 hover:bg-primary/10' }}">
                        Opini
                    </button>
                    <button wire:click="$set('type', 'khutbah')"
                        class="px-4 py-2 rounded-lg text-sm font-bold transition-all {{ $type == 'khutbah' ? 'bg-primary text-white' : 'bg-gray-100 dark:bg-white/10 text-gray-600 dark:text-white/70 hover:bg-primary/10' }}">
                        Khutbah
                    </button>
                    <button wire:click="$set('type', 'kajian')"
                        class="px-4 py-2 rounded-lg text-sm font-bold transition-all {{ $type == 'kajian' ? 'bg-primary text-white' : 'bg-gray-100 dark:bg-white/10 text-gray-600 dark:text-white/70 hover:bg-primary/10' }}">
                        Kajian
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Articles Grid -->
    <section class="py-16 bg-background-light dark:bg-background-dark">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($articles->isEmpty())
                <div class="text-center py-20">
                    <span class="material-symbols-outlined text-6xl text-gray-300 dark:text-white/20">menu_book</span>
                    <p class="text-gray-500 dark:text-white/50 mt-4 text-lg">Belum ada artikel yang dipublikasikan</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($articles as $article)
                        <article
                            class="group bg-white dark:bg-white/5 rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 border border-primary/5 dark:border-white/5">
                            <a href="{{ route('artikel.show', $article->slug) }}" wire:navigate
                                class="block relative h-52 overflow-hidden">
                                @if($article->featured_image)
                                    <img src="{{ Storage::url($article->featured_image) }}" alt="{{ $article->title }}"
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                @else
                                    <div
                                        class="w-full h-full bg-gradient-to-br from-accent/20 to-accent/10 flex items-center justify-center">
                                        <span class="material-symbols-outlined text-5xl text-accent/40">menu_book</span>
                                    </div>
                                @endif
                                @if($article->type)
                                    <span
                                        class="absolute top-4 left-4 px-3 py-1 bg-accent text-white text-xs font-bold rounded-full uppercase">
                                        {{ $article->type }}
                                    </span>
                                @endif
                            </a>
                            <div class="p-6">
                                <div class="flex items-center gap-3 text-xs text-gray-500 dark:text-white/50 mb-3">
                                    <span class="flex items-center gap-1">
                                        <span class="material-symbols-outlined text-sm">person</span>
                                        {{ $article->author_name ?? $article->author?->name ?? 'Anonim' }}
                                    </span>
                                    <span class="flex items-center gap-1">
                                        <span class="material-symbols-outlined text-sm">calendar_today</span>
                                        {{ $article->published_at?->format('d M Y') }}
                                    </span>
                                </div>
                                <h3
                                    class="text-lg font-bold text-background-dark dark:text-white mb-2 line-clamp-2 group-hover:text-primary transition-colors">
                                    <a href="{{ route('artikel.show', $article->slug) }}"
                                        wire:navigate>{{ $article->title }}</a>
                                </h3>
                                <p class="text-gray-600 dark:text-white/60 text-sm line-clamp-3">{{ $article->excerpt }}</p>
                            </div>
                        </article>
                    @endforeach
                </div>
                <div class="mt-12">{{ $articles->links() }}</div>
            @endif
        </div>
    </section>
</div>