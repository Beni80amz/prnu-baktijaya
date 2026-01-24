<div x-data="{ open: false, activeImage: '', activeTitle: '', activeType: '', activeVideo: '' }">
    <!-- Page Header -->
    <section class="bg-gradient-to-br from-primary to-primary-dark py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl md:text-5xl font-black text-white mb-4">Galeri</h1>
            <p class="text-white/80 text-lg max-w-2xl mx-auto">Dokumentasi kegiatan dan momen berharga PRNU Baktijaya
            </p>
        </div>
    </section>

    <!-- Filter -->
    <section
        class="bg-white dark:bg-background-dark border-b border-primary/10 dark:border-white/10 sticky top-20 z-30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex flex-wrap gap-2 justify-center">
                <button wire:click="$set('type', null)"
                    class="px-4 py-2 rounded-lg text-sm font-bold transition-all {{ !$type ? 'bg-primary text-white' : 'bg-gray-100 dark:bg-white/10 text-gray-600 dark:text-white/70 hover:bg-primary/10' }}">
                    Semua
                </button>
                <button wire:click="$set('type', 'photo')"
                    class="px-4 py-2 rounded-lg text-sm font-bold transition-all {{ $type == 'photo' ? 'bg-primary text-white' : 'bg-gray-100 dark:bg-white/10 text-gray-600 dark:text-white/70 hover:bg-primary/10' }}">
                    Foto
                </button>
                <button wire:click="$set('type', 'video')"
                    class="px-4 py-2 rounded-lg text-sm font-bold transition-all {{ $type == 'video' ? 'bg-primary text-white' : 'bg-gray-100 dark:bg-white/10 text-gray-600 dark:text-white/70 hover:bg-primary/10' }}">
                    Video
                </button>
            </div>
        </div>
    </section>

    <!-- Gallery Grid -->
    <section class="py-16 bg-background-light dark:bg-background-dark">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($galleries->isEmpty())
                <div class="text-center py-20">
                    <span class="material-symbols-outlined text-6xl text-gray-300 dark:text-white/20">photo_library</span>
                    <p class="text-gray-500 dark:text-white/50 mt-4 text-lg">Belum ada galeri yang dipublikasikan</p>
                </div>
            @else
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    @foreach($galleries as $gallery)
                        @php
                            $imageUrl = $gallery->display_image;
                            $videoUrl = '';
                            if (strtolower($gallery->type) === 'video' && $gallery->video_url) {
                                // Extract Video ID for Embed
                                if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=|live/)|youtu\.be/)([^"&?/ ]{11})%i', $gallery->video_url, $match)) {
                                    $videoUrl = 'https://www.youtube.com/embed/' . $match[1] . '?autoplay=1';
                                }
                            }
                        @endphp
                        <div class="group relative aspect-square rounded-xl overflow-hidden cursor-pointer"
                            @click="activeImage = '{{ $imageUrl }}'; activeTitle = '{{ addslashes($gallery->title) }}'; activeType = '{{ strtolower($gallery->type) }}'; activeVideo = '{{ $videoUrl }}'; open = true">
                            @if(strtolower($gallery->type) === 'video')
                                <div class="absolute inset-0 bg-black/50 flex items-center justify-center z-10">
                                    <span class="material-symbols-outlined text-5xl text-white">play_circle</span>
                                </div>
                            @endif
                            <img src="{{ $imageUrl }}" alt="{{ $gallery->title }}"
                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity">
                                <div class="absolute bottom-0 left-0 right-0 p-4">
                                    <h3 class="text-white font-bold text-sm line-clamp-2">{{ $gallery->title }}</h3>
                                    <p class="text-white/70 text-xs mt-1">{{ $gallery->created_at->format('d M Y') }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-12">{{ $galleries->links() }}</div>
            @endif
        </div>
    </section>

    <!-- Lightbox Modal -->
    <div x-show="open" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/90 backdrop-blur-sm"
        @keydown.escape.window="open = false; activeVideo = ''">

        <!-- Close Button -->
        <button @click="open = false; activeVideo = ''"
            class="absolute top-4 right-4 text-white hover:text-gray-300 z-50">
            <span class="material-symbols-outlined text-4xl">close</span>
        </button>

        <!-- Content Container -->
        <div class="relative max-w-7xl w-full max-h-screen p-2 flex flex-col items-center justify-center"
            @click.outside="open = false; activeVideo = ''">

            <template x-if="activeType === 'video' && activeVideo">
                <div class="w-full max-w-6xl rounded-lg shadow-2xl overflow-hidden bg-black relative"
                    style="padding-bottom: 56.25%;">
                    <iframe :src="activeVideo" class="absolute inset-0 w-full h-full" frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen></iframe>
                </div>
            </template>

            <template x-if="activeType !== 'video'">
                <img :src="activeImage" :alt="activeTitle"
                    class="max-w-6xl max-h-[85vh] object-contain rounded-lg shadow-2xl">
            </template>

            <h3 x-text="activeTitle" class="text-white font-bold text-lg mt-4 text-center"></h3>
        </div>
    </div>
</div>