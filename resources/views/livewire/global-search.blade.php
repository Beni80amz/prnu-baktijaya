<div class="w-full">
    <!-- Search Header -->
    <div class="relative border-b border-gray-50 dark:border-white/5 p-4">
        <div class="flex items-center gap-4">
            <span class="material-symbols-outlined text-gray-300 text-3xl">search</span>
            <input wire:model.live.debounce.300ms="search" 
                type="text" 
                class="w-full bg-transparent border-none focus:ring-0 text-xl font-medium text-gray-900 dark:text-white placeholder-gray-300 p-0"
                placeholder="Cari sesuatu..." 
                autofocus>
            <div wire:loading class="flex-shrink-0">
                <svg class="animate-spin h-6 w-6 text-primary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Search Results / State -->
    <div class="max-h-[60vh] overflow-y-auto custom-scrollbar bg-gray-50/50 dark:bg-black/20">
        @if(strlen($search) >= 2)
            @if(empty($results['articles']) && empty($results['news']) && empty($results['umkm']))
                <div class="flex flex-col items-center justify-center py-16 text-center">
                    <div class="w-20 h-20 bg-gray-100 dark:bg-white/5 rounded-full flex items-center justify-center mb-4">
                        <span class="material-symbols-outlined text-4xl text-gray-400">search_off</span>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">Tidak ada hasil ditemukan</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">Coba kata kunci lain atau periksa ejaan Anda.</p>
                </div>
            @else
                <div class="p-2 space-y-6">
                    {{-- Berita --}}
                    @if(isset($results['news']) && $results['news']->count() > 0)
                        <div>
                            <div class="flex items-center gap-2 px-3 py-2 mb-1">
                                <span class="material-symbols-outlined text-lg text-primary">newspaper</span>
                                <h3 class="text-xs font-bold text-gray-500 uppercase tracking-widest">Berita</h3>
                            </div>
                            <div class="space-y-1">
                                @foreach($results['news'] as $item)
                                    <a href="{{ route('berita.show', $item->slug) }}" wire:navigate 
                                       class="flex items-start gap-4 p-3 rounded-xl hover:bg-white dark:hover:bg-white/5 transition-all group border border-transparent hover:border-gray-100 dark:hover:border-white/5 shadow-sm hover:shadow-md">
                                        @if($item->featured_image)
                                            <div class="w-16 h-16 rounded-lg overflow-hidden flex-shrink-0 bg-gray-200 shadow-inner">
                                                <img src="{{ asset('storage/' . $item->featured_image) }}" alt="{{ $item->title }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                            </div>
                                        @else
                                            <div class="w-16 h-16 rounded-lg overflow-hidden flex-shrink-0 bg-primary/10 flex items-center justify-center">
                                                <span class="material-symbols-outlined text-primary text-2xl">article</span>
                                            </div>
                                        @endif
                                        <div class="flex-1 min-w-0 py-0.5">
                                            <h4 class="text-sm font-bold text-gray-900 dark:text-white truncate group-hover:text-primary transition-colors leading-tight mb-1">
                                                {{ $item->title }}
                                            </h4>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 line-clamp-2">
                                                {{ $item->excerpt ?? Str::limit(strip_tags($item->content), 80) }}
                                            </p>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Artikel --}}
                    @if(isset($results['articles']) && $results['articles']->count() > 0)
                        <div>
                            <div class="flex items-center gap-2 px-3 py-2 mb-1">
                                <span class="material-symbols-outlined text-lg text-primary">edit_note</span>
                                <h3 class="text-xs font-bold text-gray-500 uppercase tracking-widest">Artikel</h3>
                            </div>
                            <div class="space-y-1">
                                @foreach($results['articles'] as $item)
                                    <a href="{{ route('artikel.show', $item->slug) }}" wire:navigate 
                                       class="flex items-start gap-4 p-3 rounded-xl hover:bg-white dark:hover:bg-white/5 transition-all group border border-transparent hover:border-gray-100 dark:hover:border-white/5 shadow-sm hover:shadow-md">
                                        @if($item->featured_image)
                                            <div class="w-16 h-16 rounded-lg overflow-hidden flex-shrink-0 bg-gray-200 shadow-inner">
                                                <img src="{{ asset('storage/' . $item->featured_image) }}" alt="{{ $item->title }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                            </div>
                                        @else
                                            <div class="w-16 h-16 rounded-lg overflow-hidden flex-shrink-0 bg-primary/10 flex items-center justify-center">
                                                <span class="material-symbols-outlined text-primary text-2xl">feed</span>
                                            </div>
                                        @endif
                                        <div class="flex-1 min-w-0 py-0.5">
                                            <h4 class="text-sm font-bold text-gray-900 dark:text-white truncate group-hover:text-primary transition-colors leading-tight mb-1">
                                                {{ $item->title }}
                                            </h4>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 line-clamp-2">
                                                {{ $item->excerpt ?? Str::limit(strip_tags($item->content), 80) }}
                                            </p>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- UMKM --}}
                    @if(isset($results['umkm']) && $results['umkm']->count() > 0)
                        <div>
                            <div class="flex items-center gap-2 px-3 py-2 mb-1">
                                <span class="material-symbols-outlined text-lg text-primary">storefront</span>
                                <h3 class="text-xs font-bold text-gray-500 uppercase tracking-widest">UMKM</h3>
                            </div>
                            <div class="space-y-1">
                                @foreach($results['umkm'] as $item)
                                    <a href="{{ route('umkm.index') }}" wire:navigate 
                                       class="flex items-start gap-4 p-3 rounded-xl hover:bg-white dark:hover:bg-white/5 transition-all group border border-transparent hover:border-gray-100 dark:hover:border-white/5 shadow-sm hover:shadow-md">
                                        <div class="w-16 h-16 rounded-lg overflow-hidden flex-shrink-0 bg-gray-200 shadow-inner flex items-center justify-center text-gray-400 bg-cover bg-center"
                                             style="{{ $item->featured_image ? 'background-image: url('.asset('storage/' . $item->featured_image).');' : '' }}">
                                             @if(!$item->featured_image)
                                                <span class="material-symbols-outlined">store</span>
                                             @endif
                                        </div>
                                        <div class="flex-1 min-w-0 py-0.5">
                                            <h4 class="text-sm font-bold text-gray-900 dark:text-white truncate group-hover:text-primary transition-colors leading-tight mb-1">
                                                {{ $item->name }}
                                            </h4>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
                                                {{ Str::limit($item->description, 60) }}
                                            </p>
                                            <div class="mt-1 flex items-center gap-1 text-[10px] text-primary bg-primary/10 px-2 py-0.5 rounded-full w-fit">
                                                <span class="material-symbols-outlined text-[10px]">grid_view</span>
                                                {{ $item->category ?? 'Umum' }}
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            @endif
        @else
            <div class="py-16 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-primary/10 mb-4 animate-pulse">
                    <span class="material-symbols-outlined text-3xl text-primary">search</span>
                </div>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Mau cari apa hari ini?</h3>
                <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Ketik kata kunci untuk mencari Artikel, Berita, atau Produk UMKM.</p>
                
                <div class="mt-8 flex flex-wrap justify-center gap-2">
                    <button wire:click="$set('search', 'Pengajian')" class="px-3 py-1 rounded-full bg-gray-100 dark:bg-white/5 text-xs text-gray-600 dark:text-gray-300 hover:bg-primary hover:text-white transition-colors">Pengajian</button>
                    <button wire:click="$set('search', 'Beasiswa')" class="px-3 py-1 rounded-full bg-gray-100 dark:bg-white/5 text-xs text-gray-600 dark:text-gray-300 hover:bg-primary hover:text-white transition-colors">Beasiswa</button>
                    <button wire:click="$set('search', 'Agenda')" class="px-3 py-1 rounded-full bg-gray-100 dark:bg-white/5 text-xs text-gray-600 dark:text-gray-300 hover:bg-primary hover:text-white transition-colors">Agenda</button>
                    <button wire:click="$set('search', 'Lazisnu')" class="px-3 py-1 rounded-full bg-gray-100 dark:bg-white/5 text-xs text-gray-600 dark:text-gray-300 hover:bg-primary hover:text-white transition-colors">Lazisnu</button>
                </div>
            </div>
        @endif
    </div>

    <!-- Footer -->
    <div class="bg-gray-50 dark:bg-white/5 px-4 py-3 flex items-center justify-between text-xs text-gray-500 dark:text-gray-400 border-t border-gray-100 dark:border-white/10">
        <div class="flex items-center gap-4">
            <span class="flex items-center gap-1"><kbd class="font-sans px-1.5 py-0.5 rounded border border-gray-300 dark:border-white/20 bg-white dark:bg-black/20">esc</kbd> tutup</span>
            <span class="hidden sm:flex items-center gap-1"><kbd class="font-sans px-1.5 py-0.5 rounded border border-gray-300 dark:border-white/20 bg-white dark:bg-black/20">enter</kbd> pilih</span>
        </div>
        <span>PRNU Baktijaya</span>
    </div>
</div>