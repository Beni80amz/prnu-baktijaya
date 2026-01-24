<x-layouts.app>
    <!-- Hero Section / Slider -->
    @if($sliders->count() > 0)
        <div class="relative bg-gray-900 h-[500px] flex items-center justify-center overflow-hidden">
            <!-- Placeholder for real slider implementation -->
            <img src="{{ Storage::url($sliders->first()->image) }}"
                class="absolute inset-0 w-full h-full object-cover type-white opacity-50" alt="Slider">
            <div class="relative z-10 text-center text-white px-4">
                <h1 class="text-4xl md:text-6xl font-bold mb-4">{{ $sliders->first()->title }}</h1>
                <p class="text-xl mb-8">{{ $sliders->first()->description }}</p>
                @if($sliders->first()->link_url)
                    <a href="{{ $sliders->first()->link_url }}"
                        class="px-8 py-3 bg-green-600 hover:bg-green-500 rounded-lg font-bold transition">{{ $sliders->first()->button_text ?? 'Selengkapnya' }}</a>
                @endif
            </div>
        </div>
    @else
        <div class="bg-green-800 text-white h-[400px] flex items-center justify-center">
            <div class="text-center">
                <h1 class="text-4xl font-bold mb-4">Selamat Datang di PRNU Baktijaya</h1>
                <p class="text-xl">Merawat Jagad, Membangun Peradaban</p>
            </div>
        </div>
    @endif

    <!-- Jadwal Sholat Widget Area -->
    <div class="bg-white py-8 shadow-sm">
        <div class="container mx-auto px-4">
            <div
                class="flex flex-col md:flex-row justify-between items-center bg-green-50 p-6 rounded-lg border border-green-100">
                <div class="mb-4 md:mb-0">
                    <h2 class="text-2xl font-bold text-green-800 flex items-center">
                        <span class="mr-2">⏰</span> Jadwal Sholat
                    </h2>
                    <p class="text-gray-600">Wilayah Baktijaya & Sekitarnya</p>
                </div>
                <!-- Placeholder for API Data -->
                <div class="grid grid-cols-5 gap-2 md:gap-6 text-center w-full md:w-auto">
                    <div class="bg-white p-2 rounded shadow-sm">
                        <span class="block text-xs text-gray-500 font-bold uppercase">Subuh</span>
                        <span class="block text-lg font-bold text-green-700">04:32</span>
                    </div>
                    <div class="bg-white p-2 rounded shadow-sm">
                        <span class="block text-xs text-gray-500 font-bold uppercase">Dzuhur</span>
                        <span class="block text-lg font-bold text-green-700">11:45</span>
                    </div>
                    <div class="bg-white p-2 rounded shadow-sm">
                        <span class="block text-xs text-gray-500 font-bold uppercase">Ashar</span>
                        <span class="block text-lg font-bold text-green-700">15:02</span>
                    </div>
                    <div class="bg-white p-2 rounded shadow-sm">
                        <span class="block text-xs text-gray-500 font-bold uppercase">Maghrib</span>
                        <span class="block text-lg font-bold text-green-700">18:15</span>
                    </div>
                    <div class="bg-white p-2 rounded shadow-sm">
                        <span class="block text-xs text-gray-500 font-bold uppercase">Isya</span>
                        <span class="block text-lg font-bold text-green-700">19:28</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Dawuh Ulama -->
    @if($dawuh)
        <div class="py-12 bg-white">
            <div class="container mx-auto px-4">
                <div class="max-w-4xl mx-auto text-center">
                    <span class="block text-green-600 font-bold mb-2 uppercase tracking-wider">Dawuh Ulama</span>
                    <blockquote class="text-2xl md:text-3xl font-serif text-gray-800 italic mb-6">
                        "{{ $dawuh->quote }}"
                    </blockquote>
                    @if($dawuh->quote_arabic)
                        <p class="text-2xl font-arabic text-green-700 mb-4 font-amiri" style="font-family: 'Amiri', serif;">
                            {{ $dawuh->quote_arabic }}
                        </p>
                    @endif
                    <cite class="not-italic font-bold text-gray-600 block">— {{ $dawuh->ulama_title }}
                        {{ $dawuh->ulama_name }}</cite>
                </div>
            </div>
        </div>
    @endif

    <!-- Berita Terbaru -->
    <div class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-gray-800 mb-8 border-l-4 border-green-600 pl-4">Berita Terbaru</h2>
            @if($news->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @foreach($news as $item)
                        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition duration-300">
                            @if($item->featured_image)
                                <img src="{{ Storage::url($item->featured_image) }}" alt="{{ $item->title }}"
                                    class="w-full h-48 object-cover">
                            @else
                                <div class="w-full h-48 bg-gray-200 flex items-center justify-center text-gray-400">No Image</div>
                            @endif
                            <div class="p-6">
                                <span
                                    class="text-xs font-bold text-green-600 uppercase mb-2 block">{{ $item->category->name ?? 'Berita' }}</span>
                                <h3 class="text-xl font-bold mb-2">
                                    <a href="#" class="text-gray-800 hover:text-green-700 transition">{{ $item->title }}</a>
                                </h3>
                                <p class="text-gray-600 mb-4 line-clamp-3">{{ $item->excerpt }}</p>
                                <div class="flex justify-between items-center text-sm text-gray-500">
                                    <span>{{ $item->published_at->format('d M Y') }}</span>
                                    <a href="#" class="text-green-600 font-semibold hover:underline">Baca Selengkapnya
                                        &rarr;</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-center text-gray-500 py-10">Belum ada berita terbaru.</p>
            @endif
        </div>
    </div>

    <!-- Layanan Cepat / Features Grid -->
    <div class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center text-gray-800 mb-12">Layanan Umat</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                <a href="#"
                    class="group p-6 rounded-xl border border-gray-100 shadow hover:shadow-lg transition text-center bg-white hover:bg-green-50">
                    <div
                        class="w-16 h-16 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl group-hover:bg-green-200 transition">
                        💬</div>
                    <h3 class="font-bold text-lg mb-2">Tanya Kiai</h3>
                    <p class="text-sm text-gray-500">Konsultasi masalah keagamaan</p>
                </a>
                <a href="#"
                    class="group p-6 rounded-xl border border-gray-100 shadow hover:shadow-lg transition text-center bg-white hover:bg-green-50">
                    <div
                        class="w-16 h-16 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl group-hover:bg-green-200 transition">
                        🧮</div>
                    <h3 class="font-bold text-lg mb-2">Kalkulator Zakat</h3>
                    <p class="text-sm text-gray-500">Hitung zakat maal & fitrah</p>
                </a>
                <a href="#"
                    class="group p-6 rounded-xl border border-gray-100 shadow hover:shadow-lg transition text-center bg-white hover:bg-green-50">
                    <div
                        class="w-16 h-16 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl group-hover:bg-green-200 transition">
                        🤲</div>
                    <h3 class="font-bold text-lg mb-2">Doa & Tahlil</h3>
                    <p class="text-sm text-gray-500">Request doa untuk arwah</p>
                </a>
                <a href="#"
                    class="group p-6 rounded-xl border border-gray-100 shadow hover:shadow-lg transition text-center bg-white hover:bg-green-50">
                    <div
                        class="w-16 h-16 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl group-hover:bg-green-200 transition">
                        🛍️</div>
                    <h3 class="font-bold text-lg mb-2">UMKM Warga</h3>
                    <p class="text-sm text-gray-500">Dukung ekonomi jamaah</p>
                </a>
            </div>
        </div>
    </div>
</x-layouts.app>