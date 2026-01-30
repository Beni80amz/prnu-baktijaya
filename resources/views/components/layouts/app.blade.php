<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'PRNU Baktijaya - Merawat Jagad, Membangun Peradaban' }}</title>

    @php
        $settings = \App\Models\Setting::all()->pluck('value', 'key')->toArray();
    @endphp

    @if(!empty($settings['site_favicon']))
        <link rel="icon" type="image/png" href="{{ asset('storage/' . $settings['site_favicon']) }}">
    @endif

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200"
        rel="stylesheet">

    <!-- Leaflet Maps -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <!-- Styles & Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <script>
        // Immediately check theme to prevent flash
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }

        // Define toggle function globally
        window.toggleTheme = function () {
            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('theme', 'light');
            } else {
                document.documentElement.classList.add('dark');
                localStorage.setItem('theme', 'dark');
            }
            window.updateIcons();
        }

        // Define update icons function globally
        window.updateIcons = function () {
            const isDark = document.documentElement.classList.contains('dark');
            const themeIcon = document.getElementById('theme-icon');

            if (themeIcon) {
                // Text-based icon toggle (Material Symbols)
                themeIcon.textContent = isDark ? 'light_mode' : 'dark_mode';
            }
        }

        // Listeners
        document.addEventListener('DOMContentLoaded', window.updateIcons);
        document.addEventListener('livewire:navigated', window.updateIcons); 
    </script>
</head>

