<div>
    <!-- Page Header -->
    <section class="bg-gradient-to-br from-primary to-primary-dark py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl md:text-5xl font-black text-white mb-4">UMKM Jamaah</h1>
            <p class="text-white/80 text-lg max-w-2xl mx-auto">Dukung produk dan jasa dari warga Nahdliyin Baktijaya</p>
        </div>
    </section>

    <!-- Search & Filter -->
    <section
        class="bg-white dark:bg-background-dark border-b border-primary/10 dark:border-white/10 sticky top-20 z-30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex flex-col md:flex-row gap-4 items-center justify-between">
                <div class="relative w-full md:w-96">
                    <span
                        class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">search</span>
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari UMKM..."
                        class="w-full pl-10 pr-4 py-3 rounded-xl border border-primary/10 dark:border-white/10 bg-gray-50 dark:bg-white/5 text-background-dark dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent">
                </div>
                <div class="flex flex-wrap gap-2">
                    <button wire:click="$set('category', null)"
                        class="px-4 py-2 rounded-lg text-sm font-bold transition-all {{ !$category ? 'bg-primary text-white' : 'bg-gray-100 dark:bg-white/10 text-gray-600 dark:text-white/70 hover:bg-primary/10' }}">
                        Semua
                    </button>
                    @foreach($categories as $cat)
                        <button wire:click="$set('category', '{{ $cat }}')"
                            class="px-4 py-2 rounded-lg text-sm font-bold transition-all {{ $category == $cat ? 'bg-primary text-white' : 'bg-gray-100 dark:bg-white/10 text-gray-600 dark:text-white/70 hover:bg-primary/10' }}">
                            {{ ucfirst($cat) }}
                        </button>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <!-- UMKM Grid -->
    <section class="py-16 bg-background-light dark:bg-background-dark">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($umkms->isEmpty())
                <div class="text-center py-20">
                    <span class="material-symbols-outlined text-6xl text-gray-300 dark:text-white/20">storefront</span>
                    <p class="text-gray-500 dark:text-white/50 mt-4 text-lg">Belum ada UMKM yang terdaftar</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach($umkms as $umkm)
                        <div
                            class="group bg-white dark:bg-white/5 rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 border border-primary/5 dark:border-white/5">
                            <div class="relative h-48 overflow-hidden">
                                @if($umkm->image)
                                    <img src="{{ Storage::url($umkm->image) }}" alt="{{ $umkm->name }}"
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                @else
                                    <div
                                        class="w-full h-full bg-gradient-to-br from-primary/10 to-accent/10 flex items-center justify-center">
                                        <span class="material-symbols-outlined text-5xl text-primary/40">storefront</span>
                                    </div>
                                @endif
                                @if($umkm->category)
                                    <span
                                        class="absolute top-3 left-3 px-3 py-1 bg-accent text-white text-xs font-bold rounded-full">
                                        {{ $umkm->category }}
                                    </span>
                                @endif
                            </div>
                            <div class="p-5">
                                <h3 class="font-bold text-lg text-background-dark dark:text-white mb-2">{{ $umkm->name }}</h3>
                                <p class="text-gray-600 dark:text-white/60 text-sm line-clamp-2 mb-4">{{ $umkm->description }}
                                </p>
                                <div class="flex items-center justify-between">
                                    <p class="text-xs text-gray-500 dark:text-white/50">
                                        <span class="material-symbols-outlined text-sm align-middle">person</span>
                                        {{ $umkm->owner_name }}
                                    </p>
                                    @if($umkm->whatsapp)
                                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $umkm->whatsapp) }}" target="_blank"
                                            class="inline-flex items-center gap-1 px-3 py-1.5 bg-green-500 hover:bg-green-600 text-white text-xs font-bold rounded-lg transition-colors">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                                            </svg>
                                            Hubungi
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-12">{{ $umkms->links() }}</div>
            @endif
        </div>
    </section>
</div>