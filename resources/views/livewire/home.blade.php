<div x-data="{ showDonationModal: false }" @open-donation.window="showDonationModal = true">
    <script>
        window.donationCampaignsData = @json($donationCampaigns);
        window.donationBankData = {
            'BSI': {
                name: '{{ $settings['donation_bank_name'] ?? 'Bank Syariah Indonesia' }}',
                account: '{{ $settings['donation_bank_number'] ?? '' }}',
                owner: '{{ $settings['donation_bank_owner'] ?? 'PRNU Baktijaya' }}'
            },
            'BRI': {
                name: 'Bank BRI',
                account: '{{ $settings['donation_bank_bri'] ?? '' }}',
                owner: '{{ $settings['donation_bank_owner'] ?? 'PRNU Baktijaya' }}'
            },
            'BCA': {
                name: 'Bank BCA',
                account: '{{ $settings['donation_bank_bca'] ?? '' }}',
                owner: '{{ $settings['donation_bank_owner'] ?? 'PRNU Baktijaya' }}'
            },
            'Mandiri': {
                name: 'Bank Mandiri',
                account: '{{ $settings['donation_bank_mandiri'] ?? '' }}',
                owner: '{{ $settings['donation_bank_owner'] ?? 'PRNU Baktijaya' }}'
            }
        };
        // Filter banks with valid account numbers
        window.availableBanks = Object.keys(window.donationBankData).filter(key => {
            let acc = window.donationBankData[key].account;
            return acc && acc !== '-' && acc.trim() !== '';
        });

        // E-Wallet data with QR codes
        window.donationEwalletData = {
            'OVO': {
                number: '{{ $settings['donation_ewallet_ovo'] ?? '' }}',
                qr: '{{ $settings['donation_ewallet_ovo_qr'] ? asset('storage/' . $settings['donation_ewallet_ovo_qr']) : '' }}'
            },
            'GoPay': {
                number: '{{ $settings['donation_ewallet_gopay'] ?? '' }}',
                qr: '{{ $settings['donation_ewallet_gopay_qr'] ? asset('storage/' . $settings['donation_ewallet_gopay_qr']) : '' }}'
            },
            'DANA': {
                number: '{{ $settings['donation_ewallet_dana'] ?? '' }}',
                qr: '{{ $settings['donation_ewallet_dana_qr'] ? asset('storage/' . $settings['donation_ewallet_dana_qr']) : '' }}'
            }
        };
        // Filter e-wallets with valid numbers
        window.availableEwallets = Object.keys(window.donationEwalletData).filter(key => {
            let num = window.donationEwalletData[key].number;
            return num && num !== '-' && num.trim() !== '';
        });

        // QRIS Image (legacy/universal)
        window.donationQrisImage = '{{ $settings['donation_qris_image'] ? asset('storage/' . $settings['donation_qris_image']) : '' }}';

        // Global copy function with fallback
        window.copyToClipboard = function (text, buttonEl) {
            const originalText = buttonEl.textContent;

            // Try modern clipboard API first
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(text).then(function () {
                    buttonEl.textContent = 'TERSALIN!';
                    setTimeout(function () { buttonEl.textContent = originalText; }, 2000);
                }).catch(function () {
                    // Fallback
                    fallbackCopy(text, buttonEl, originalText);
                });
            } else {
                // Fallback for older browsers
                fallbackCopy(text, buttonEl, originalText);
            }
        };

        function fallbackCopy(text, buttonEl, originalText) {
            const textArea = document.createElement('textarea');
            textArea.value = text;
            textArea.style.position = 'fixed';
            textArea.style.left = '-999999px';
            document.body.appendChild(textArea);
            textArea.select();
            try {
                document.execCommand('copy');
                buttonEl.textContent = 'TERSALIN!';
                setTimeout(function () { buttonEl.textContent = originalText; }, 2000);
            } catch (err) {
                console.error('Copy failed:', err);
            }
            document.body.removeChild(textArea);
        }
    </script>
    <!-- Hero Section -->
    <header class="relative w-full h-[650px] overflow-hidden flex items-center justify-center bg-black" x-data='{
            currentSlide: 0,
            slides: @json($sliders),
            timer: null,
            next() {
                this.currentSlide = (this.currentSlide + 1) % this.slides.length;
            },
            prev() {
                this.currentSlide = (this.currentSlide - 1 + this.slides.length) % this.slides.length;
            },
            startAutoPlay() {
                this.timer = setInterval(() => this.next(), 8000);
            },
            stopAutoPlay() {
                clearInterval(this.timer);
            }
        }' x-init="startAutoPlay()" @mouseenter="stopAutoPlay()" @mouseleave="startAutoPlay()">

        @if(count($sliders) > 0)
            <!-- Background Images Logic -->
            <template x-for="(slide, index) in slides" :key="index">
                <div class="absolute inset-0 z-0 bg-cover bg-center" x-show="currentSlide === index"
                    x-transition:enter="transition ease-in-out duration-[5000ms]" x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in-out duration-[5000ms]"
                    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                    :style="`background-image: url('${slide.image}');`">
                </div>
            </template>

            <!-- Gradient Overlay -->
            <div
                class="absolute inset-0 z-0 bg-gradient-to-b from-[rgba(15,35,35,0.7)] via-[rgba(0,102,102,0.6)] to-[rgba(0,102,102,0.9)]">
            </div>

            <!-- Content -->
            <div class="relative z-10 container mx-auto px-4 text-center">
                <div class="max-w-3xl mx-auto space-y-8 text-white">

                    @if($dawuh)
                        <div
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-accent font-bold text-xs uppercase tracking-widest mb-4">
                            <span class="material-symbols-outlined text-sm">format_quote</span>
                            <span>Dawuh Ulama</span>
                        </div>
                    @endif

                    <!-- Title with x-html -->
                    <h1 class="text-4xl md:text-6xl font-black tracking-tighter leading-tight drop-shadow-lg min-h-[1.2em]"
                        x-html="slides[currentSlide].title" x-transition:enter="transition ease-out duration-500 delay-200"
                        x-transition:enter-start="opacity-0 translate-y-4"
                        x-transition:enter-end="opacity-100 translate-y-0">
                    </h1>

                    @if($dawuh)
                        <!-- Dawuh logic remains static as it overrides slider description if present -->
                        <div
                            class="bg-background-dark/40 backdrop-blur-xl p-8 rounded-2xl border-l-4 border-accent max-w-2xl mx-auto mt-6 shadow-2xl">
                            <p class="text-lg md:text-xl font-medium italic leading-relaxed text-background-light/90">
                                "{{ $dawuh->quote }}"
                            </p>
                            <p class="text-sm font-extrabold text-accent mt-4 uppercase tracking-[0.2em]">—
                                {{ $dawuh->ulama_title }} {{ $dawuh->ulama_name }}
                            </p>
                        </div>
                    @else
                        <!-- Description with x-text -->
                        <p class="text-xl md:text-2xl font-medium leading-relaxed text-white/90 max-w-2xl mx-auto drop-shadow-md min-h-[3em]"
                            x-text="slides[currentSlide].description"
                            x-transition:enter="transition ease-out duration-500 delay-300"
                            x-transition:enter-start="opacity-0 translate-y-4"
                            x-transition:enter-end="opacity-100 translate-y-0">
                        </p>
                    @endif

                    <!-- Buttons -->
                    <div class="pt-8 flex flex-col sm:flex-row gap-4 justify-center">
                        <a :href="slides[currentSlide].link_url || '#'"
                            class="bg-primary hover:bg-primary-dark text-white font-bold py-4 px-10 rounded-lg transition-all transform hover:-translate-y-1 shadow-xl shadow-primary/30 border border-white/20">
                            <span x-text="slides[currentSlide].button_text || 'Lihat Kegiatan'"></span>
                        </a>
                        <a href="{{ route('profil') }}" wire:navigate
                            class="bg-white/10 hover:bg-white/20 text-white border border-white/30 font-bold py-4 px-10 rounded-lg backdrop-blur-md transition-all">
                            Tentang Kami
                        </a>
                    </div>
                </div>
            </div>

            <!-- Slider Navigation Controls -->
            <button @click="prev()"
                class="absolute left-4 top-1/2 -translate-y-1/2 bg-white/10 hover:bg-white/20 p-4 rounded-full text-white backdrop-blur-md transition-all z-20 hidden md:block group">
                <span class="material-symbols-outlined group-hover:-translate-x-1 transition-transform">chevron_left</span>
            </button>
            <button @click="next()"
                class="absolute right-4 top-1/2 -translate-y-1/2 bg-white/10 hover:bg-white/20 p-4 rounded-full text-white backdrop-blur-md transition-all z-20 hidden md:block group">
                <span class="material-symbols-outlined group-hover:translate-x-1 transition-transform">chevron_right</span>
            </button>

            <!-- Indicators -->
            <div class="absolute bottom-10 left-1/2 -translate-x-1/2 flex gap-3 z-20">
                <template x-for="(slide, index) in slides" :key="index">
                    <button @click="currentSlide = index" class="h-1.5 rounded-full transition-all duration-300"
                        :class="currentSlide === index ? 'w-8 bg-accent' : 'w-2 bg-white/30 hover:bg-white/50'">
                    </button>
                </template>
            </div>

        @else
            <div class="absolute inset-0 z-0 bg-cover bg-center"
                style='background-image: url("https://placehold.co/1920x1080");'></div>
            <div
                class="absolute inset-0 z-0 bg-gradient-to-b from-[rgba(15,35,35,0.7)] via-[rgba(0,102,102,0.6)] to-[rgba(0,102,102,0.9)]">
            </div>

            <div class="relative z-10 container mx-auto px-4 text-center">
                <div class="max-w-3xl mx-auto space-y-8 text-white">
                    <h1 class="text-5xl md:text-7xl font-black tracking-tighter leading-tight">
                        Merawat Jagad, <br />
                        <span class="text-accent">Membangun Peradaban</span>
                    </h1>
                </div>
            </div>
        @endif
    </header>

    <!-- Jadwal Sholat -->
    <div class="sticky top-20 z-40 bg-white dark:bg-background-dark shadow-xl border-b border-primary/10 dark:border-white/10"
        x-data="{ showCityDropdown: false, searchQuery: '', searchResults: [] }" x-init="
            // Try to get user's location on page load
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        // For now, we'll just log the coordinates
                        // In a real implementation, we'd use a reverse geocoding service to find the nearest city
                        console.log('User location:', position.coords.latitude, position.coords.longitude);
                    },
                    (error) => {
                        console.log('Geolocation denied or failed, using default city');
                    }
                );
            }
         ">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="py-4 flex flex-col md:flex-row items-center justify-between gap-6">
                <div class="flex items-center gap-4">
                    <div class="p-2 bg-primary/10 rounded-lg">
                        <span class="material-symbols-outlined text-primary font-bold">schedule</span>
                    </div>
                    <div class="relative">
                        <h3
                            class="text-sm font-black text-background-dark dark:text-white uppercase tracking-tight flex items-center gap-2">
                            Jadwal Sholat
                            <span
                                class="text-[10px] font-bold text-primary bg-primary/10 px-2 py-0.5 rounded-full lowercase tracking-normal">{{ $hijriDate }}</span>
                        </h3>
                        <button @click="showCityDropdown = !showCityDropdown"
                            class="text-[11px] text-primary/60 dark:text-white/50 font-medium hover:text-primary dark:hover:text-white transition-colors flex items-center gap-1 cursor-pointer">
                            <span class="material-symbols-outlined text-[14px]">location_on</span>
                            {{ $cityName }}
                            <span class="material-symbols-outlined text-[14px]">expand_more</span>
                        </button>

                        <!-- City Dropdown -->
                        <div x-show="showCityDropdown" @click.away="showCityDropdown = false" x-transition
                            class="absolute top-full left-0 mt-2 w-72 bg-white dark:bg-background-dark rounded-xl shadow-2xl border border-primary/10 dark:border-white/10 z-50 overflow-hidden">
                            <div class="p-3 border-b border-primary/10 dark:border-white/10">
                                <input type="text" placeholder="Cari kota..."
                                    class="w-full px-3 py-2 text-sm bg-gray-50 dark:bg-white/5 border border-primary/10 dark:border-white/10 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent text-background-dark dark:text-white"
                                    x-model="searchQuery">
                            </div>
                            <div class="max-h-60 overflow-y-auto">
                                @foreach(array_slice($allCities, 0, 20) as $city)
                                    <button wire:click="updateCity({{ $city['id'] }})" @click="showCityDropdown = false"
                                        class="w-full text-left px-4 py-2 text-sm hover:bg-primary/5 dark:hover:bg-white/5 transition-colors text-background-dark dark:text-white {{ $city['id'] == $cityId ? 'bg-primary/10 text-primary font-bold' : '' }}">
                                        {{ $city['lokasi'] }}
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Dynamic Times from API -->
                <div class="flex-1 w-full md:w-auto overflow-x-auto no-scrollbar">
                    <div class="flex justify-between md:justify-end gap-3 min-w-max px-1">
                        {{-- Subuh --}}
                        <div
                            class="flex flex-col items-center justify-center rounded-lg p-3 min-w-[90px] transition-colors
                            {{ $activePrayer === 'subuh' ? 'bg-primary text-white shadow-lg shadow-primary/20' : 'bg-primary/5 dark:bg-white/5 border border-primary/10' }}">
                            <span
                                class="text-[10px] font-extrabold uppercase tracking-widest {{ $activePrayer === 'subuh' ? 'opacity-80' : 'text-primary/60 dark:text-white/40' }}">Subuh</span>
                            <span
                                class="text-xl font-black {{ $activePrayer === 'subuh' ? '' : 'text-primary dark:text-white' }}">{{ $prayerTimes['subuh'] ?? '04:30' }}</span>
                        </div>
                        {{-- Dzuhur --}}
                        <div
                            class="flex flex-col items-center justify-center rounded-lg p-3 min-w-[90px] transition-colors
                            {{ $activePrayer === 'dzuhur' ? 'bg-primary text-white shadow-lg shadow-primary/20' : 'hover:bg-primary/5 border border-transparent' }}">
                            <span
                                class="text-[10px] font-bold uppercase tracking-widest {{ $activePrayer === 'dzuhur' ? 'opacity-80' : 'text-gray-500' }}">Dzuhur</span>
                            <span
                                class="text-xl font-bold {{ $activePrayer === 'dzuhur' ? 'font-black' : 'text-gray-800 dark:text-gray-300' }}">{{ $prayerTimes['dzuhur'] ?? '12:00' }}</span>
                        </div>
                        {{-- Ashar --}}
                        <div
                            class="flex flex-col items-center justify-center rounded-lg p-3 min-w-[90px] transition-colors
                            {{ $activePrayer === 'ashar' ? 'bg-primary text-white shadow-lg shadow-primary/20' : 'hover:bg-primary/5 border border-transparent' }}">
                            <span
                                class="text-[10px] font-bold uppercase tracking-widest {{ $activePrayer === 'ashar' ? 'font-extrabold opacity-80' : 'text-gray-500' }}">Ashar</span>
                            <span
                                class="text-xl font-bold {{ $activePrayer === 'ashar' ? 'font-black' : 'text-gray-800 dark:text-gray-300' }}">{{ $prayerTimes['ashar'] ?? '15:30' }}</span>
                        </div>
                        {{-- Maghrib --}}
                        <div
                            class="flex flex-col items-center justify-center rounded-lg p-3 min-w-[90px] transition-colors
                            {{ $activePrayer === 'maghrib' ? 'bg-primary text-white shadow-lg shadow-primary/20' : 'hover:bg-primary/5 border border-transparent' }}">
                            <span
                                class="text-[10px] font-bold uppercase tracking-widest {{ $activePrayer === 'maghrib' ? 'font-extrabold opacity-80' : 'text-gray-500' }}">Maghrib</span>
                            <span
                                class="text-xl font-bold {{ $activePrayer === 'maghrib' ? 'font-black' : 'text-gray-800 dark:text-gray-300' }}">{{ $prayerTimes['maghrib'] ?? '18:00' }}</span>
                        </div>
                        {{-- Isya --}}
                        <div
                            class="flex flex-col items-center justify-center rounded-lg p-3 min-w-[90px] transition-colors
                            {{ $activePrayer === 'isya' ? 'bg-primary text-white shadow-lg shadow-primary/20' : 'hover:bg-primary/5 border border-transparent' }}">
                            <span
                                class="text-[10px] font-bold uppercase tracking-widest {{ $activePrayer === 'isya' ? 'font-extrabold opacity-80' : 'text-gray-500' }}">Isya</span>
                            <span
                                class="text-xl font-bold {{ $activePrayer === 'isya' ? 'font-black' : 'text-gray-800 dark:text-gray-300' }}">{{ $prayerTimes['isya'] ?? '19:30' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Promo Section - Combined Static + Dynamic Slider -->
    <section class="py-12 bg-background-light dark:bg-background-dark overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="relative group" x-data="promoSlider()" x-init="init()">
                <div class="overflow-hidden rounded-xl shadow-2xl bg-primary">
                    <div class="relative flex min-h-[400px]">

                        <!-- Slide 0: Original Hardcoded Static Slide -->
                        <div x-show="activeSlide === 0" x-transition:enter="transition ease-out duration-700"
                            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                            x-transition:leave="transition ease-in duration-500" x-transition:leave-start="opacity-100"
                            x-transition:leave-end="opacity-0" class="absolute inset-0 w-full h-full">
                            <img alt="Gerakan Koin NU" class="w-full h-full object-cover opacity-40 mix-blend-overlay"
                                src="https://placehold.co/1200x600?text=NU+Care" />
                            <div
                                class="absolute inset-0 bg-gradient-to-r from-primary/90 via-primary/40 to-transparent">
                            </div>
                        </div>

                        <!-- Dynamic Campaign Slides (index starts from 1) -->
                        <template x-for="(campaign, index) in campaigns" :key="'campaign-'+index">
                            <div x-show="activeSlide === (index + 1)"
                                x-transition:enter="transition ease-out duration-700"
                                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                x-transition:leave="transition ease-in duration-500"
                                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                                class="absolute inset-0 w-full h-full">
                                <img :alt="campaign.title"
                                    class="w-full h-full object-cover opacity-40 mix-blend-overlay"
                                    :src="campaign.image" />
                                <div
                                    class="absolute inset-0 bg-gradient-to-r from-primary/90 via-primary/40 to-transparent">
                                </div>
                            </div>
                        </template>

                        <!-- Content Overlay -->
                        <div
                            class="relative z-10 w-full flex flex-col md:flex-row items-center justify-between p-8 md:p-16 gap-8">
                            <div class="flex-1 text-white space-y-6">
                                <div
                                    class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-accent/20 border border-accent/30 text-accent text-xs font-black uppercase tracking-widest">
                                    <span class="size-2 bg-accent rounded-full animate-pulse"></span>
                                    Program Unggulan
                                </div>

                                <!-- Static Content for Slide 0 -->
                                <template x-if="activeSlide === 0">
                                    <div>
                                        <h2
                                            class="text-4xl md:text-5xl font-black leading-tight tracking-tight text-white drop-shadow-md">
                                            Gerakan Koin NU<br /><span class="text-accent">Mandiri &amp; Berdaya</span>
                                        </h2>
                                        <p
                                            class="text-white/90 text-lg max-w-xl font-medium leading-relaxed drop-shadow-sm mt-4">
                                            Wujudkan kemandirian ekonomi umat melalui donasi rutin untuk program sosial,
                                            kesehatan, dan pendidikan di lingkungan Baktijaya.
                                        </p>
                                    </div>
                                </template>

                                <!-- Dynamic Content for Campaign Slides -->
                                <template x-if="activeSlide > 0 && campaigns[activeSlide - 1]">
                                    <div>
                                        <h2 class="text-4xl md:text-5xl font-black leading-tight tracking-tight text-white drop-shadow-md"
                                            x-text="campaigns[activeSlide - 1].title"></h2>
                                        <p class="text-white/90 text-lg max-w-xl font-medium leading-relaxed drop-shadow-sm mt-4"
                                            x-text="campaigns[activeSlide - 1].subtitle || 'Wujudkan kebaikan bersama kami'">
                                        </p>
                                    </div>
                                </template>

                                <!-- Only show button on dynamic campaign slides -->
                                <template x-if="activeSlide > 0">
                                    <div class="flex gap-4 pt-4">
                                        <button @click="$dispatch('open-donation')"
                                            class="bg-accent hover:bg-white text-background-dark font-black py-4 px-8 rounded-lg transition-all transform hover:scale-105 shadow-xl shadow-black/20">
                                            IKUT BERKONTRIBUSI
                                        </button>
                                    </div>
                                </template>
                            </div>
                            <div class="hidden md:block w-1/3">
                                <div
                                    class="bg-white/10 backdrop-blur-md p-8 rounded-2xl border border-white/20 rotate-3 transform transition-transform group-hover:rotate-0 shadow-2xl">
                                    <!-- Static info for Slide 0 -->
                                    <template x-if="activeSlide === 0">
                                        <div>
                                            <div class="text-accent text-5xl font-black mb-2 italic">100%</div>
                                            <p class="text-white font-bold uppercase tracking-wider text-sm">Amanah
                                                &amp;
                                                Transparan</p>
                                        </div>
                                    </template>
                                    <!-- Dynamic info for Campaign Slides -->
                                    <template x-if="activeSlide > 0 && campaigns[activeSlide - 1]?.target_amount > 0">
                                        <div>
                                            <div class="text-accent text-3xl font-black mb-2">Target</div>
                                            <p class="text-white font-bold text-2xl"
                                                x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(campaigns[activeSlide - 1]?.target_amount || 0)">
                                            </p>
                                        </div>
                                    </template>
                                    <template
                                        x-if="activeSlide > 0 && (!campaigns[activeSlide - 1]?.target_amount || campaigns[activeSlide - 1]?.target_amount == 0)">
                                        <div>
                                            <div class="text-accent text-5xl font-black mb-2 italic">100%</div>
                                            <p class="text-white font-bold uppercase tracking-wider text-sm">Amanah
                                                &amp;
                                                Transparan</p>
                                        </div>
                                    </template>
                                    <div class="w-full h-1.5 bg-white/20 rounded-full mt-4">
                                        <div class="w-3/4 h-full bg-accent rounded-full"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Slider Controls -->
                <button x-show="totalSlides > 1" @click="prevSlide()"
                    class="absolute left-4 top-1/2 -translate-y-1/2 z-20 bg-white/10 hover:bg-white/30 backdrop-blur-md text-white p-3 rounded-full transition-all opacity-0 group-hover:opacity-100 border border-white/20">
                    <span class="material-symbols-outlined">chevron_left</span>
                </button>
                <button x-show="totalSlides > 1" @click="nextSlide()"
                    class="absolute right-4 top-1/2 -translate-y-1/2 z-20 bg-white/10 hover:bg-white/30 backdrop-blur-md text-white p-3 rounded-full transition-all opacity-0 group-hover:opacity-100 border border-white/20">
                    <span class="material-symbols-outlined">chevron_right</span>
                </button>
                <div x-show="totalSlides > 1" class="absolute bottom-6 left-1/2 -translate-x-1/2 flex gap-3">
                    <template x-for="i in totalSlides" :key="'dot-'+i">
                        <button @click="goToSlide(i - 1)"
                            :class="activeSlide === (i - 1) ? 'bg-accent size-3' : 'bg-white/30 size-2'"
                            class="rounded-full transition-all"></button>
                    </template>
                </div>
            </div>
        </div>
    </section>

    <!-- Services -->
    <section class="py-24 bg-background-light dark:bg-background-dark">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <span class="text-primary font-black tracking-[0.2em] text-xs uppercase">Layanan Digital</span>
                <h2 class="text-4xl font-black text-background-dark dark:text-white mt-3 mb-4">Akses Cepat Layanan Umat
                </h2>
                <div class="w-20 h-1.5 bg-accent mx-auto rounded-full"></div>
                <p class="text-gray-600 dark:text-white/60 mt-6 max-w-2xl mx-auto text-lg leading-relaxed">Meningkatkan
                    kesejahteraan jamaah melalui digitalisasi bimbingan keagamaan.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div
                    class="group relative bg-white dark:bg-white/5 p-10 rounded-2xl shadow-sm hover:shadow-2xl transition-all duration-500 border border-primary/5 dark:border-white/5 hover:border-accent/40">
                    <div
                        class="size-16 bg-primary/10 rounded-xl flex items-center justify-center mb-8 text-primary group-hover:bg-primary group-hover:text-white transition-all duration-300">
                        <span class="material-symbols-outlined text-4xl">calculate</span>
                    </div>
                    <h3 class="text-2xl font-black text-background-dark dark:text-white mb-4">Kalkulator Zakat</h3>
                    <p class="text-gray-600 dark:text-white/60 mb-8 text-sm leading-relaxed">Hitung kewajiban zakat maal
                        dan penghasilan Anda secara otomatis sesuai syariat.</p>
                    <a class="inline-flex items-center text-primary font-black text-sm uppercase tracking-wider group-hover:text-accent transition-colors"
                        href="{{ route('zakat') }}" wire:navigate>
                        Hitung Sekarang <span
                            class="material-symbols-outlined text-lg ml-2 group-hover:translate-x-1 transition-transform">arrow_forward</span>
                    </a>
                </div>
                <div
                    class="group relative bg-white dark:bg-white/5 p-10 rounded-2xl shadow-sm hover:shadow-2xl transition-all duration-500 border border-primary/5 dark:border-white/5 hover:border-accent/40">
                    <div
                        class="size-16 bg-primary/10 rounded-xl flex items-center justify-center mb-8 text-primary group-hover:bg-primary group-hover:text-white transition-all duration-300">
                        <span class="material-symbols-outlined text-4xl">chat</span>
                    </div>
                    <h3 class="text-2xl font-black text-background-dark dark:text-white mb-4">Tanya Kiai</h3>
                    <p class="text-gray-600 dark:text-white/60 mb-8 text-sm leading-relaxed">Konsultasi hukum Islam dan
                        problematika umat langsung dengan para Asatidz pilihan.</p>
                    <a class="inline-flex items-center text-primary font-black text-sm uppercase tracking-wider group-hover:text-accent transition-colors"
                        href="{{ route('tanya-kiai') }}" wire:navigate>
                        Mulai Chat <span
                            class="material-symbols-outlined text-lg ml-2 group-hover:translate-x-1 transition-transform">arrow_forward</span>
                    </a>
                </div>
                <div
                    class="group relative bg-white dark:bg-white/5 p-10 rounded-2xl shadow-sm hover:shadow-2xl transition-all duration-500 border border-primary/5 dark:border-white/5 hover:border-accent/40">
                    <div
                        class="size-16 bg-primary/10 rounded-xl flex items-center justify-center mb-8 text-primary group-hover:bg-primary group-hover:text-white transition-all duration-300">
                        <span class="material-symbols-outlined text-4xl">diversity_1</span>
                    </div>
                    <h3 class="text-2xl font-black text-background-dark dark:text-white mb-4">Ruang Doa</h3>
                    <p class="text-gray-600 dark:text-white/60 mb-8 text-sm leading-relaxed">Khidmat permohonan doa
                        virtual yang akan dibacakan rutin setiap majelis taklim.</p>
                    <a class="inline-flex items-center text-primary font-black text-sm uppercase tracking-wider group-hover:text-accent transition-colors"
                        href="{{ route('ruang-doa') }}" wire:navigate>
                        Titip Doa <span
                            class="material-symbols-outlined text-lg ml-2 group-hover:translate-x-1 transition-transform">arrow_forward</span>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- News & Video -->
    <section class="py-24 bg-white dark:bg-background-dark border-t border-primary/5 dark:border-white/5">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row gap-16">
                <!-- News -->
                <div class="lg:w-2/3">
                    <div class="flex items-center justify-between mb-12">
                        <h2 class="text-3xl font-black text-background-dark dark:text-white flex items-center gap-4">
                            <span class="w-2.5 h-10 bg-primary rounded-full"></span>
                            Kabar Baktijaya
                        </h2>
                        <a class="text-xs font-bold text-primary hover:text-accent transition-colors uppercase tracking-widest border-b-2 border-primary/20 pb-1"
                            href="{{ route('berita.index') }}" wire:navigate>Lihat Semua</a>
                    </div>
                    <div class="space-y-10">
                        @forelse($news as $item)
                            <article class="flex flex-col sm:flex-row gap-8 group">
                                <a href="{{ route('berita.show', $item->slug) }}" wire:navigate
                                    class="w-full sm:w-60 h-40 rounded-xl overflow-hidden shrink-0 relative shadow-lg block">
                                    <div
                                        class="absolute inset-0 bg-primary/10 group-hover:bg-transparent transition-colors z-10">
                                    </div>
                                    <img alt="{{ $item->title }}"
                                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                                        src="{{ $item->featured_image ? Storage::url($item->featured_image) : 'https://placehold.co/600x400' }}" />
                                </a>
                                <div class="flex-1 flex flex-col justify-center">
                                    <div class="flex items-center gap-4 mb-3">
                                        <span
                                            class="bg-primary/10 text-primary text-[10px] font-black px-3 py-1 rounded-full uppercase tracking-widest">{{ $item->category?->name ?? 'Berita' }}</span>
                                        <span class="text-[11px] text-gray-400 font-bold flex items-center gap-1.5"><span
                                                class="material-symbols-outlined text-[16px]">calendar_today</span>
                                            {{ $item->created_at->format('d M Y') }}</span>
                                    </div>
                                    <h3
                                        class="text-xl font-black text-background-dark dark:text-white mb-3 group-hover:text-primary transition-colors leading-tight">
                                        <a href="{{ route('berita.show', $item->slug) }}"
                                            wire:navigate>{{ $item->title }}</a>
                                    </h3>
                                    <p class="text-sm text-gray-600 dark:text-white/50 line-clamp-2 leading-relaxed">
                                        {{ Str::limit(strip_tags($item->content), 120) }}
                                    </p>
                                </div>
                            </article>
                        @empty
                            <p class="text-gray-500">Belum ada berita.</p>
                        @endforelse
                    </div>
                </div>

                <!-- Video & Agenda -->
                <div class="lg:w-1/3 space-y-12">
                    <!-- Video -->
                    <div class="bg-background-dark rounded-2xl overflow-hidden shadow-2xl border border-white/5">
                        <div class="bg-primary/20 px-6 py-4 flex items-center justify-between border-b border-white/10">
                            @if(!empty($settings['youtube_live_status']) && $settings['youtube_live_status'])
                                <h3 class="text-white font-black text-sm flex items-center gap-3">
                                    <span class="relative flex h-3 w-3">
                                        <span
                                            class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                        <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500"></span>
                                    </span>
                                    LIVE STREAMING
                                </h3>
                            @else
                                <h3 class="text-white font-black text-sm flex items-center gap-3">
                                    <span class="material-symbols-outlined text-white text-sm">play_circle</span>
                                    VIDEO TERBARU
                                </h3>
                            @endif
                            <a href="{{ $settings['social_youtube'] ?? '#' }}" target="_blank"
                                class="text-[10px] font-bold text-accent tracking-widest hover:text-white transition-colors cursor-pointer">
                                YOUTUBE <span
                                    class="material-symbols-outlined text-[10px] align-middle">open_in_new</span>
                            </a>
                        </div>

                        @php
                            $isLive = !empty($settings['youtube_live_status']) && $settings['youtube_live_status'];
                            $ytUrl = $isLive
                                ? ($settings['youtube_live_url'] ?? '')
                                : ($settings['youtube_video_url'] ?? '');

                            $videoId = '';
                            // Regex support for:
                            // youtube.com/watch?v=ID
                            // youtube.com/embed/ID
                            // youtu.be/ID
                            // youtube.com/live/ID
                            // Handles parameters like ?si=...
                            if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=|live/)|youtu\.be/)([^"&?/ ]{11})%i', $ytUrl, $matches)) {
                                $videoId = $matches[1];
                            }
                        @endphp

                        <div class="relative pt-[56.25%] bg-black group cursor-pointer">
                            @if($videoId)
                                <iframe class="absolute inset-0 w-full h-full"
                                    src="https://www.youtube.com/embed/{{ $videoId }}" title="YouTube video player"
                                    frameborder="0"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                    allowfullscreen></iframe>
                            @else
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <img alt="Live stream thumbnail"
                                        class="absolute inset-0 w-full h-full object-cover opacity-50 group-hover:opacity-30 transition-opacity"
                                        src="https://placehold.co/600x400/png?text={{ $isLive ? 'No+Live+URL' : 'No+Video+URL' }}" />
                                    <div
                                        class="size-20 bg-primary/90 rounded-full flex items-center justify-center text-white shadow-2xl z-10 group-hover:scale-110 transition-transform">
                                        <span class="material-symbols-outlined text-4xl ml-1">play_arrow</span>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="p-6">
                            <h4 class="text-white font-black mb-1">
                                {{ $isLive ? ($settings['youtube_live_title'] ?? 'Live Streaming') : 'Video Terbaru' }}
                            </h4>
                            @if($isLive)
                                <p class="text-white/40 text-xs font-bold uppercase tracking-wide">Sedang Tayang</p>
                            @else
                                <p class="text-white/40 text-xs font-bold uppercase tracking-wide">Tonton Video Dokumentasi
                                    Kami</p>
                            @endif
                        </div>
                    </div>

                    <!-- Agenda -->
                    <div class="bg-primary/5 dark:bg-white/5 rounded-2xl p-8 border border-primary/10">
                        <h3 class="font-black text-background-dark dark:text-white mb-8 flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary">event</span>
                            Agenda Terdekat
                        </h3>

                        <ul class="space-y-6">
                            @forelse($agendas ?? [] as $agenda)
                                @php
                                    $months = [
                                        1 => 'JAN',
                                        2 => 'FEB',
                                        3 => 'MAR',
                                        4 => 'APR',
                                        5 => 'MEI',
                                        6 => 'JUN',
                                        7 => 'JUL',
                                        8 => 'AGU',
                                        9 => 'SEP',
                                        10 => 'OKT',
                                        11 => 'NOV',
                                        12 => 'DES'
                                    ];
                                @endphp
                                <li class="flex gap-6 items-center">
                                    <div
                                        class="bg-primary text-white rounded-xl w-14 h-14 flex flex-col items-center justify-center shrink-0 shadow-lg shadow-primary/20">
                                        <span
                                            class="text-[10px] font-black uppercase">{{ $months[(int) $agenda->date->format('m')] }}</span>
                                        <span
                                            class="text-xl font-black leading-none">{{ $agenda->date->format('d') }}</span>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-black text-background-dark dark:text-white line-clamp-1">
                                            {{ $agenda->title }}
                                        </h4>
                                        <p class="text-[11px] font-bold text-primary/60 mt-1 uppercase">
                                            @if($agenda->time) {{ \Carbon\Carbon::parse($agenda->time)->format('H:i') }} WIB
                                            • @endif {{ $agenda->location }}
                                        </p>
                                    </div>
                                </li>
                            @empty
                                <li class="text-gray-500 text-sm py-4 text-center italic">
                                    Belum ada agenda terdekat.
                                </li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Galeri Section -->
    <section class="py-20 bg-white dark:bg-background-dark">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-12">
                <div>
                    <span class="text-primary font-black tracking-[0.2em] text-xs uppercase">Dokumentasi</span>
                    <h2 class="text-3xl md:text-4xl font-black text-background-dark dark:text-white mt-2">Galeri
                        Kegiatan</h2>
                </div>
                <a href="{{ route('galeri.web') }}" wire:navigate
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary/10 hover:bg-primary text-primary hover:text-white font-bold text-sm rounded-lg transition-all">
                    Lihat Semua
                    <span class="material-symbols-outlined text-lg">arrow_forward</span>
                </a>
            </div>
            @if($galleries->isEmpty())
                <div class="text-center py-16 bg-gray-50 dark:bg-white/5 rounded-2xl">
                    <span class="material-symbols-outlined text-5xl text-gray-300 dark:text-white/20">photo_library</span>
                    <p class="text-gray-500 dark:text-white/50 mt-3">Belum ada galeri</p>
                </div>
            @else
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                    @foreach($galleries as $gallery)
                        <a href="{{ route('galeri.web') }}" wire:navigate
                            class="group relative aspect-square rounded-xl overflow-hidden">
                            @if($gallery->type === 'video')
                                <div class="absolute inset-0 bg-black/40 flex items-center justify-center z-10">
                                    <span class="material-symbols-outlined text-3xl text-white">play_circle</span>
                                </div>
                            @endif
                            <img src="{{ $gallery->display_image }}" alt="{{ $gallery->title }}"
                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity">
                                <div class="absolute bottom-2 left-2 right-2">
                                    <p class="text-white text-xs font-bold line-clamp-1">{{ $gallery->title }}</p>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    <!-- Peta Masjid Section -->
    <section class="py-20 bg-background-light dark:bg-background-dark/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-12">
                <div>
                    <span class="text-primary font-black tracking-[0.2em] text-xs uppercase">Tempat Ibadah</span>
                    <h2 class="text-3xl md:text-4xl font-black text-background-dark dark:text-white mt-2">Masjid &
                        Musholla</h2>
                </div>
                <a href="{{ route('peta-masjid') }}" wire:navigate
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary/10 hover:bg-primary text-primary hover:text-white font-bold text-sm rounded-lg transition-all">
                    Lihat Semua
                    <span class="material-symbols-outlined text-lg">arrow_forward</span>
                </a>
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Map from Settings -->
                <!-- Map Section -->
                <div
                    class="lg:col-span-2 bg-white dark:bg-white/5 rounded-2xl p-2 shadow-sm border border-primary/5 dark:border-white/5 h-[500px] lg:h-[600px] overflow-hidden group relative z-0">

                    <div id="home-map" class="w-full h-full rounded-xl z-10" wire:ignore></div>

                    @if($mosques->isEmpty())
                        <div
                            class="absolute inset-0 flex items-center justify-center bg-gray-100 dark:bg-white/5 rounded-xl z-20 pointer-events-none">
                            <div class="text-center">
                                <span class="material-symbols-outlined text-6xl text-gray-300 dark:text-white/20">map</span>
                                <p class="text-gray-500 dark:text-white/50 mt-4">Belum ada data masjid (Koordinat)</p>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Mosque List -->
                <div class="lg:col-span-1">
                    <div
                        class="bg-white dark:bg-white/5 rounded-2xl shadow-sm border border-primary/5 dark:border-white/5 overflow-hidden flex flex-col h-[500px] lg:h-[600px]">
                        <div class="p-6 border-b border-primary/5 dark:border-white/5 bg-gray-50/50 dark:bg-white/5">
                            <h3 class="font-bold text-lg text-background-dark dark:text-white flex items-center gap-2">
                                <span class="material-symbols-outlined text-primary">list</span>
                                Daftar Masjid ({{ $mosques->count() }})
                            </h3>
                        </div>

                        <div class="flex-1 overflow-y-auto p-4 space-y-3 custom-scrollbar">
                            @forelse($mosques as $mosque)
                                <div
                                    class="bg-gray-50 dark:bg-white/5 p-4 rounded-xl border border-primary/5 dark:border-white/5 hover:border-primary/30 transition-colors group">
                                    <div class="flex items-start gap-4">
                                        <div
                                            class="w-10 h-10 bg-white dark:bg-white/10 rounded-lg flex items-center justify-center flex-shrink-0 shadow-sm text-primary">
                                            <span
                                                class="material-symbols-outlined text-xl">{{ $mosque->type === 'musholla' ? 'home_work' : 'mosque' }}</span>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex justify-between items-start">
                                                <h4
                                                    class="font-bold text-sm text-background-dark dark:text-white line-clamp-1 group-hover:text-primary transition-colors">
                                                    {{ $mosque->name }}
                                                </h4>
                                                <span
                                                    class="text-[10px] font-bold uppercase px-2 py-0.5 rounded bg-primary/10 text-primary">{{ $mosque->type }}</span>
                                            </div>
                                            <p class="text-xs text-gray-500 dark:text-white/50 mt-0.5 line-clamp-2">
                                                {{ $mosque->address }}
                                            </p>

                                            @if($mosque->latitude && $mosque->longitude)
                                                <a href="https://www.google.com/maps/dir/?api=1&destination={{ $mosque->latitude }},{{ $mosque->longitude }}"
                                                    target="_blank"
                                                    class="inline-flex items-center gap-1 text-[10px] font-bold text-primary hover:text-accent mt-2 uppercase tracking-wide">
                                                    <span class="material-symbols-outlined text-[14px]">directions</span>
                                                    Petunjuk Arah
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-12">
                                    <span
                                        class="material-symbols-outlined text-4xl text-gray-300 dark:text-white/20 mb-2">location_off</span>
                                    <p class="text-sm text-gray-500 dark:text-white/50">Belum ada data masjid</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Financial Transparency -->
    <section class="py-20 bg-primary dark:bg-primary-dark text-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-accent/10 rounded-full -mr-32 -mt-32 blur-3xl"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="flex flex-col md:flex-row items-center justify-between gap-12">
                <div class="md:w-1/2">
                    <span class="text-accent font-black text-xs uppercase tracking-[0.3em] mb-4 block">Amanah &amp;
                        Transparan</span>
                    <h2 class="text-4xl font-black mb-6 leading-tight">Transparansi Keuangan Umat</h2>
                    <p class="text-white/80 mb-10 text-lg leading-relaxed">Komitmen kami dalam menjaga amanah jamaah
                        dengan pelaporan keuangan digital yang realtime dan akuntabel.</p>
                    <a href="{{ route('kas-digital') }}" wire:navigate
                        class="inline-block bg-accent text-background-dark font-black py-4 px-10 rounded-lg hover:bg-white transition-all transform hover:scale-105 shadow-2xl">
                        LIHAT LAPORAN KAS
                    </a>
                </div>
                <div class="md:w-1/2 w-full">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div class="bg-white/10 backdrop-blur-md rounded-2xl p-8 border border-white/20 shadow-xl">
                            <div
                                class="size-10 bg-accent/20 rounded-lg flex items-center justify-center mb-4 text-accent">
                                <span class="material-symbols-outlined font-bold">payments</span>
                            </div>
                            <p class="text-white/70 text-xs font-black uppercase tracking-widest mb-2">Pemasukan Bulan
                                Ini
                            </p>
                            <p class="text-3xl font-black text-white">Rp
                                {{ number_format($totalInfaq / 1000000, 1, ',', '.') }} jt
                            </p>
                        </div>
                        <div class="bg-white/10 backdrop-blur-md rounded-2xl p-8 border border-white/20 shadow-xl">
                            <div
                                class="size-10 bg-accent/20 rounded-lg flex items-center justify-center mb-4 text-accent">
                                <span class="material-symbols-outlined font-bold">volunteer_activism</span>
                            </div>
                            <p class="text-white/70 text-xs font-black uppercase tracking-widest mb-2">Total Pengeluaran
                            </p>
                            <p class="text-3xl font-black text-white">Rp
                                {{ number_format($totalZakat / 1000000, 1, ',', '.') }} jt
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Donation Modal -->
    <div x-show="showDonationModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto"
        aria-labelledby="modal-title" role="dialog" aria-modal="true">

        <!-- Background backdrop -->
        <div x-show="showDonationModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-gray-900/80 backdrop-blur-md transition-opacity" @click="showDonationModal = false">
        </div>

        <!-- Modal Positioning Wrapper -->
        <div class="flex min-h-screen items-center justify-center p-4 text-center sm:p-6">

            <!-- Modal panel -->
            <div x-show="showDonationModal" x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="relative transform overflow-hidden rounded-3xl bg-white dark:bg-[#161615] text-left shadow-2xl transition-all w-full max-w-md mx-auto border border-white/10"
                @click.stop>

                <!-- Close Button -->
                <div class="absolute right-4 top-4 z-20">
                    <button @click="showDonationModal = false" type="button"
                        class="group rounded-full bg-black/20 dark:bg-white/10 p-2 text-white hover:bg-red-500 hover:text-white transition-all focus:outline-none backdrop-blur-sm">
                        <span
                            class="material-symbols-outlined text-xl group-hover:rotate-90 transition-transform">close</span>
                    </button>
                </div>

                <!-- 1. Campaign Slider -->
                <div class="relative w-full h-48 sm:h-56 overflow-hidden bg-gray-900 group">
                    <div x-data="campaignSlider()" x-init="init()" @mouseenter="!isSliderLocked && stopTimer()"
                        @mouseleave="!isSliderLocked && startTimer()" @lock-slider.window="forceLock()"
                        @unlock-slider.window="unlock()" class="w-full h-full relative">

                        <template x-for="(slide, index) in donationSlides" :key="index">
                            <div x-show="activeSlide === index"
                                x-transition:enter="transition transform duration-1000 ease-in-out"
                                x-transition:enter-start="scale-110 opacity-0"
                                x-transition:enter-end="scale-100 opacity-100"
                                x-transition:leave="transition transform duration-1000 ease-in-out absolute"
                                x-transition:leave-start="scale-100 opacity-100"
                                x-transition:leave-end="scale-100 opacity-0" class="absolute inset-0 w-full h-full">
                                <img :src="slide.image" class="w-full h-full object-cover opacity-80" :alt="slide.title"
                                    crossOrigin="anonymous">
                                <!-- Gradient Overlay -->
                                <div
                                    class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-[#161615] via-[#161615]/70 to-transparent p-6 pt-20">
                                    <span
                                        class="bg-accent text-background-dark text-[10px] font-black px-3 py-1.5 rounded-full shadow-lg uppercase tracking-widest mb-2 inline-block drop-shadow-md">Program
                                        Unggulan</span>
                                    <h3 class="text-white font-black text-xl sm:text-2xl mb-1 leading-tight shadow-black drop-shadow-md"
                                        x-text="slide.title"></h3>
                                </div>
                            </div>
                        </template>

                        <!-- Navigation Arrows -->
                        <button @click="prevSlide()" x-show="!isSliderLocked"
                            class="absolute left-4 top-1/2 -translate-y-1/2 w-10 h-10 bg-black/30 hover:bg-black/50 hover:scale-110 backdrop-blur-sm rounded-full flex items-center justify-center text-white transition-all z-20 group-hover:opacity-100 opacity-0 sm:opacity-100">
                            <span class="material-symbols-outlined text-2xl font-bold">chevron_left</span>
                        </button>
                        <button @click="nextSlide(true)" x-show="!isSliderLocked"
                            class="absolute right-4 top-1/2 -translate-y-1/2 w-10 h-10 bg-black/30 hover:bg-black/50 hover:scale-110 backdrop-blur-sm rounded-full flex items-center justify-center text-white transition-all z-20 group-hover:opacity-100 opacity-0 sm:opacity-100">
                            <span class="material-symbols-outlined text-2xl font-bold">chevron_right</span>
                        </button>

                        <!-- Indicators -->
                        <div class="absolute bottom-4 right-4 flex gap-1.5 z-10" x-show="!isSliderLocked">
                            <template x-for="(slide, index) in donationSlides" :key="index">
                                <button @click="goToSlide(index)"
                                    class="h-1.5 rounded-full transition-all duration-300 shadow-sm backdrop-blur-sm"
                                    :class="activeSlide === index ? 'w-6 bg-accent' : 'w-1.5 bg-white/50 hover:bg-white'">
                                </button>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- Modal Body with Steps -->
                <div class="p-6" x-data="{ 
                    step: 1,
                    campaignName: window.donationCampaignsData?.[0]?.title || 'Program Donasi',
                    nominal: 50000, 
                    customNominal: '', 
                    paymentType: 'qris',
                    selectedBank: null,
                    selectedEwallet: window.availableEwallets[0] || 'OVO',
                    userInfo: { name: '', phone: '', region: '', anonymous: false },
                    todayDate: new Date().toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' }),
                    donationId: 'DNS-' + Math.floor(Math.random() * 1000000),
                    
                    get totalAmount() {
                        return this.customNominal ? this.customNominal : this.nominal;
                    },

                    validateAndNext() {
                        let amount = this.totalAmount;
                        if (!amount || amount < 10000) {
                            alert('Mohon masukkan nominal minimal Rp 10.000');
                            return;
                        }
                        if (this.paymentType === 'bank' && !this.selectedBank) {
                            alert('Mohon pilih bank tujuan transfer');
                            return;
                        }
                        if (!this.userInfo.name && !this.userInfo.anonymous) {
                            alert('Mohon isi nama lengkap atau centang Hamba Allah');
                            return;
                        }
                        if (!this.userInfo.region) {
                            alert('Mohon pilih Wilayah / Ranting');
                            return;
                        }
                        this.step = 2;
                        $dispatch('lock-slider');
                    },
                    
                    completePayment() {
                        let payload = {
                            campaign_name: this.campaignName,
                            amount: this.totalAmount,
                            donor_name: this.userInfo.name,
                            donor_phone: this.userInfo.phone,
                            region_id: this.userInfo.region,
                            is_anonymous: this.userInfo.anonymous,
                            payment_method: this.paymentType === 'qris' ? 'QRIS' : 'Transfer',
                            bank_name: this.paymentType === 'bank' ? this.selectedBank : null,
                        };

                        // Use Livewire to save data
                        $wire.saveDonation(payload).then(transactionId => {
                            if (transactionId) {
                                this.donationId = transactionId;
                                this.step = 3;
                            } else {
                                alert('Terjadi kesalahan saat menyimpan data donasi. Silakan coba lagi.');
                            }
                        }).catch(error => {
                            console.error('Error saving donation:', error);
                            alert('Terjadi kesalahan. Mohon periksa koneksi internet Anda.');
                        });
                    },

                    downloadProof() {
                        window.downloadDonationProof(this);
                    },

                    confirmWA() {
                        let amount = new Intl.NumberFormat('id-ID').format(this.totalAmount);
                        let name = this.userInfo.anonymous ? 'Hamba Allah' : this.userInfo.name;
                        let method = this.paymentType === 'qris' ? ('QRIS ' + this.selectedEwallet) : this.selectedBank;
                        let message = `*Konfirmasi Donasi Baru*%0A%0ATujuan: ${this.campaignName}%0AID Donasi:
                        ${this.donationId}%0ATanggal: ${this.todayDate}%0ANama: ${name}%0ANominal: Rp ${amount}%0AMetode:
                        ${method}%0A%0AMohon dicek. Terima kasih.`;

                        // Use double quotes for JS string to avoid conflict with single quotes in PHP
                        let phone =
                        '{{ \App\Models\Setting::where('key', 'donation_contact_person')->value('value') ?? '6282123456789' }}';
                        window.open(`https://wa.me/${phone}?text=${message}`, '_blank');
                    }
                }" @campaign-changed.window="campaignName = $event.detail">

                    <!-- STEP 1: INPUT DATA -->
                    <div x-show="step === 1" x-transition:enter="transition ease-out duration-300">

                        <!-- Donation Purpose Input (Auto-filled) -->
                        <div class="mb-6">
                            <label
                                class="block text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-2">Tujuan
                                Donasi</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <span
                                        class="material-symbols-outlined text-primary text-sm font-bold">volunteer_activism</span>
                                </div>
                                <input type="text" x-model="campaignName" readonly
                                    class="w-full pl-10 pr-4 py-3 bg-gray-100 dark:bg-white/5 border border-transparent rounded-xl text-gray-900 dark:text-gray-100 font-bold text-sm cursor-not-allowed opacity-80">
                            </div>
                        </div>

                        <!-- 2. Nominal Selection -->
                        <div class="mb-6">


                            <div class="flex items-center justify-between mb-3">
                                <label
                                    class="text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest">Pilih
                                    Nominal Infaq</label>
                                <span
                                    class="text-[10px] text-primary font-bold cursor-pointer hover:underline bg-primary/10 px-2 py-0.5 rounded-full">MINIMAL
                                    RP 10.000</span>
                            </div>

                            <div class="grid grid-cols-2 gap-3 mb-3">
                                <template x-for="amount in [10000, 20000, 50000, 100000]">
                                    <button @click="nominal = amount; customNominal = ''"
                                        class="relative px-2 py-3 rounded-xl border text-sm font-black transition-all duration-200 group flex flex-col items-center justify-center gap-1"
                                        :class="nominal === amount && !customNominal ? 'bg-primary border-primary text-white shadow-lg shadow-primary/20 scale-[1.02]' : 'bg-gray-50 dark:bg-white/5 border-transparent text-gray-600 dark:text-gray-300 hover:border-primary/30 hover:bg-primary/5 dark:hover:bg-white/10'">
                                        <span x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(amount)"></span>
                                        <div x-show="nominal === amount && !customNominal"
                                            class="absolute -top-2 -right-2 bg-accent text-background-dark rounded-full p-0.5 shadow-sm">
                                            <span
                                                class="material-symbols-outlined text-[14px] font-black block">check</span>
                                        </div>
                                    </button>
                                </template>
                            </div>

                            <!-- Custom Nominal -->
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <span
                                        class="text-gray-400 group-focus-within:text-primary font-bold transition-colors text-xs">Rp</span>
                                </div>
                                <input type="number" x-model="customNominal" @input="nominal = 0"
                                    placeholder="Masukkan nominal lainnya..."
                                    class="w-full pl-10 pr-4 py-3 bg-gray-50 dark:bg-white/5 border border-transparent focus:bg-white dark:focus:bg-black/20 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent text-gray-900 dark:text-gray-100 font-bold placeholder-gray-400 text-sm transition-all">
                            </div>
                        </div>

                        <!-- 3. Donor Info -->
                        <div class="mb-6 space-y-3">
                            <label
                                class="block text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest">Data
                                Donatur</label>

                            <div class="grid grid-cols-1 gap-3">
                                <div class="group">
                                    <input type="text" x-model="userInfo.name" placeholder="Nama Lengkap"
                                        class="w-full px-4 py-3 bg-gray-50 dark:bg-white/5 border border-transparent focus:bg-white dark:focus:bg-black/20 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent text-gray-900 dark:text-gray-100 text-xs font-medium transition-all">
                                </div>
                                <div class="group">
                                    <input type="tel" x-model="userInfo.phone" placeholder="Nomor WhatsApp (Opsional)"
                                        class="w-full px-4 py-3 bg-gray-50 dark:bg-white/5 border border-transparent focus:bg-white dark:focus:bg-black/20 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent text-gray-900 dark:text-gray-100 text-xs font-medium transition-all">
                                </div>
                                <div class="group">
                                    <select x-model="userInfo.region"
                                        class="w-full px-4 py-3 bg-gray-50 dark:bg-white/5 border border-transparent focus:bg-white dark:focus:bg-black/20 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent text-gray-900 dark:text-gray-100 text-xs font-medium transition-all cursor-pointer">
                                        <option value="" disabled selected class="text-gray-500 bg-white">Pilih Wilayah
                                            / Ranting</option>
                                        @foreach($regions as $region)
                                            <option value="{{ $region->id }}" class="text-gray-900 bg-white">
                                                {{ $region->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 cursor-pointer group"
                                @click="userInfo.anonymous = !userInfo.anonymous">
                                <div class="relative flex items-center justify-center p-0.5 w-5 h-5 rounded border transition-all"
                                    :class="userInfo.anonymous ? 'bg-primary border-primary' : 'border-gray-300 bg-gray-50'">
                                    <span x-show="userInfo.anonymous"
                                        class="material-symbols-outlined text-[14px] text-white font-bold">check</span>
                                </div>
                                <label
                                    class="text-xs text-gray-600 dark:text-gray-400 font-medium cursor-pointer group-hover:text-primary transition-colors">Sembunyikan
                                    nama saya (Hamba Allah)</label>
                            </div>
                        </div>

                        <!-- 4. Payment Method -->
                        <div class="mb-6">
                            <label
                                class="block text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-3">Metode
                                Pembayaran</label>

                            <!-- Tabs -->
                            <div class="grid grid-cols-2 gap-2 mb-4 p-1 bg-gray-100 dark:bg-white/5 rounded-xl">
                                <button @click="paymentType = 'qris'"
                                    class="py-2.5 px-4 rounded-lg text-xs font-bold transition-all flex items-center justify-center gap-2"
                                    :class="paymentType === 'qris' ? 'bg-white dark:bg-[#161615] shadow-sm text-primary' : 'text-gray-500 hover:text-gray-700 dark:hover:text-gray-300'">
                                    <span class="material-symbols-outlined text-[18px]">qr_code_scanner</span>
                                    QRIS (E-Wallet)
                                </button>
                                <button @click="paymentType = 'bank'"
                                    class="py-2.5 px-4 rounded-lg text-xs font-bold transition-all flex items-center justify-center gap-2"
                                    :class="paymentType === 'bank' ? 'bg-white dark:bg-[#161615] shadow-sm text-primary' : 'text-gray-500 hover:text-gray-700 dark:hover:text-gray-300'">
                                    <span class="material-symbols-outlined text-[18px]">account_balance</span>
                                    Transfer Bank
                                </button>
                            </div>

                            <div x-show="paymentType === 'qris'"
                                class="bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl p-4">

                                <!-- E-Wallet Tab Buttons -->
                                <div class="flex items-center justify-center gap-2 mb-4">
                                    <template x-for="wallet in window.availableEwallets" :key="wallet">
                                        <button @click="selectedEwallet = wallet"
                                            class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all"
                                            :class="selectedEwallet === wallet ? 'bg-primary text-white shadow-md' : 'bg-gray-100 dark:bg-white/10 text-gray-600 dark:text-gray-300 hover:bg-gray-200'">
                                            <span x-text="wallet"></span>
                                        </button>
                                    </template>
                                </div>

                                <!-- QR Code Display -->
                                <div class="text-center mb-4">
                                    <template x-if="window.donationEwalletData[selectedEwallet]?.qr">
                                        <div>
                                            <img :src="window.donationEwalletData[selectedEwallet].qr"
                                                :alt="'QR Code ' + selectedEwallet"
                                                class="w-48 h-48 mx-auto rounded-xl border-2 border-gray-200 dark:border-white/20 object-contain bg-white p-2">
                                            <p class="text-xs text-gray-500 mt-2">Scan QR Code <span
                                                    x-text="selectedEwallet" class="font-bold"></span></p>
                                        </div>
                                    </template>
                                    <template x-if="!window.donationEwalletData[selectedEwallet]?.qr">
                                        <div class="py-8 text-center text-gray-400">
                                            <span class="material-symbols-outlined text-4xl">qr_code_2</span>
                                            <p class="text-xs mt-2">QR Code <span x-text="selectedEwallet"></span> belum
                                                tersedia</p>
                                        </div>
                                    </template>
                                </div>

                                <!-- E-Wallet Number -->
                                <div
                                    class="flex items-center justify-between bg-gray-50 dark:bg-white/5 p-3 rounded-lg">
                                    <div>
                                        <p class="text-[10px] text-gray-400 uppercase">Nomor <span
                                                x-text="selectedEwallet"></span></p>
                                        <p class="text-sm font-mono font-bold text-gray-700 dark:text-gray-300"
                                            x-text="window.donationEwalletData[selectedEwallet]?.number || '-'"></p>
                                    </div>
                                    <button
                                        @click="window.copyToClipboard(window.donationEwalletData[selectedEwallet]?.number || '', $el)"
                                        class="text-primary text-xs font-bold hover:underline px-3 py-1 rounded bg-primary/10">SALIN</button>
                                </div>
                            </div>

                            <!-- Bank Transfer Content -->
                            <div x-show="paymentType === 'bank'" class="space-y-2">
                                <template x-for="bank in window.availableBanks" :key="bank">
                                    <button @click="selectedBank = bank"
                                        class="w-full flex items-center justify-between p-3 rounded-xl border transition-all hover:bg-gray-50 dark:hover:bg-white/5"
                                        :class="selectedBank === bank ? 'border-primary bg-primary/5 dark:bg-primary/10 ring-1 ring-primary' : 'border-gray-200 dark:border-white/10'">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-[10px] font-bold text-gray-600"
                                                x-text="bank"></div>
                                            <div class="text-left">
                                                <p class="text-xs font-bold text-gray-800 dark:text-gray-200"
                                                    x-text="window.donationBankData[bank]?.name || ('Transfer ' + bank)">
                                                </p>
                                                <p class="text-[10px] text-gray-500">Manual Check</p>
                                            </div>
                                        </div>
                                        <div x-show="selectedBank === bank" class="text-primary">
                                            <span class="material-symbols-outlined text-[18px]">check_circle</span>
                                        </div>
                                    </button>
                                </template>
                            </div>
                        </div>

                        <!-- Total & Action -->
                        <div class="border-t border-gray-100 dark:border-white/10 pt-4 mt-2">
                            <div class="flex items-center justify-between mb-4">
                                <span class="text-xs font-bold text-gray-500 dark:text-gray-400">Total Donasi</span>
                                <span class="text-xl font-black text-primary"
                                    x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(totalAmount)"></span>
                            </div>

                            <button @click="validateAndNext()"
                                class="w-full bg-primary hover:bg-primary-dark text-white font-black py-4 rounded-xl shadow-xl shadow-primary/30 hover:shadow-2xl hover:-translate-y-1 transition-all flex items-center justify-center gap-2 group relative overflow-hidden">
                                <span
                                    class="absolute inset-0 w-full h-full bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full group-hover:animate-shimmer"></span>
                                <span>LANJUT PEMBAYARAN</span>
                                <span
                                    class="material-symbols-outlined group-hover:translate-x-1 transition-transform font-bold">arrow_forward</span>
                            </button>
                        </div>
                    </div>

                    <!-- STEP 2: PAYMENT INSTRUCTION -->
                    <div x-show="step === 2" x-transition:enter="transition ease-out duration-300"
                        style="display: none;">
                        <div class="text-center mb-6">
                            <h3 class="text-xl font-black text-gray-800 dark:text-white mb-2">Selesaikan Pembayaran</h3>
                            <p class="text-xs text-gray-500">Silakan transfer sesuai nominal di bawah ini</p>
                        </div>

                        <div
                            class="bg-gray-50 dark:bg-white/5 rounded-2xl p-6 border border-gray-200 dark:border-white/10 mb-6 text-center">
                            <p class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Total Tagihan</p>
                            <h2 class="text-3xl font-black text-primary mb-4"
                                x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(totalAmount)"></h2>

                            <!-- QRIS Display -->
                            <div x-show="paymentType === 'qris'" class="mb-4">
                                <template x-if="window.donationEwalletData[selectedEwallet]?.qr">
                                    <div class="bg-white p-4 rounded-xl inline-block shadow-sm">
                                        <img :src="window.donationEwalletData[selectedEwallet].qr"
                                            :alt="'QRIS ' + selectedEwallet"
                                            class="w-48 h-48 object-contain mix-blend-multiply" crossOrigin="anonymous">
                                        <p class="text-[10px] text-gray-400 mt-2 uppercase font-bold"
                                            x-text="'QRIS ' + selectedEwallet"></p>
                                    </div>
                                </template>
                                <template x-if="!window.donationEwalletData[selectedEwallet]?.qr">
                                    <div class="py-12 bg-white/5 rounded-xl border border-dashed border-white/10">
                                        <span
                                            class="material-symbols-outlined text-4xl text-gray-500 mb-2">qr_code_2</span>
                                        <p class="text-xs text-gray-500"
                                            x-text="'QR Code ' + selectedEwallet + ' tidak tersedia'"></p>
                                    </div>
                                </template>
                            </div>

                            <!-- Bank Display -->
                            <div x-show="paymentType === 'bank'" class="text-left space-y-4">
                                <!-- Tujuan Donasi -->
                                <div
                                    class="bg-white dark:bg-black/20 p-4 rounded-xl border border-gray-200 dark:border-white/10">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center">
                                            <span
                                                class="material-symbols-outlined text-primary text-xl">volunteer_activism</span>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-500">Tujuan Donasi</p>
                                            <p class="font-bold text-gray-800 dark:text-white" x-text="campaignName">
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div
                                    class="bg-white dark:bg-black/20 p-4 rounded-xl border border-gray-200 dark:border-white/10">
                                    <div class="flex items-center gap-3 mb-2">
                                        <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center font-bold text-blue-700"
                                            x-text="selectedBank"></div>
                                        <div>
                                            <p class="text-xs text-gray-500">Bank Tujuan</p>
                                            <p class="font-bold text-gray-800 dark:text-white"
                                                x-text="window.donationBankData[selectedBank]?.name || ('Bank ' + selectedBank)">
                                            </p>
                                        </div>
                                    </div>
                                    <div class="mt-4 pt-4 border-t border-gray-100 dark:border-white/5">
                                        <p class="text-xs text-gray-500 mb-1">Nomor Rekening</p>
                                        <div class="flex items-center justify-between">
                                            <p class="text-xl font-mono font-bold text-gray-800 dark:text-white tracking-wider"
                                                x-text="window.donationBankData[selectedBank]?.account || '-'"></p>
                                            <button @click="
                                                    let acc = (window.donationBankData[selectedBank]?.account || '').replace(/\s/g, '');
                                                    if (acc && acc !== '-') {
                                                        window.copyToClipboard(acc, $el);
                                                    }
                                                " class="text-primary text-xs font-bold hover:underline">SALIN</button>
                                        </div>
                                        <p class="text-xs text-gray-400 mt-1"
                                            x-text="'a.n ' + (window.donationBankData[selectedBank]?.owner || 'PRNU Baktijaya')">
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <button @click="completePayment()"
                                class="w-full bg-primary hover:bg-primary-dark text-white font-black py-4 rounded-xl shadow-lg transition-all">
                                SAYA SUDAH TRANSFER
                            </button>
                            <button @click="step = 1; $dispatch('unlock-slider')"
                                class="w-full bg-transparent text-gray-500 font-bold py-3 text-sm hover:text-gray-700">
                                Kembali ke Data Diri
                            </button>
                        </div>
                    </div>

                    <!-- STEP 3: SUCCESS & PROOF -->
                    <div x-show="step === 3" x-transition:enter="transition ease-out duration-300"
                        style="display: none;">
                        <div class="text-center">
                            <div
                                class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                                <span class="material-symbols-outlined text-4xl text-green-600">check_circle</span>
                            </div>
                            <h3 class="text-2xl font-black text-gray-800 dark:text-white mb-2">Terima Kasih!</h3>
                            <p class="text-sm text-gray-500 mb-8 max-w-xs mx-auto">Donasi Anda telah kami catat. Silakan
                                kirim bukti transfer agar segera diverifikasi.</p>

                            <!-- RECEIPT CARD for Download -->
                            <div id="donation-receipt"
                                class="bg-white text-gray-800 p-8 rounded-2xl shadow-sm border border-gray-200 mb-8 max-w-sm mx-auto relative overflow-hidden">
                                <div class="absolute top-0 left-0 w-full h-2 bg-primary"></div>
                                <div class="flex justify-between items-start mb-6">
                                    <div class="text-left">
                                        <h4 class="font-black text-lg text-primary">BUKTI DONASI</h4>
                                        <p class="text-[10px] text-gray-500">PRNU Baktijaya</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-[10px] text-gray-400" x-text="todayDate"></p>
                                        <p class="text-xs font-mono font-bold text-gray-600" x-text="donationId"></p>
                                    </div>
                                </div>
                                <div class="text-center py-6 border-y border-dashed border-gray-200 my-4">
                                    <p class="text-xs text-gray-500 mb-1">Nominal Donasi</p>
                                    <h2 class="text-3xl font-black text-gray-800"
                                        x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(totalAmount)"></h2>
                                </div>
                                <div class="text-left space-y-3 text-sm">
                                    <div class="flex justify-between items-start gap-4">
                                        <span class="text-gray-500 whitespace-nowrap">Tujuan</span>
                                        <span class="font-bold text-right leading-tight" x-text="campaignName"></span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-500">Nama</span>
                                        <span class="font-bold"
                                            x-text="userInfo.anonymous ? 'Hamba Allah' : userInfo.name"></span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-500">Metode</span>
                                        <span class="font-bold uppercase"
                                            x-text="paymentType === 'qris' ? 'QRIS' : ('Transfer ' + selectedBank)"></span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-500">Status</span>
                                        <span class="font-bold text-orange-500">Menunggu Verifikasi</span>
                                    </div>
                                </div>
                                <div class="mt-8 pt-4 border-t border-gray-100 text-center">
                                    <p class="text-[10px] text-gray-400">Jazakumullah Khairan Katsiran</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <button @click="downloadProof()"
                                    class="w-full bg-gray-100 hover:bg-gray-200 text-gray-800 font-bold py-3 rounded-xl flex items-center justify-center gap-2 transition-all">
                                    <span class="material-symbols-outlined text-lg">download</span>
                                    <span>Simpan Bukti</span>
                                </button>
                                <button @click="confirmWA()"
                                    class="w-full bg-[#25D366] hover:bg-[#128C7E] py-3 rounded-xl flex items-center justify-center gap-2 transition-all shadow-lg shadow-green-500/20">
                                    <img src="https://upload.wikimedia.org/wikipedia/commons/6/6b/WhatsApp.svg"
                                        class="w-5 h-5 brightness-0 invert" alt="WA">
                                    <span style="color: #044f52ff !important; font-weight: 800;">Konfirmasi WA</span>
                                </button>
                            </div>
                            <!-- Kembali ke Beranda button -->
                            <button @click="showDonationModal = false; step = 1; $dispatch('unlock-slider')"
                                class="w-full mt-4 bg-transparent text-gray-500 font-bold py-3 text-sm hover:text-gray-700 flex items-center justify-center gap-2">
                                <span class="material-symbols-outlined text-lg">home</span>
                                Kembali ke Beranda
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
        <script>
            window.downloadDonationProof = function (data) {
                // 1. Prepare Data
                const amount = new Intl.NumberFormat('id-ID').format(data.totalAmount);
                const name = data.userInfo.anonymous ? 'Hamba Allah' : data.userInfo.name;
                const method = data.paymentType === 'qris' ? 'QRIS' : ('Transfer ' + data.selectedBank);
                const date = data.todayDate;
                const id = data.donationId;
                const campaign = data.campaignName;

                // 2. Create Clean Container for Capture
                const container = document.createElement('div');
                Object.assign(container.style, {
                    position: 'fixed',
                    top: '-9999px',
                    left: '-9999px',
                    width: '400px',
                    padding: '30px',
                    backgroundColor: '#ffffff',
                    fontFamily: 'sans-serif',
                    border: '1px solid #e5e7eb',
                    borderRadius: '16px',
                    color: '#1f2937',
                    zIndex: '-9999'
                });

                // 3. Construct HTML with safe Inline Styles (HEX colors only)
                container.innerHTML = `
                                                                                                        <div style="width: 100%; height: 8px; background-color: #064e3b; position: absolute; top: 0; left: 0;"></div>

                                                                                                        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 24px;">
                                                                                                            <div>
                                                                                                                <h4 style="font-weight: 900; font-size: 18px; color: #064e3b; margin: 0;">BUKTI DONASI</h4>
                                                                                                                <p style="font-size: 10px; color: #6b7280; margin: 0;">PRNU Baktijaya</p>
                                                                                                            </div>
                                                                                                            <div style="text-align: right;">
                                                                                                                <p style="font-size: 10px; color: #9ca3af; margin: 0;">${date}</p>
                                                                                                                <p style="font-size: 12px; font-family: monospace; font-weight: bold; color: #4b5563; margin: 0;">${id}</p>
                                                                                                            </div>
                                                                                                        </div>

                                                                                                        <div style="text-align: center; padding: 24px 0; border-top: 1px dashed #e5e7eb; border-bottom: 1px dashed #e5e7eb; margin: 16px 0;">
                                                                                                            <p style="font-size: 12px; color: #6b7280; margin: 0 0 4px 0;">Nominal Donasi</p>
                                                                                                            <h2 style="font-size: 30px; font-weight: 900; color: #1f2937; margin: 0;">Rp ${amount}</h2>
                                                                                                        </div>

                                                                                                        <div style="font-size: 14px; color: #374151;">
                                                                                                            <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                                                                                                                <span style="color: #6b7280;">Tujuan</span>
                                                                                                                <span style="font-weight: bold; text-align: right; max-width: 60%;">${campaign}</span>
                                                                                                            </div>
                                                                                                            <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                                                                                                                <span style="color: #6b7280;">Nama</span>
                                                                                                                <span style="font-weight: bold;">${name}</span>
                                                                                                            </div>
                                                                                                            <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                                                                                                                <span style="color: #6b7280;">Metode</span>
                                                                                                                <span style="font-weight: bold; text-transform: uppercase;">${method}</span>
                                                                                                            </div>
                                                                                                            <div style="display: flex; justify-content: space-between;">
                                                                                                                <span style="color: #6b7280;">Status</span>
                                                                                                                <span style="font-weight: bold; color: #f97316;">Menunggu Verifikasi</span>
                                                                                                            </div>
                                                                                                        </div>

                                                                                                        <div style="margin-top: 32px; padding-top: 16px; border-top: 1px solid #f3f4f6; text-align: center;">
                                                                                                            <p style="font-size: 10px; color: #9ca3af; margin: 0;">Jazakumullah Khairan Katsiran</p>
                                                                                                        </div>
                                                                                                    `;

                document.body.appendChild(container);

                // 4. Capture
                html2canvas(container, {
                    scale: 2,
                    backgroundColor: '#ffffff',
                    useCORS: true
                }).then(canvas => {
                    const link = document.createElement('a');
                    link.download = 'Bukti-Donasi-' + data.donationId + '.png';
                    link.href = canvas.toDataURL('image/png');
                    link.click();
                    document.body.removeChild(container);
                }).catch(err => {
                    console.error('Download Error:', err);
                    document.body.removeChild(container);
                    alert('Gagal: ' + err.message);
                });
            };

            document.addEventListener('livewire:navigated', function () {
                initMap();
            });
            document.addEventListener('DOMContentLoaded', function () {
                initMap();
            });

            // Promo Section Slider (1 static + dynamic campaigns)
            function promoSlider() {
                return {
                    activeSlide: 0,
                    campaigns: window.donationCampaignsData || [],
                    timer: null,
                    get totalSlides() {
                        return 1 + this.campaigns.length; // 1 static slide + dynamic campaigns
                    },
                    init() {
                        this.startTimer();
                    },
                    nextSlide() {
                        this.activeSlide = (this.activeSlide + 1) % this.totalSlides;
                    },
                    prevSlide() {
                        this.activeSlide = (this.activeSlide - 1 + this.totalSlides) % this.totalSlides;
                    },
                    goToSlide(index) {
                        this.activeSlide = index;
                    },
                    startTimer() {
                        if (this.totalSlides > 1) {
                            this.timer = setInterval(() => this.nextSlide(), 6000);
                        }
                    }
                };
            }

            // Campaign Slider for Donation Modal
            function campaignSlider() {
                return {
                    activeSlide: 0,
                    donationSlides: window.donationCampaignsData || [],
                    timer: null,
                    autoPlay: true,
                    isSliderLocked: false,
                    init() {
                        this.startTimer();
                        this.updateCampaign();
                    },
                    nextSlide(manual = false) {
                        if (manual) this.lockSlider();
                        if (this.donationSlides.length > 0) {
                            this.activeSlide = (this.activeSlide + 1) % this.donationSlides.length;
                            this.updateCampaign();
                        }
                    },
                    prevSlide() {
                        this.lockSlider();
                        if (this.donationSlides.length > 0) {
                            this.activeSlide = (this.activeSlide - 1 + this.donationSlides.length) % this.donationSlides.length;
                            this.updateCampaign();
                        }
                    },
                    goToSlide(index) {
                        this.lockSlider();
                        this.activeSlide = index;
                        this.updateCampaign();
                    },
                    lockSlider() {
                        this.autoPlay = false;
                        this.stopTimer();
                    },
                    forceLock() {
                        this.lockSlider();
                        this.isSliderLocked = true;
                    },
                    unlock() {
                        this.isSliderLocked = false;
                        this.autoPlay = true;
                        this.startTimer();
                    },
                    updateCampaign() {
                        if (this.donationSlides.length > 0 && this.donationSlides[this.activeSlide]) {
                            this.$dispatch('campaign-changed', this.donationSlides[this.activeSlide].title);
                        }
                    },
                    startTimer() {
                        if (this.autoPlay && this.donationSlides.length > 1) {
                            this.stopTimer();
                            this.timer = setInterval(() => this.nextSlide(), 5000);
                        }
                    },
                    stopTimer() {
                        clearInterval(this.timer);
                    }
                };
            }

            function initMap() {
                var container = document.getElementById('home-map');
                if (container) {
                    if (container._leaflet_id) {
                        container._leaflet_id = null;
                    }
                    if (container.hasChildNodes()) {
                        container.innerHTML = '';
                    }

                    // 1. Initial Setup
                    var defaultCenter = [-6.3827433, 106.8525385]; // Baktijaya
                    var map = L.map('home-map').setView(defaultCenter, 14);

                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                    }).addTo(map);

                    var bounds = [];
                    var addedCoords = new Set();
                    var detectedCount = 0;

                    // 2. Add Database Mosques (Verified)
                    var verifiedMosques = @json($mosques);
                    var settingsAddress = @json($settings['contact_address'] ?? 'Kelurahan Baktijaya, Depok');

                    verifiedMosques.forEach(function (mosque) {
                        if (mosque.latitude && mosque.longitude) {
                            var lat = parseFloat(mosque.latitude);
                            var lng = parseFloat(mosque.longitude);

                            addMarker(lat, lng, mosque.name, mosque.address, mosque.type, true);
                            bounds.push([lat, lng]);
                            addedCoords.add(lat.toFixed(5) + ',' + lng.toFixed(5));
                        }
                    });

                    // 3. Helper to Add Marker
                    function addMarker(lat, lng, name, address, type, isVerified) {
                        var marker = L.marker([lat, lng]).addTo(map);

                        var badgeClass = isVerified
                            ? 'bg-green-100 text-green-700'
                            : 'bg-gray-100 text-gray-700 border border-gray-300';

                        var badgeText = isVerified
                            ? type
                            : 'Terdeteksi Otomatis';

                        var popupContent = '<div class="p-2 min-w-[200px]">' +
                            '<h4 class="font-bold text-gray-900 mb-1">' + name + '</h4>' +
                            '<p class="text-xs text-gray-600 mb-2">' + (address || 'Alamat tidak tersedia') + '</p>' +
                            '<span class="text-[10px] font-bold uppercase px-2 py-0.5 rounded ' + badgeClass + '">' + badgeText + '</span>' +
                            '<a href="https://www.google.com/maps/dir/?api=1&destination=' + lat + ',' + lng + '" target="_blank" class="block mt-2 text-xs text-blue-600 font-bold hover:underline">Petunjuk Arah</a>' +
                            '</div>';

                        marker.bindPopup(popupContent);
                    }

                    // 4. Helper to Add List Item
                    function addListItem(lat, lng, name, address) {
                        var listContainer = document.getElementById('mosque-list');
                        var countSpan = document.getElementById('mosque-count');

                        if (listContainer) {
                            // Remove "No Data" placeholder if it exists
                            var emptyState = listContainer.querySelector('.text-center.py-12');
                            if (emptyState) emptyState.remove();

                            var item = document.createElement('div');
                            item.className = 'bg-gray-50 dark:bg-white/5 p-4 rounded-xl border border-primary/5 dark:border-white/5 hover:border-primary/30 transition-colors group animate-fade-in';
                            item.innerHTML = `
                                                                                                                                                                    <div class="flex items-start gap-4">
                                                                                                                                                                        <div class="w-10 h-10 bg-white dark:bg-white/10 rounded-lg flex items-center justify-center flex-shrink-0 shadow-sm text-gray-500">
                                                                                                                                                                            <span class="material-symbols-outlined text-xl">location_on</span>
                                                                                                                                                                        </div>
                                                                                                                                                                        <div class="flex-1 min-w-0">
                                                                                                                                                                            <div class="flex justify-between items-start">
                                                                                                                                                                                <h4 class="font-bold text-sm text-background-dark dark:text-white line-clamp-1 group-hover:text-primary transition-colors">${name}</h4>
                                                                                                                                                                                <span class="text-[10px] font-bold uppercase px-2 py-0.5 rounded bg-gray-100 text-gray-600 border border-gray-200">Otomatis</span>
                                                                                                                                                                            </div>
                                                                                                                                                                            <p class="text-xs text-gray-500 dark:text-white/50 mt-0.5 line-clamp-2">${address || 'Alamat sekitar area ini'}</p>

                                                                                                                                                                            <a href="https://www.google.com/maps/dir/?api=1&destination=${lat},${lng}" 
                                                                                                                                                                                target="_blank"
                                                                                                                                                                                class="inline-flex items-center gap-1 text-[10px] font-bold text-primary hover:text-accent mt-2 uppercase tracking-wide">
                                                                                                                                                                                <span class="material-symbols-outlined text-[14px]">directions</span>
                                                                                                                                                                                Petunjuk Arah
                                                                                                                                                                            </a>
                                                                                                                                                                        </div>
                                                                                                                                                                    </div>
                                                                                                                                                                `;
                            listContainer.appendChild(item);

                            // Update Count
                            if (countSpan) {
                                var current = parseInt(countSpan.innerText.replace(/[^0-9]/g, '')) || 0;
                                countSpan.innerText = current + 1;
                            }
                        }
                    }

                    // 5. Auto-Discovery Logic
                    var searchCenter = defaultCenter;

                    function runOverpass(lat, lng) {
                        L.circle([lat, lng], {
                            color: 'var(--color-primary)',
                            fillColor: 'var(--color-primary)',
                            fillOpacity: 0.1,
                            radius: 1000
                        }).addTo(map);

                        var radius = 1000;
                        var query = `
                                                                                                                                                                [out:json][timeout:25];
                                                                                                                                                                (
                                                                                                                                                                  node["amenity"="place_of_worship"]["religion"="muslim"](around:${radius},${lat},${lng});
                                                                                                                                                                  way["amenity"="place_of_worship"]["religion"="muslim"](around:${radius},${lat},${lng});
                                                                                                                                                                );
                                                                                                                                                                out center; 
                                                                                                                                                            `;

                        fetch('https://overpass-api.de/api/interpreter', {
                            method: 'POST',
                            body: query
                        })
                            .then(response => response.json())
                            .then(osmData => {
                                if (osmData.elements) {
                                    osmData.elements.forEach(el => {
                                        var lat = el.lat || el.center.lat;
                                        var lon = el.lon || el.center.lon;
                                        var name = el.tags.name || 'Masjid/Musholla (Tanpa Nama)';

                                        var key = lat.toFixed(5) + ',' + lon.toFixed(5);
                                        var isDuplicate = false;

                                        addedCoords.forEach(coord => {
                                            var parts = coord.split(',');
                                            var cLat = parseFloat(parts[0]);
                                            var cLon = parseFloat(parts[1]);
                                            var dLat = Math.abs(cLat - lat);
                                            var dLon = Math.abs(cLon - lon);
                                            if (dLat < 0.0002 && dLon < 0.0002) isDuplicate = true;
                                        });

                                        if (!isDuplicate) {
                                            addMarker(lat, lon, name, '', 'Masjid', false);
                                            addListItem(lat, lon, name, ''); // Add to List
                                            addedCoords.add(key);
                                            bounds.push([lat, lon]);
                                        }
                                    });

                                    if (bounds.length > 0) {
                                        map.fitBounds(bounds, { padding: [50, 50] });
                                    }
                                }
                            })
                            .catch(e => console.error("Overpass Error:", e));
                    }

                    if (settingsAddress) {
                        fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(settingsAddress)}`)
                            .then(response => response.json())
                            .then(data => {
                                if (data && data.length > 0) {
                                    var centerLat = parseFloat(data[0].lat);
                                    var centerLng = parseFloat(data[0].lon);

                                    map.setView([centerLat, centerLng], 15);
                                    runOverpass(centerLat, centerLng);
                                } else {
                                    runOverpass(searchCenter[0], searchCenter[1]);
                                }
                            })
                            .catch(e => {
                                runOverpass(searchCenter[0], searchCenter[1]);
                            });
                    } else {
                        runOverpass(searchCenter[0], searchCenter[1]);
                    }
                }
            }
        </script>
    @endpush
</div>