<body
    class="bg-background-light dark:bg-background-dark text-background-dark dark:text-white font-display overflow-x-hidden selection:bg-primary selection:text-white">

    <!-- Navbar -->
    <nav x-data="{ mobileMenuOpen: false }"
        class="sticky top-0 z-50 border-b border-primary/10 dark:border-white/10 bg-white/95 dark:bg-background-dark/95 backdrop-blur-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex items-center gap-4">
                    <a href="{{ route('home') }}" wire:navigate
                        class="flex items-center gap-3 text-primary dark:text-white">
                        <div
                            class="size-10 rounded-full flex items-center justify-center overflow-hidden {{ empty($settings['site_logo']) ? 'bg-primary/10 dark:bg-white/10' : '' }}">
                            @if(!empty($settings['site_logo']))
                                <img src="{{ asset('storage/' . $settings['site_logo']) }}" alt="Logo"
                                    class="w-full h-full object-cover">
                            @else
                                <span class="material-symbols-outlined text-primary text-2xl">mosque</span>
                            @endif
                        </div>
                        <div class="hidden md:block">
                            <h2 class="text-xl font-bold leading-none tracking-tight">PRNU Baktijaya</h2>
                            <p class="text-[10px] text-yellow-500 font-semibold uppercase tracking-wider mt-1"
                                style="color: #EAB308;">
                                NU Ranting Baktijaya</p>
                        </div>
                    </a>
                </div>

                <div class="hidden lg:flex items-center gap-8">
                    <a class="text-sm font-semibold {{ request()->routeIs('home') ? 'text-primary' : '' }} hover:text-primary transition-colors"
                        href="{{ route('home') }}" wire:navigate>Beranda</a>
                    <a class="text-sm font-medium {{ request()->routeIs('profil') ? 'text-primary font-semibold' : '' }} hover:text-primary transition-colors"
                        href="{{ route('profil') }}" wire:navigate>Profil</a>
                    <a class="text-sm font-medium {{ request()->routeIs('berita.*') ? 'text-primary font-semibold' : '' }} hover:text-primary transition-colors"
                        href="{{ route('berita.index') }}" wire:navigate>Berita</a>
                    <a class="text-sm font-medium {{ request()->routeIs('artikel.*') ? 'text-primary font-semibold' : '' }} hover:text-primary transition-colors"
                        href="{{ route('artikel.index') }}" wire:navigate>Artikel</a>
                    <a class="text-sm font-medium {{ request()->routeIs('kas-digital') ? 'text-primary font-semibold' : '' }} hover:text-primary transition-colors"
                        href="{{ route('kas-digital') }}" wire:navigate>KAS Digital</a>
                    <a class="text-sm font-medium {{ request()->routeIs('umkm.*') ? 'text-primary font-semibold' : '' }} hover:text-primary transition-colors"
                        href="{{ route('umkm.index') }}" wire:navigate>UMKM</a>

                    @php
                        $channelId = \App\Models\Setting::getValue('youtube_channel_id');
                        $isLive = false;
                        if ($channelId) {
                            $cacheKey = 'youtube_live_status_' . $channelId;
                            $cachedStatus = \Illuminate\Support\Facades\Cache::get($cacheKey);
                            if ($cachedStatus && isset($cachedStatus['is_live']) && $cachedStatus['is_live']) {
                                $isLive = true;
                            }
                        }
                        $youtubeLink = \App\Models\Setting::getValue('social_youtube', '#');
                    @endphp

                    @if($isLive)
                        <a class="text-sm font-medium {{ request()->routeIs('live-streaming') ? 'text-primary font-semibold' : '' }} hover:text-primary transition-colors animate-pulse text-red-500 font-bold"
                            href="{{ route('live-streaming') }}" wire:navigate>LIVE</a>
                    @else
                        <a class="text-sm font-medium hover:text-primary transition-colors"
                            href="{{ $youtubeLink }}" target="_blank">Youtube</a>
                    @endif

                </div>

                <div class="flex items-center gap-4">
                    <!-- Theme Toggle -->
                    <button id="theme-toggle-btn" onclick="toggleTheme()"
                        class="text-primary/70 hover:text-primary transition-colors p-2 rounded-full hover:bg-primary/5 dark:hover:bg-white/5 cursor-pointer flex items-center justify-center z-50 relative">
                        <span id="theme-icon" class="material-symbols-outlined">dark_mode</span>
                    </button>

                    <button @click="$dispatch('open-search')"
                        class="text-primary/70 hover:text-primary transition-colors p-2 rounded-full hover:bg-primary/5 dark:hover:bg-white/5">
                        <span class="material-symbols-outlined">search</span>
                    </button>

                    @auth
                        <a href="{{ url('/admin') }}"
                            class="hidden sm:flex items-center gap-2 bg-primary hover:bg-primary-dark text-white text-sm font-bold py-2.5 px-5 rounded-lg transition-all shadow-md shadow-primary/20">
                            <span class="material-symbols-outlined text-[20px]">dashboard</span>
                            <span>Dashboard</span>
                        </a>
                    @else
                        <a href="{{ url('/admin/login') }}"
                            class="hidden sm:flex items-center gap-2 bg-transparent border border-primary hover:bg-primary text-primary hover:text-white dark:border-white/30 dark:text-white dark:hover:bg-white dark:hover:text-background-dark text-sm font-bold py-2.5 px-5 rounded-lg transition-all">
                            <span class="material-symbols-outlined text-[20px]">login</span>
                            <span>Login</span>
                        </a>
                    @endauth

                    <button @click="$dispatch('open-donation')"
                        class="hidden sm:flex items-center gap-2 bg-primary hover:bg-primary-dark text-white text-sm font-bold py-2.5 px-5 rounded-lg transition-all shadow-md shadow-primary/20 cursor-pointer">
                        <span class="material-symbols-outlined text-[20px]">volunteer_activism</span>
                        <span>Donasi</span>
                    </button>

                    <!-- Mobile Menu Button -->
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="lg:hidden text-primary p-2">
                        <span class="material-symbols-outlined" x-text="mobileMenuOpen ? 'close' : 'menu'">menu</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div x-show="mobileMenuOpen" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-2"
            class="lg:hidden bg-white dark:bg-background-dark border-t border-primary/10 dark:border-white/10 shadow-lg absolute w-full left-0 z-40"
            style="display: none;">
            <div class="px-4 pt-2 pb-6 space-y-2">
                <a class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('home') ? 'bg-primary/10 text-primary' : 'text-gray-700 dark:text-gray-200 hover:bg-primary/5 hover:text-primary' }} transition-colors"
                    href="{{ route('home') }}" wire:navigate @click="mobileMenuOpen = false">Beranda</a>
                <a class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('profil') ? 'bg-primary/10 text-primary' : 'text-gray-700 dark:text-gray-200 hover:bg-primary/5 hover:text-primary' }} transition-colors"
                    href="{{ route('profil') }}" wire:navigate @click="mobileMenuOpen = false">Profil</a>
                <a class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('berita.*') ? 'bg-primary/10 text-primary' : 'text-gray-700 dark:text-gray-200 hover:bg-primary/5 hover:text-primary' }} transition-colors"
                    href="{{ route('berita.index') }}" wire:navigate @click="mobileMenuOpen = false">Berita</a>
                <a class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('artikel.*') ? 'bg-primary/10 text-primary' : 'text-gray-700 dark:text-gray-200 hover:bg-primary/5 hover:text-primary' }} transition-colors"
                    href="{{ route('artikel.index') }}" wire:navigate @click="mobileMenuOpen = false">Artikel</a>
                <a class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('kas-digital') ? 'bg-primary/10 text-primary' : 'text-gray-700 dark:text-gray-200 hover:bg-primary/5 hover:text-primary' }} transition-colors"
                    href="{{ route('kas-digital') }}" wire:navigate @click="mobileMenuOpen = false">KAS Digital</a>
                <a class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('umkm.*') ? 'bg-primary/10 text-primary' : 'text-gray-700 dark:text-gray-200 hover:bg-primary/5 hover:text-primary' }} transition-colors"
                    href="{{ route('umkm.index') }}" wire:navigate @click="mobileMenuOpen = false">UMKM</a>

                @if($isLive)
                    <a class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('live-streaming') ? 'bg-primary/10 text-primary' : 'text-gray-700 dark:text-gray-200 hover:bg-primary/5 hover:text-primary' }} transition-colors animate-pulse text-red-500 font-bold"
                        href="{{ route('live-streaming') }}" wire:navigate @click="mobileMenuOpen = false">LIVE</a>
                @else
                    <a class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 dark:text-gray-200 hover:bg-primary/5 hover:text-primary transition-colors"
                        href="{{ $youtubeLink }}" target="_blank" @click="mobileMenuOpen = false">Youtube</a>
                @endif

                <div class="border-t border-gray-100 dark:border-gray-800 my-2 pt-2">
                    @auth
                        <a href="{{ url('/admin') }}"
                            class="flex items-center gap-2 px-3 py-2 rounded-md text-base font-medium text-gray-700 dark:text-gray-200 hover:bg-primary/5 hover:text-primary transition-colors">
                            <span class="material-symbols-outlined text-[20px]">dashboard</span>
                            <span>Dashboard</span>
                        </a>
                    @else
                        <a href="{{ url('/admin/login') }}"
                            class="flex items-center gap-2 px-3 py-2 rounded-md text-base font-medium text-gray-700 dark:text-gray-200 hover:bg-primary/5 hover:text-primary transition-colors">
                            <span class="material-symbols-outlined text-[20px]">login</span>
                            <span>Login</span>
                        </a>
                    @endauth

                    <a href="#" @click.prevent="$dispatch('open-donation'); mobileMenuOpen = false"
                        class="flex items-center gap-2 px-3 py-2 rounded-md text-base font-medium text-gray-700 dark:text-gray-200 hover:bg-primary/5 hover:text-primary transition-colors cursor-pointer">
                        <span class="material-symbols-outlined text-[20px]">volunteer_activism</span>
                        <span>Donasi</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content Slot -->
    {{ $slot }}

    <!-- Footer -->
    <footer class="bg-background-dark text-gray-400 pt-24 pb-12 border-t border-white/5">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-16 mb-20">
                <div class="col-span-1 md:col-span-1">
                    <div class="flex items-center gap-3 text-white mb-8">
                        <div
                            class="size-10 rounded-full flex items-center justify-center overflow-hidden shadow-lg shadow-primary/20 {{ empty($settings['site_logo']) ? 'bg-primary' : '' }}">
                            @if(!empty($settings['site_logo']))
                                <img src="{{ asset('storage/' . $settings['site_logo']) }}" alt="Logo"
                                    class="w-full h-full object-cover">
                            @else
                                <span class="material-symbols-outlined text-white text-2xl">mosque</span>
                            @endif
                        </div>
                        <h3 class="text-xl font-black tracking-tight">{{ $settings['site_name'] ?? 'PRNU Baktijaya' }}
                        </h3>
                    </div>
                    <p class="text-sm leading-relaxed mb-8 font-medium">
                        {{ $settings['site_description'] ?? 'Mewujudkan masyarakat Baktijaya yang religius, toleran, dan sejahtera melalui pengamalan nilai-nilai Ahlussunnah wal Jamaah an-Nahdliyah.' }}
                    </p>
                    <div class="flex gap-4">
                        @if(!empty($settings['social_facebook']))
                            <a class="size-10 bg-white/5 rounded-lg flex items-center justify-center text-primary hover:bg-primary hover:text-white transition-all"
                                href="{{ $settings['social_facebook'] }}" target="_blank" rel="noopener noreferrer"><span
                                    class="material-symbols-outlined">public</span></a>
                        @endif
                        @if(!empty($settings['social_instagram']))
                            <a class="size-10 bg-white/5 rounded-lg flex items-center justify-center text-primary hover:bg-primary hover:text-white transition-all"
                                href="{{ $settings['social_instagram'] }}" target="_blank" rel="noopener noreferrer"><span
                                    class="material-symbols-outlined">share</span></a>
                        @endif
                        @if(!empty($settings['social_youtube']))
                            <a class="size-10 bg-white/5 rounded-lg flex items-center justify-center text-primary hover:bg-primary hover:text-white transition-all"
                                href="{{ $settings['social_youtube'] }}" target="_blank" rel="noopener noreferrer"><span
                                    class="material-symbols-outlined">smart_display</span></a>
                        @endif
                        @if(!empty($settings['contact_email']))
                            <a class="size-10 bg-white/5 rounded-lg flex items-center justify-center text-primary hover:bg-primary hover:text-white transition-all"
                                href="mailto:{{ $settings['contact_email'] }}"><span
                                    class="material-symbols-outlined">mail</span></a>
                        @endif
                    </div>
                </div>
                <div>
                    <h4 class="text-white font-black text-sm uppercase tracking-widest mb-8">Tautan Cepat</h4>
                    <ul class="space-y-4 text-sm font-semibold">
                        <li><a class="hover:text-accent transition-colors flex items-center gap-2"
                                href="{{ route('profil') }}" wire:navigate><span
                                    class="w-1.5 h-1.5 bg-primary rounded-full"></span> Tentang Kami</a></li>
                        <li><a class="hover:text-accent transition-colors flex items-center gap-2"
                                href="{{ route('profil') }}" wire:navigate><span
                                    class="w-1.5 h-1.5 bg-primary rounded-full"></span> Struktur
                                Organisasi</a></li>
                        <li><a class="hover:text-accent transition-colors flex items-center gap-2"
                                href="{{ route('artikel.index') }}" wire:navigate><span
                                    class="w-1.5 h-1.5 bg-primary rounded-full"></span> Program Kerja</a></li>
                        <li><a class="hover:text-accent transition-colors flex items-center gap-2" href="#"
                                wire:navigate><span class="w-1.5 h-1.5 bg-primary rounded-full"></span> Layanan
                                Ambulans</a></li>
                        <li><a class="hover:text-accent transition-colors flex items-center gap-2" href="#"
                                wire:navigate><span class="w-1.5 h-1.5 bg-primary rounded-full"></span> Kontak</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-black text-sm uppercase tracking-widest mb-8">Hubungi Kami</h4>
                    <ul class="space-y-6 text-sm font-medium">
                        <li class="flex items-start gap-4">
                            <span class="material-symbols-outlined text-primary">location_on</span>
                            <span
                                class="leading-relaxed">{{ $settings['contact_address'] ?? 'Jl. Baktijaya No. 6, Kec. Sukmajaya, Kota Depok, Jawa Barat 16418' }}</span>
                        </li>
                        <li class="flex items-center gap-4">
                            <span class="material-symbols-outlined text-primary">call</span>
                            <span>{{ $settings['contact_phone'] ?? '(021) 7788-9900' }}</span>
                        </li>
                        <li class="flex items-center gap-4">
                            <span class="material-symbols-outlined text-primary">email</span>
                            <span>{{ $settings['contact_email'] ?? 'sekretariat@prnubaktijaya.org' }}</span>
                        </li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-black text-sm uppercase tracking-widest mb-8">Lokasi Kami</h4>
                    <div
                        class="rounded-xl overflow-hidden h-44 bg-white/5 border border-white/10 group shadow-2xl relative">
                        @if(!empty($settings['contact_map_link']))
                            @if(Str::contains($settings['contact_map_link'], '<iframe'))
                                <div
                                    class="w-full h-full [&_iframe]:w-full! [&_iframe]:h-full! [&_iframe]:border-0 [&_iframe]:absolute [&_iframe]:inset-0">
                                    {!! $settings['contact_map_link'] !!}
                                </div>
                            @else
                                <a href="{{ $settings['contact_map_link'] }}" target="_blank"
                                    class="block w-full h-full relative">
                                    <img alt="Klik Lihat Peta"
                                        class="w-full h-full object-cover opacity-60 group-hover:opacity-100 transition-opacity duration-500"
                                        src="https://placehold.co/600x400/png?text=Klik+Lihat+Peta" />
                                    <div
                                        class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300 bg-black/30">
                                        <span
                                            class="bg-white/10 backdrop-blur-md px-4 py-2 rounded-lg text-white font-bold text-xs border border-white/20">Buka
                                            di Maps</span>
                                    </div>
                                </a>
                            @endif
                        @else
                            <div class="w-full h-full bg-white/5 flex items-center justify-center text-gray-500 text-xs">
                                Map belum tersedia
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="border-t border-white/5 pt-10 flex flex-col md:flex-row justify-between items-center gap-6">
                <p class="text-[11px] font-black uppercase tracking-widest text-gray-500">© {{ date('Y') }}
                    {{ $settings['site_name'] ?? 'PRNU BAKTIJAYA' }}.
                    MERAWAT JAGAD MEMBANGUN PERADABAN.
                </p>
                <div class="flex gap-8 text-[11px] font-black uppercase tracking-widest text-gray-500">
                    <a class="hover:text-white transition-colors" href="#">Kebijakan Privasi</a>
                    <a class="hover:text-white transition-colors" href="#">Syarat Ketentuan</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Back to Top Button -->
    <button x-data="{ visible: false }" @scroll.window="visible = (window.pageYOffset > 300)"
        x-on:click="window.scrollTo({ top: 0, behavior: 'smooth' })" x-show="visible"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-10"
        x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-10"
        class="fixed z-[100] bg-primary hover:bg-primary-dark text-white p-3 rounded-full shadow-lg hover:shadow-xl transition-all"
        style="position: fixed; bottom: 30px; right: 30px; display: none;"
        x-init="$watch('visible', value => $el.style.display = value ? 'block' : 'none'); visible = window.pageYOffset > 300">
        <span class="material-symbols-outlined text-2xl">arrow_upward</span>
    </button>


    <!-- Search Modal -->
    <div x-data="{ searchOpen: false }"
        @open-search.window="searchOpen = true; $nextTick(() => $refs.searchInput.focus())"
        @keydown.escape.window="searchOpen = false" x-show="searchOpen" class="relative z-[100]"
        aria-labelledby="modal-title" role="dialog" aria-modal="true" style="display: none;">

        <div x-show="searchOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-gray-500/75 dark:bg-black/80 transition-opacity backdrop-blur-sm"></div>

        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-start sm:p-0">
                <div x-show="searchOpen" @click.away="searchOpen = false" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="relative transform overflow-hidden rounded-2xl bg-white dark:bg-background-dark text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-2xl border border-white/10">

                    <div class="absolute right-0 top-0 pr-4 pt-4 z-10">
                        <button type="button" @click="searchOpen = false"
                            class="rounded-md bg-transparent text-gray-400 hover:text-gray-500 focus:outline-none">
                            <span class="material-symbols-outlined">close</span>
                        </button>
                    </div>

                    <div class="p-6">
                        @livewire('global-search')
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Donation Modal -->
    <div x-data="{
        donationOpen: false,
        selectedCampaign: 1,
        selectedNominal: 20000,
        customNominal: '',
        donorName: '',
        donorPhone: '',
        donorPurpose: '',
        isAnonymous: false,
        paymentMethod: 'qris',
        campaigns: [
            { id: 1, title: 'Renovasi Masjid Al-Ikhlas', image: 'https://placehold.co/600x300/166534/FFFFFF?text=Renovasi+Masjid' },
            { id: 2, title: 'Santunan Yatim Piatu', image: 'https://placehold.co/600x300/15803d/FFFFFF?text=Santunan+Yatim' },
            { id: 3, title: 'Tanggap Bencana', image: 'https://placehold.co/600x300/14532d/FFFFFF?text=Tanggap+Bencana' }
        ],
        nominals: [10000, 20000, 50000, 100000],
        formatRupiah(number) {
            return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(number);
        },
        get totalAmount() {
            let amount = this.customNominal ? parseInt(this.customNominal) || 0 : this.selectedNominal;
            return this.formatRupiah(amount);
        },
        submitDonation() {
            alert('Terima kasih! Fitur pembayaran akan segera tersedia.');
            this.donationOpen = false;
        }
    }"
    @open-donation.window="donationOpen = true"
    @keydown.escape.window="donationOpen = false"
    x-show="donationOpen"
    class="relative z-[100]"
    aria-labelledby="modal-title" role="dialog" aria-modal="true" style="display: none;">

        <div x-show="donationOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-gray-500/75 dark:bg-black/80 transition-opacity backdrop-blur-sm"></div>

        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div x-show="donationOpen" @click.away="donationOpen = false" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="relative transform overflow-hidden rounded-2xl bg-white dark:bg-background-dark text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-xl border border-white/10 flex flex-col max-h-[90vh]">

                    <!-- Header -->
                    <div class="bg-primary/5 dark:bg-white/5 px-6 py-4 border-b border-primary/10 dark:border-white/10 flex justify-between items-center shrink-0">
                        <h3 class="text-xl font-black text-primary dark:text-white uppercase tracking-tight">Formulir Donasi</h3>
                        <button type="button" @click="donationOpen = false"
                            class="rounded-md bg-transparent text-gray-400 hover:text-gray-500 focus:outline-none">
                            <span class="material-symbols-outlined">close</span>
                        </button>
                    </div>

                    <!-- Body -->
                    <div class="p-6 overflow-y-auto custom-scrollbar space-y-8">
                        
                        <!-- Pilihan Kampanye -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-3 uppercase tracking-wide">Pilih Kampanye</label>
                            <div class="grid grid-cols-1 gap-4">
                                <template x-for="campaign in campaigns" :key="campaign.id">
                                    <div @click="selectedCampaign = campaign.id"
                                        class="cursor-pointer relative rounded-xl overflow-hidden border-2 transition-all group"
                                        :class="selectedCampaign === campaign.id ? 'border-primary ring-2 ring-primary/20' : 'border-transparent hover:border-primary/50'">
                                        <div class="aspect-[3/1] w-full relative">
                                            <img :src="campaign.image" :alt="campaign.title" class="w-full h-full object-cover">
                                            <div class="absolute inset-0 bg-black/40 flex items-center justify-center p-4">
                                                <h4 class="text-white font-bold text-lg text-center leading-tight shadow-black drop-shadow-md" x-text="campaign.title"></h4>
                                            </div>
                                            <!-- Checkmark -->
                                            <div x-show="selectedCampaign === campaign.id" 
                                                class="absolute top-2 right-2 bg-primary text-white rounded-full p-1 shadow-lg">
                                                <span class="material-symbols-outlined text-lg block">check</span>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- Pilihan Nominal -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-3 uppercase tracking-wide">Pilih Nominal Donasi</label>
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-4">
                                <template x-for="amount in nominals" :key="amount">
                                    <button type="button" @click="selectedNominal = amount; customNominal = ''"
                                        class="py-3 px-4 rounded-xl text-sm font-bold border-2 transition-all"
                                        :class="selectedNominal === amount && customNominal === '' ? 'border-primary bg-primary text-white shadow-lg shadow-primary/30' : 'border-gray-200 dark:border-white/10 text-gray-600 dark:text-gray-400 hover:border-primary/50 dark:hover:border-primary/50 hover:text-primary'">
                                        <span x-text="formatRupiah(amount)"></span>
                                    </button>
                                </template>
                            </div>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-500 font-bold">Rp</span>
                                <input type="number" x-model="customNominal" @input="selectedNominal = 0"
                                    class="w-full pl-12 pr-4 py-3 bg-gray-50 dark:bg-white/5 border-2 border-gray-200 dark:border-white/10 rounded-xl focus:ring-0 focus:border-primary font-bold text-gray-900 dark:text-white transition-colors"
                                    placeholder="Nominal Lainnya (Min. 10.000)">
                            </div>
                        </div>

                        <!-- Data Donatur -->
                        <div class="space-y-4">
                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wide">Data Donatur</label>
                            
                            <!-- Toggle Hamba Allah -->
                            <div class="flex items-center justify-between bg-primary/5 dark:bg-white/5 p-4 rounded-xl border border-primary/10 dark:border-white/10">
                                <span class="text-sm font-semibold text-primary dark:text-white">Donasi sebagai Hamba Allah</span>
                                <button type="button" @click="isAnonymous = !isAnonymous"
                                    class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none"
                                    :class="isAnonymous ? 'bg-primary' : 'bg-gray-200 dark:bg-gray-700'">
                                    <span aria-hidden="true" class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"
                                        :class="isAnonymous ? 'translate-x-5' : 'translate-x-0'"></span>
                                </button>
                            </div>

                            <div x-show="!isAnonymous" x-collapse>
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-500 mb-1">Nama Lengkap</label>
                                        <input type="text" x-model="donorName" class="w-full px-4 py-2.5 bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-lg focus:ring-primary focus:border-primary dark:text-white" placeholder="Masukkan nama anda">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-500 mb-1">Nomor HP (Opsional)</label>
                                        <input type="text" x-model="donorPhone" class="w-full px-4 py-2.5 bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-lg focus:ring-primary focus:border-primary dark:text-white" placeholder="08xxxxxxxxxx">
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Tujuan Donasi -->
                            <div>
                                <label class="block text-xs font-semibold text-gray-500 mb-1">Tujuan Donasi / Doa (Opsional)</label>
                                <textarea x-model="donorPurpose" rows="2" class="w-full px-4 py-2.5 bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-lg focus:ring-primary focus:border-primary dark:text-white" placeholder="Tuliskan tujuan donasi atau doa khusus..."></textarea>
                            </div>
                        </div>

                        <!-- Metode Pembayaran -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-3 uppercase tracking-wide">Metode Pembayaran</label>
                            <div class="space-y-3">
                                <label class="flex items-center justify-between p-4 border rounded-xl cursor-pointer transition-all"
                                    :class="paymentMethod === 'qris' ? 'border-primary bg-primary/5 ring-1 ring-primary' : 'border-gray-200 dark:border-white/10 hover:border-primary/50'">
                                    <div class="flex items-center gap-3">
                                        <div class="w-5 h-5 rounded-full border flex items-center justify-center" :class="paymentMethod === 'qris' ? 'border-primary' : 'border-gray-400'">
                                            <div class="w-2.5 h-2.5 rounded-full bg-primary" x-show="paymentMethod === 'qris'"></div>
                                        </div>
                                        <div>
                                            <span class="block font-bold text-gray-900 dark:text-white">QRIS (GoPay, OVO, Dana)</span>
                                            <span class="block text-xs text-gray-500">Proses otomatis & instan</span>
                                        </div>
                                    </div>
                                    <span class="material-symbols-outlined text-gray-400">qr_code_scanner</span>
                                    <input type="radio" name="payment" value="qris" x-model="paymentMethod" class="hidden">
                                </label>

                                <label class="flex items-center justify-between p-4 border rounded-xl cursor-pointer transition-all"
                                    :class="paymentMethod === 'bank_transfer' ? 'border-primary bg-primary/5 ring-1 ring-primary' : 'border-gray-200 dark:border-white/10 hover:border-primary/50'">
                                    <div class="flex items-center gap-3">
                                        <div class="w-5 h-5 rounded-full border flex items-center justify-center" :class="paymentMethod === 'bank_transfer' ? 'border-primary' : 'border-gray-400'">
                                            <div class="w-2.5 h-2.5 rounded-full bg-primary" x-show="paymentMethod === 'bank_transfer'"></div>
                                        </div>
                                        <div>
                                            <span class="block font-bold text-gray-900 dark:text-white">Transfer Bank (Manual)</span>
                                            <span class="block text-xs text-gray-500">Verifikasi manual via WhatsApp</span>
                                        </div>
                                    </div>
                                    <span class="material-symbols-outlined text-gray-400">account_balance</span>
                                    <input type="radio" name="payment" value="bank_transfer" x-model="paymentMethod" class="hidden">
                                </label>
                            </div>
                        </div>

                    </div>

                    <!-- Footer -->
                    <div class="bg-gray-50 dark:bg-white/5 px-6 py-4 border-t border-gray-100 dark:border-white/5 shrink-0">
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-sm font-medium text-gray-500">Total Donasi</span>
                            <span class="text-2xl font-black text-primary" x-text="totalAmount"></span>
                        </div>
                        <button type="button" @click="submitDonation"
                            class="w-full bg-primary hover:bg-primary-dark text-white font-bold py-3.5 rounded-xl shadow-lg shadow-primary/25 transition-all transform hover:-translate-y-0.5"
                            :disabled="(selectedNominal == 0 && customNominal == '')">
                            Konfirmasi Donasi
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </div>

    @livewireScripts
    @stack('scripts')
</body>

</html>