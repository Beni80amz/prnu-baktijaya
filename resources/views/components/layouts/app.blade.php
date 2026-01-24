<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'PRNU Baktijaya - Merawat Jagad, Membangun Peradaban' }}</title>

    @php
        $settings = \App\Models\Setting::all()->pluck('value', 'key');
    @endphp

    @if(!empty($settings['site_favicon']))
        <link rel="icon" type="image/png" href="{{ Storage::url($settings['site_favicon']) }}">
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
    <nav
        class="sticky top-0 z-50 border-b border-primary/10 dark:border-white/10 bg-white/95 dark:bg-background-dark/95 backdrop-blur-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex items-center gap-4">
                    <a href="{{ route('home') }}" wire:navigate
                        class="flex items-center gap-3 text-primary dark:text-white">
                        <div
                            class="size-10 rounded-full flex items-center justify-center overflow-hidden {{ empty($settings['site_logo']) ? 'bg-primary/10 dark:bg-white/10' : '' }}">
                            @if(!empty($settings['site_logo']))
                                <img src="{{ Storage::url($settings['site_logo']) }}" alt="Logo"
                                    class="w-full h-full object-cover">
                            @else
                                <span class="material-symbols-outlined text-primary text-2xl">mosque</span>
                            @endif
                        </div>
                        <div class="hidden md:block">
                            <h2 class="text-xl font-bold leading-none tracking-tight">PRNU Baktijaya</h2>
                            <p
                                class="text-[10px] text-primary/70 dark:text-white/60 font-semibold uppercase tracking-wider mt-1">
                                Nahdlatul Ulama Ranting Baktijaya</p>
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
                </div>

                <div class="flex items-center gap-4">
                    <!-- Theme Toggle -->
                    <button id="theme-toggle-btn" onclick="toggleTheme()"
                        class="text-primary/70 hover:text-primary transition-colors p-2 rounded-full hover:bg-primary/5 dark:hover:bg-white/5 cursor-pointer flex items-center justify-center z-50 relative">
                        <span id="theme-icon" class="material-symbols-outlined">dark_mode</span>
                    </button>

                    <button
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

                    <button
                        class="hidden sm:flex items-center gap-2 bg-primary hover:bg-primary-dark text-white text-sm font-bold py-2.5 px-5 rounded-lg transition-all shadow-md shadow-primary/20">
                        <span class="material-symbols-outlined text-[20px]">volunteer_activism</span>
                        <span>Donasi</span>
                    </button>
                    <button class="lg:hidden text-primary p-2">
                        <span class="material-symbols-outlined">menu</span>
                    </button>
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
                                <img src="{{ Storage::url($settings['site_logo']) }}" alt="Logo"
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
                        <li><a class="hover:text-accent transition-colors flex items-center gap-2" href="#"
                                wire:navigate><span class="w-1.5 h-1.5 bg-primary rounded-full"></span> Struktur
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
                                class="leading-relaxed">{{ $settings['contact_address'] ?? 'Jl. Baktijaya No. 123, Kec. Sukmajaya, Kota Depok, Jawa Barat 16418' }}</span>
                        </li>
                        <li class="flex items-center gap-4">
                            <span class="material-symbols-outlined text-primary">call</span>
                            <span>{{ $settings['contact_phone'] ?? '(021) 7788-9900' }}</span>
                        </li>
                        <li class="flex items-center gap-4">
                            <span class="material-symbols-outlined text-primary">email</span>
                            <span>{{ $settings['contact_email'] ?? 'sekretariat@prnubaktijaya.or.id' }}</span>
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


    @livewireScripts
    @stack('scripts')
</body>

</html>