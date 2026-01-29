<div class="min-h-screen bg-background-light dark:bg-background-dark font-sans text-slate-900 dark:text-gray-100 flex flex-col transition-colors duration-300"
    x-data="{
        activeTab: 'chat',
        showSupportModal: false,
        shareCurrentPage() {
            if (navigator.share) {
                navigator.share({
                    title: '{{ $title }}',
                    text: '{{ $description }}',
                    url: window.location.href,
                })
                .then(() => console.log('Successful share'))
                .catch((error) => console.log('Error sharing', error));
            } else {
                navigator.clipboard.writeText(window.location.href).then(() => {
                    alert('Link berhasil disalin!');
                });
            }
        }
    }">
    <main class="flex-1 max-w-[1440px] mx-auto w-full p-6 lg:p-10">
        <!-- Support Modal -->
        <div x-show="showSupportModal" style="display: none;"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm transition-opacity"
            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

            <div class="bg-white dark:bg-slate-900 rounded-2xl max-w-md w-full shadow-2xl transform transition-all max-h-[85vh] flex flex-col overflow-hidden"
                @click.away="showSupportModal = false" x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95">

                <div class="flex justify-between items-center p-4 pb-2 flex-shrink-0">
                    <h3 class="text-xl font-bold flex items-center gap-2 text-slate-900 dark:text-white">
                        <span class="material-symbols-outlined text-primary">volunteer_activism</span>
                        Dukung Acara
                    </h3>
                    <button @click="showSupportModal = false"
                        class="text-slate-400 hover:text-red-500 transition-colors">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>

                <div class="flex-1 overflow-y-auto px-6 py-2 custom-scrollbar">
                    <div class="flex flex-col items-center text-center space-y-4">
                        <div
                            class="w-48 h-48 bg-white p-2 rounded-xl border border-slate-200 shadow-sm overflow-hidden relative">
                            <!-- Placeholder QRIS -->
                            @if($donationQrisImage)
                                <img src="{{ Str::startsWith($donationQrisImage, 'http') ? $donationQrisImage : Storage::url($donationQrisImage) }}"
                                    alt="QRIS Donasi" class="w-full h-full object-contain">
                            @else
                                <div class="w-full h-full flex flex-col items-center justify-center text-slate-400">
                                    <span class="material-symbols-outlined text-4xl mb-2">qr_code_2</span>
                                    <span class="text-xs">QRIS belum tersedia</span>
                                </div>
                            @endif
                        </div>
                        <div>
                            <p class="text-sm font-bold text-slate-800 dark:text-gray-200">Scan QRIS (Bank / E-Wallet)
                            </p>
                            <p class="text-[10px] text-slate-500 dark:text-gray-400">Dukungan Sat Set via QRIS</p>
                        </div>

                        <div class="space-y-3 w-full">
                            <!-- Primary Bank (BSI usually) -->
                            <div
                                class="w-full bg-slate-50 dark:bg-white/5 p-4 rounded-xl border border-slate-100 dark:border-white/5 text-left flex items-center gap-4">
                                <div
                                    class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0 text-blue-600 font-bold text-[10px] uppercase text-center leading-tight">
                                    {{ substr($donationBankName ?? 'BANK', 0, 4) }}
                                </div>
                                <div class="flex-1">
                                    <p
                                        class="text-[10px] text-slate-500 dark:text-gray-400 font-bold uppercase tracking-wider">
                                        {{ $donationBankName ?? 'Nama Bank' }}
                                    </p>
                                    <p
                                        class="text-base font-black text-slate-800 dark:text-white tracking-widest font-mono">
                                        {{ $donationBankNumber ?? '-' }}
                                    </p>
                                    <p class="text-xs text-slate-600 dark:text-gray-300 font-medium">a.n
                                        {{ $donationBankOwner ?? '-' }}
                                    </p>
                                </div>
                                <button
                                    onclick="navigator.clipboard.writeText('{{ $donationBankNumber }}'); alert('No. Rekening disalin!')"
                                    class="p-2 text-slate-400 hover:text-primary transition-colors">
                                    <span class="material-symbols-outlined text-lg">content_copy</span>
                                </button>
                            </div>

                            <!-- Additional Banks (BRI, BCA, Mandiri) -->
                            @if($donationBankBri && $donationBankBri !== '-')
                                <div
                                    class="w-full bg-slate-50 dark:bg-white/5 p-3 rounded-xl border border-slate-100 dark:border-white/5 text-left flex items-center gap-3">
                                    <div
                                        class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center flex-shrink-0 text-white font-bold text-[8px]">
                                        BRI</div>
                                    <div class="flex-1">
                                        <p class="text-[10px] text-slate-500 dark:text-gray-400 font-bold uppercase">Bank
                                            BRI</p>
                                        <p
                                            class="text-sm font-black text-slate-800 dark:text-white font-mono leading-none mb-0.5">
                                            {{ $donationBankBri }}</p>
                                        <p class="text-[10px] text-slate-500 dark:text-gray-400 font-medium">a.n
                                            {{ $donationBankOwner ?? '-' }}</p>
                                    </div>
                                    <button
                                        onclick="navigator.clipboard.writeText('{{ $donationBankBri }}'); alert('No. Rekening BRI disalin!')"
                                        class="p-1.5 text-slate-400 hover:text-primary transition-colors">
                                        <span class="material-symbols-outlined text-base">content_copy</span>
                                    </button>
                                </div>
                            @endif

                            @if($donationBankBca && $donationBankBca !== '-')
                                <div
                                    class="w-full bg-slate-50 dark:bg-white/5 p-3 rounded-xl border border-slate-100 dark:border-white/5 text-left flex items-center gap-3">
                                    <div
                                        class="w-8 h-8 bg-blue-800 rounded-lg flex items-center justify-center flex-shrink-0 text-white font-bold text-[8px]">
                                        BCA</div>
                                    <div class="flex-1">
                                        <p class="text-[10px] text-slate-500 dark:text-gray-400 font-bold uppercase">Bank
                                            BCA</p>
                                        <p
                                            class="text-sm font-black text-slate-800 dark:text-white font-mono leading-none mb-0.5">
                                            {{ $donationBankBca }}</p>
                                        <p class="text-[10px] text-slate-500 dark:text-gray-400 font-medium">a.n
                                            {{ $donationBankOwner ?? '-' }}</p>
                                    </div>
                                    <button
                                        onclick="navigator.clipboard.writeText('{{ $donationBankBca }}'); alert('No. Rekening BCA disalin!')"
                                        class="p-1.5 text-slate-400 hover:text-primary transition-colors">
                                        <span class="material-symbols-outlined text-base">content_copy</span>
                                    </button>
                                </div>
                            @endif

                            @if($donationBankMandiri && $donationBankMandiri !== '-')
                                <div
                                    class="w-full bg-slate-50 dark:bg-white/5 p-3 rounded-xl border border-slate-100 dark:border-white/5 text-left flex items-center gap-3">
                                    <div
                                        class="w-8 h-8 bg-yellow-500 rounded-lg flex items-center justify-center flex-shrink-0 text-slate-900 font-bold text-[8px]">
                                        MDR</div>
                                    <div class="flex-1">
                                        <p class="text-[10px] text-slate-500 dark:text-gray-400 font-bold uppercase">Bank
                                            Mandiri</p>
                                        <p
                                            class="text-sm font-black text-slate-800 dark:text-white font-mono leading-none mb-0.5">
                                            {{ $donationBankMandiri }}</p>
                                        <p class="text-[10px] text-slate-500 dark:text-gray-400 font-medium">a.n
                                            {{ $donationBankOwner ?? '-' }}</p>
                                    </div>
                                    <button
                                        onclick="navigator.clipboard.writeText('{{ $donationBankMandiri }}'); alert('No. Rekening Mandiri disalin!')"
                                        class="p-1.5 text-slate-400 hover:text-primary transition-colors">
                                        <span class="material-symbols-outlined text-base">content_copy</span>
                                    </button>
                                </div>
                            @endif

                            <!-- E-Wallets (OVO, Gopay) -->
                            @if(($donationEwalletOvo && $donationEwalletOvo !== '-') || ($donationEwalletGopay && $donationEwalletGopay !== '-'))
                                <div class="grid grid-cols-2 gap-3">
                                    @if($donationEwalletOvo && $donationEwalletOvo !== '-')
                                        <div
                                            class="bg-slate-50 dark:bg-white/5 p-3 rounded-xl border border-slate-100 dark:border-white/5 text-left">
                                            <div class="flex items-center gap-2 mb-1">
                                                <div
                                                    class="w-5 h-5 bg-purple-600 rounded-full flex items-center justify-center text-white text-[8px] font-bold">
                                                    O</div>
                                                <span class="text-[10px] font-bold text-slate-500 dark:text-gray-400">OVO</span>
                                            </div>
                                            <p class="text-xs font-black text-slate-800 dark:text-white font-mono mb-1">
                                                {{ $donationEwalletOvo }}
                                            </p>
                                            <button
                                                onclick="navigator.clipboard.writeText('{{ $donationEwalletOvo }}'); alert('Nomor OVO disalin!')"
                                                class="text-[10px] text-primary hover:underline">Salin</button>
                                        </div>
                                    @endif

                                    @if($donationEwalletGopay && $donationEwalletGopay !== '-')
                                        <div
                                            class="bg-slate-50 dark:bg-white/5 p-3 rounded-xl border border-slate-100 dark:border-white/5 text-left">
                                            <div class="flex items-center gap-2 mb-1">
                                                <div
                                                    class="w-5 h-5 bg-green-500 rounded-full flex items-center justify-center text-white text-[8px] font-bold">
                                                    G</div>
                                                <span
                                                    class="text-[10px] font-bold text-slate-500 dark:text-gray-400">Gopay</span>
                                            </div>
                                            <p class="text-xs font-black text-slate-800 dark:text-white font-mono mb-1">
                                                {{ $donationEwalletGopay }}
                                            </p>
                                            <button
                                                onclick="navigator.clipboard.writeText('{{ $donationEwalletGopay }}'); alert('Nomor Gopay disalin!')"
                                                class="text-[10px] text-primary hover:underline">Salin</button>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>

                </div>

                <div class="p-4 pt-2 border-t border-slate-100 dark:border-white/5 flex-shrink-0">
                    <a href="https://wa.me/{{ $donationContact }}?text=Assalamualaikum, saya ingin konfirmasi donasi untuk acara..."
                        target="_blank"
                        class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-3 rounded-xl flex items-center justify-center gap-2 transition-colors shadow-lg shadow-green-500/20">
                        <span class="material-symbols-outlined">whatsapp</span>
                        Konfirmasi Donasi
                    </a>
                </div>
            </div>
        </div>

        <div class="flex flex-col lg:flex-row gap-8">
                <div class="lg:w-2/3 space-y-6">
                    <!-- Video Player -->
                    <div class="relative w-full aspect-video bg-black rounded-2xl overflow-hidden shadow-2xl group">
                        @if($youtubeId)
                            <iframe src="https://www.youtube.com/embed/{{ $youtubeId }}?autoplay=1&mute=0"
                                title="YouTube video player" frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                allowfullscreen class="w-full h-full absolute inset-0">
                            </iframe>
                        @else
                            <img alt="Religious gathering live stream" class="w-full h-full object-cover opacity-90"
                                src="https://lh3.googleusercontent.com/aida-public/AB6AXuBm56GIZrwJ1IH48W7KCFoIYrBi01Srog4ReI6M2RNsmM-IquzO1ui6A7dp6vblMVakohZw9j5I1FGR8hcGDejPrSe6W8ueoQxmkPi11pEcScd9xN8PsGQZ_wYSM6Su8AZSXh7Qt-Il7sTZjx-Yzo2by2EgeVSm7QbyGU73MB_iCbHwqk-ZX7hUQij7EhIjE-6vdqfy8OqEQw971nx28OFkJzs8AC8JG7C133sl_DAFbaTDAcgs34gHIlHBdO10Y6CQebhiKNbNbSM" />
                            <div
                                class="absolute inset-0 flex flex-col justify-between p-6 bg-gradient-to-t from-black/80 via-transparent to-black/40">
                                <div class="flex justify-between items-start">
                                    @if($isLive)
                                        <span
                                            class="bg-red-600 text-white text-xs font-bold px-3 py-1 rounded-md flex items-center gap-2">
                                            <span class="w-2 h-2 bg-white rounded-full animate-pulse"></span>
                                            SEDANG TAYANG
                                        </span>
                                    @else
                                        <span
                                            class="bg-gray-600 text-white text-xs font-bold px-3 py-1 rounded-md flex items-center gap-2">
                                            OFFLINE
                                        </span>
                                    @endif
                                    <div
                                        class="bg-black/60 backdrop-blur-md text-white text-xs px-3 py-1.5 rounded-md flex items-center gap-2">
                                        <span class="material-symbols-outlined text-sm">visibility</span>
                                        1.284 Menonton
                                    </div>
                                </div>
                                <div class="flex items-center justify-center">
                                    <button
                                        class="w-20 h-20 bg-primary/90 text-white rounded-full flex items-center justify-center shadow-2xl hover:scale-110 transition-transform">
                                        <span class="material-symbols-outlined text-5xl ml-1">play_arrow</span>
                                    </button>
                                </div>
                                <div class="flex justify-between items-center text-white">
                                    <div class="flex items-center gap-4">
                                        <span
                                            class="material-symbols-outlined cursor-pointer hover:text-primary transition-colors">volume_up</span>
                                        <div class="h-1.5 w-32 bg-white/20 rounded-full overflow-hidden">
                                            <div class="h-full bg-primary w-3/4"></div>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-6">
                                        <span
                                            class="material-symbols-outlined cursor-pointer hover:text-primary transition-colors">settings</span>
                                        <span
                                            class="material-symbols-outlined cursor-pointer hover:text-primary transition-colors">fullscreen</span>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Video Info -->
                    <div
                        class="bg-white dark:bg-slate-900 p-6 rounded-2xl shadow-sm border border-slate-100 dark:border-white/5">
                        <div class="flex flex-col md:flex-row md:items-start justify-between gap-4">
                            <div class="flex-1">
                                <h2 class="text-2xl lg:text-3xl font-bold text-slate-900 dark:text-white leading-tight">
                                    {{ $title }}
                                </h2>
                                <div class="flex items-center gap-4 mt-4">
                                    <div
                                        class="w-12 h-12 rounded-full overflow-hidden ring-2 ring-primary ring-offset-2 dark:ring-offset-slate-900">
                                        <img alt="Kiyai. Saroham Asymuni, S.Pd.I" class="w-full h-full object-cover"
                                            src="https://lh3.googleusercontent.com/aida-public/AB6AXuAg4azJBetkBkyD_LODwbpE-dzqvivEZBdLutvyb6bkMonZ6wvg0BUrhv6RBMCSfUoyA6tjNAtkGRvhgb9TkTdieSCIcoJ_Ihgx9RWUzko6Ke__ZOUks0_H-Nh5-343MIbwtWs-SKJl3Hqbveun2mDit_qRzklFDlZ0DnNPfiODkCkItUZmoyCaKqoJxToX1ojbUdGlsR7JO85DImoHTY7FjXTN9eXprsfb-J9oXGa0FNbhdaeDdZ9fQQsIStOJ81JcTe5UkOVaYHk" />
                                    </div>
                                    <div>
                                        <p
                                            class="font-bold text-lg flex items-center gap-1.5 text-slate-900 dark:text-white">
                                            Kiyai. Saroham Asymuni, S.Pd.I
                                            <span class="material-symbols-outlined text-xl text-accent">verified</span>
                                        </p>
                                        <p class="text-sm text-slate-500 dark:text-gray-400 font-medium">
                                            {{ $description }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <button @click="shareCurrentPage()"
                                    class="flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-100 dark:bg-slate-800 font-bold text-sm hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-gray-200 transition-colors">
                                    <span class="material-symbols-outlined text-sm">share</span>
                                    Bagikan
                                </button>
                                <button @click="showSupportModal = true"
                                    class="flex items-center gap-2 px-4 py-2 rounded-xl bg-primary text-white font-bold text-sm shadow-lg shadow-primary/20 hover:brightness-110 transition-all">
                                    <span class="material-symbols-outlined text-sm">favorite</span>
                                    Dukung Acara
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Upcoming Schedule -->
                    <div class="mt-10">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-xl font-bold flex items-center gap-2 text-slate-900 dark:text-white">
                                <span class="w-2 h-8 bg-accent rounded-full"></span>
                                Jadwal Live Mendatang
                            </h3>
                            <button class="text-primary font-bold text-sm hover:underline">Lihat Kalender
                                Dakwah</button>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @forelse($upcomingSchedules as $schedule)
                                <div
                                    class="bg-white dark:bg-slate-900 rounded-2xl overflow-hidden border border-slate-100 dark:border-white/5 group shadow-sm hover:shadow-md transition-shadow">
                                    <div class="aspect-video relative overflow-hidden">
                                        <img alt="{{ $schedule['title'] }}"
                                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                            src="{{ $schedule['thumbnail'] }}" />
                                        <div
                                            class="absolute top-3 right-3 bg-black/70 backdrop-blur-md px-3 py-1 rounded-lg text-xs font-bold text-white">
                                            {{ $schedule['scheduled_start'] }}
                                        </div>
                                    </div>
                                    <div class="p-5">
                                        <h4
                                            class="font-bold text-lg text-slate-900 dark:text-white group-hover:text-primary transition-colors line-clamp-2">
                                            {{ $schedule['title'] }}
                                        </h4>
                                        <p class="text-sm text-slate-500 dark:text-gray-400 mt-1 line-clamp-1">
                                            {{ $schedule['description'] }}
                                        </p>
                                        <button
                                            class="mt-4 w-full flex items-center justify-center gap-2 bg-primary/10 dark:bg-primary/20 text-primary dark:text-primary-light py-3 rounded-xl text-sm font-bold hover:bg-primary hover:text-white transition-all">
                                            <span class="material-symbols-outlined text-sm">notifications_active</span>
                                            Ingatkan Saya
                                        </button>
                                    </div>
                                </div>
                            @empty
                                <div class="col-span-2 text-center py-10 text-slate-500 dark:text-gray-400">
                                    Belum ada jadwal live streaming dalam waktu dekat.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Sidebar (Chat & Attendance) -->
                <aside class="lg:w-1/3 flex flex-col h-[calc(100vh-140px)] sticky top-24">
                    <div
                        class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-100 dark:border-white/5 flex flex-col h-full overflow-hidden">
                        <!-- Tabs -->
                        <div class="flex border-b border-slate-100 dark:border-white/5">
                            <button wire:click="$set('activeTab', 'chat')"
                                class="flex-1 py-4 text-sm font-bold transition-colors {{ $activeTab === 'chat' ? 'border-b-2 border-primary text-primary dark:text-white bg-primary/5 dark:bg-white/5' : 'text-slate-400 hover:text-slate-600 dark:text-gray-500 dark:hover:text-gray-300' }}">
                                Live Chat
                            </button>
                            <button wire:click="$set('activeTab', 'attendance')"
                                class="flex-1 py-4 text-sm font-bold transition-colors {{ $activeTab === 'attendance' ? 'border-b-2 border-primary text-primary dark:text-white bg-primary/5 dark:bg-white/5' : 'text-slate-400 hover:text-slate-600 dark:text-gray-500 dark:hover:text-gray-300' }}">
                                Daftar Hadir
                            </button>
                        </div>

                        <!-- Chat Section -->
                        <div class="flex-1 overflow-y-auto p-4 space-y-5 custom-scrollbar relative"
                            x-show="$wire.activeTab === 'chat'">
                            <div
                                class="bg-slate-50 dark:bg-slate-800 p-3 rounded-xl text-[12px] text-center text-slate-500 dark:text-gray-400 leading-relaxed italic">
                                Selamat datang di Live Streaming PRNU Baktijaya / MTN Baktijaya. Mari gunakan fitur chat
                                dengan bahasa yang santun & penuh adab.
                            </div>

                            <!-- Chat Items (Polled) -->
                            <div wire:poll.3s>
                                @foreach($this->chats as $chat)
                                    <div class="flex gap-3 mb-4 last:mb-0">
                                        <div
                                            class="w-9 h-9 rounded-full {{ $chat->avatar_color }} flex-shrink-0 flex items-center justify-center font-bold text-xs text-white">
                                            {{ strtoupper(substr($chat->name, 0, 2)) }}
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-[11px] font-bold text-primary dark:text-white mb-1">
                                                {{ $chat->name }}
                                                @if($chat->is_admin)
                                                    <span class="material-symbols-outlined text-[10px] text-accent">stars</span>
                                                @endif
                                                <span
                                                    class="text-[10px] text-slate-400 dark:text-gray-500 font-normal ml-2">{{ $chat->created_at->format('H:i') }}</span>
                                            </p>
                                            <div class="bg-slate-50 dark:bg-white/5 p-2 rounded-r-xl rounded-bl-xl">
                                                <p class="text-sm text-slate-700 dark:text-gray-300">{{ $chat->message }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Attendance Section -->
                        <div class="flex-1 overflow-y-auto p-4 space-y-5 custom-scrollbar relative"
                            x-show="$wire.activeTab === 'attendance'" style="display: none;">
                            @if (session()->has('success_attendance'))
                                <div
                                    class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative text-sm mb-4">
                                    {{ session('success_attendance') }}
                                </div>
                            @endif

                            <form wire:submit="submitAttendance"
                                class="space-y-3 bg-slate-50 dark:bg-white/5 p-4 rounded-xl border border-slate-100 dark:border-white/5">
                                <h4 class="text-sm font-bold text-slate-700 dark:text-gray-200 mb-2">Isi Daftar Hadir
                                </h4>
                                <input wire:model="attendanceName" type="text" placeholder="Nama Lengkap"
                                    class="w-full rounded-lg text-sm border-slate-200 dark:border-white/10 bg-white dark:bg-slate-900 text-slate-800 dark:text-white">
                                @error('attendanceName') <span class="text-xs text-red-500">{{ $message }}</span>
                                @enderror

                                <input wire:model="attendanceAddress" type="text"
                                    placeholder="Alamat / Asal (Cth: RW 02)"
                                    class="w-full rounded-lg text-sm border-slate-200 dark:border-white/10 bg-white dark:bg-slate-900 text-slate-800 dark:text-white">
                                @error('attendanceAddress') <span class="text-xs text-red-500">{{ $message }}</span>
                                @enderror

                                <textarea wire:model="attendanceMessage" placeholder="Pesan / Doa (Opsional)" rows="2"
                                    class="w-full rounded-lg text-sm border-slate-200 dark:border-white/10 bg-white dark:bg-slate-900 text-slate-800 dark:text-white"></textarea>
                                @error('attendanceMessage') <span class="text-xs text-red-500">{{ $message }}</span>
                                @enderror

                                <button type="submit"
                                    class="w-full bg-primary text-white py-2 rounded-lg text-sm font-bold hover:bg-primary-dark transition-colors">
                                    <span wire:loading.remove wire:target="submitAttendance">Kirim Kehadiran</span>
                                    <span wire:loading wire:target="submitAttendance">Mengirim...</span>
                                </button>
                            </form>

                            <div class="space-y-4" wire:poll.5s>
                                <h4 class="text-xs font-bold text-slate-500 uppercase tracking-wider">Jamaah Hadir:</h4>
                                @foreach($this->attendances as $attendance)
                                    <div
                                        class="flex items-start gap-3 border-b border-slate-100 dark:border-white/5 pb-3 last:border-0">
                                        <div
                                            class="w-8 h-8 rounded-full bg-slate-200 dark:bg-white/10 flex items-center justify-center text-xs font-bold text-slate-600 dark:text-gray-400">
                                            {{ strtoupper(substr($attendance->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-slate-800 dark:text-gray-200">
                                                {{ $attendance->name }} <span
                                                    class="font-normal text-xs text-slate-400 ml-1">({{ $attendance->address }})</span>
                                            </p>
                                            @if($attendance->message)
                                                <p class="text-xs text-slate-500 dark:text-gray-400 italic mt-0.5">
                                                    "{{ $attendance->message }}"</p>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Chat Input Area (Only visible on Chat Tab) -->
                        <div class="p-4 border-t border-slate-100 dark:border-white/5 bg-white dark:bg-slate-900"
                            x-show="$wire.activeTab === 'chat'">
                            <div class="flex gap-2 overflow-x-auto pb-3 custom-scrollbar no-scrollbar">
                                <button wire:click="$set('chatMessage', '🤲 Kirim Doa')"
                                    class="whitespace-nowrap text-[11px] font-bold bg-primary/10 dark:bg-white/10 text-primary dark:text-white px-3 py-1.5 rounded-full border border-primary/20 dark:border-white/20 hover:bg-primary hover:text-white transition-colors">🤲
                                    Kirim Doa</button>
                                <button wire:click="$set('chatMessage', '✨ Allahumma Sholli \'ala Sayyidina Muhammad')"
                                    class="whitespace-nowrap text-[11px] font-bold bg-primary/10 dark:bg-white/10 text-primary dark:text-white px-3 py-1.5 rounded-full border border-primary/20 dark:border-white/20 hover:bg-primary hover:text-white transition-colors">✨
                                    Sholawat</button>
                                <button wire:click="$set('chatMessage', '👏 Aamiin')"
                                    class="whitespace-nowrap text-[11px] font-bold bg-primary/10 dark:bg-white/10 text-primary dark:text-white px-3 py-1.5 rounded-full border border-primary/20 dark:border-white/20 hover:bg-primary hover:text-white transition-colors">👏
                                    Amin</button>
                            </div>
                            <form wire:submit="sendChat" class="flex gap-2 items-center flex-col md:flex-row">
                                <div class="flex-1 w-full space-y-2">
                                    @if(!$chatName) <!-- Simple check, ideally check session/auth -->
                                        <input wire:model="chatName"
                                            class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-lg py-2 px-3 text-xs focus:ring-1 focus:ring-primary/50 text-slate-800 dark:text-white placeholder-slate-400 transition-all mb-1"
                                            placeholder="Nama Anda..." type="text" required />
                                    @endif
                                    <div class="relative w-full">
                                        <input wire:model="chatMessage"
                                            class="w-full bg-slate-100 dark:bg-slate-800 border-none rounded-xl py-3 px-4 text-sm focus:ring-2 focus:ring-primary/50 text-slate-800 dark:text-white placeholder-slate-500 dark:placeholder-gray-400 transition-all"
                                            placeholder="Tulis komentar atau doa..." type="text" required />
                                    </div>
                                </div>
                                <button type="submit"
                                    class="bg-primary text-white w-11 h-11 rounded-xl flex items-center justify-center shadow-lg shadow-primary/20 hover:scale-105 active:scale-95 transition-transform self-end md:self-center">
                                    <span wire:loading.remove wire:target="sendChat"
                                        class="material-symbols-outlined">send</span>
                                    <span wire:loading wire:target="sendChat"
                                        class="material-symbols-outlined animate-spin text-sm">sync</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </aside>
            </div>
    </main>
</div>