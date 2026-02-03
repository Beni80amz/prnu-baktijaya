<div class="min-h-screen bg-background-light dark:bg-background-dark"
    style="padding-top: 150px !important; padding-bottom: 250px !important;">
    <div class="max-w-3xl mx-auto px-4">
        {{-- Hero Header --}}
        <div class="text-center mb-10">
            <span
                class="inline-block py-1.5 px-4 rounded-full bg-primary/10 text-primary text-[10px] font-black uppercase tracking-[0.2em] mb-4">
                Informasi Keanggotaan
            </span>
            <h1 class="text-4xl md:text-5xl font-black text-background-dark dark:text-white mb-4">Status Pendaftaran
            </h1>
            <p class="text-gray-500 dark:text-gray-400 max-w-lg mx-auto leading-relaxed">
                Terima kasih atas antusiasme Anda untuk bergabung mengembangkan konten dakwah digital PRNU Baktijaya.
            </p>
        </div>

        <div class="relative">
            {{-- Decorative Elements --}}
            <div class="absolute -top-10 -right-10 size-40 bg-primary/5 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-10 -left-10 size-40 bg-yellow-500/5 rounded-full blur-3xl"></div>

            <div
                class="bg-white dark:bg-white/5 rounded-[2.5rem] shadow-[0_32px_64px_-12px_rgba(0,0,0,0.08)] overflow-hidden border border-white dark:border-white/10 backdrop-blur-xl relative z-10">
                @if($application->status === 'pending')
                        <div class="p-8 md:p-14 text-center">
                            {{-- User Avatar / Status Icon --}}
                            <div class="relative size-20 mx-auto mb-6">
                                {{-- Minimal Pulse --}}
                                <div class="absolute inset-0 bg-yellow-500/10 rounded-full animate-ping opacity-30"></div>

                                {{-- Refined Avatar Container --}}
                                <div
                                    class="relative size-20 rounded-full p-1 bg-white dark:bg-gray-800 shadow-lg border border-gray-100 dark:border-white/10">
                                    <div
                                        class="w-full h-full rounded-full overflow-hidden bg-gray-50 dark:bg-gray-900 flex items-center justify-center">
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($application->name) }}&background=EAB308&color=fff&size=128"
                                            alt="{{ $application->name }}" class="w-full h-full object-cover">

                                        {{-- Status Badge Icon --}}
                                        <div
                                            class="absolute -bottom-1 -right-1 size-7 bg-yellow-500 rounded-lg flex items-center justify-center text-white border-2 border-white dark:border-gray-800 shadow-md">
                                            <span class="material-symbols-outlined text-sm">history_edu</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <h2 class="text-3xl font-black text-gray-800 dark:text-white mb-4 text-center">Sedang Ditinjau Admin
                        </h2>
                        <p class="text-gray-500 dark:text-gray-400 text-lg mb-10 max-w-md mx-auto leading-relaxed">
                            Pendaftaran Anda sebagai <span class="text-primary font-bold">Kontributor Berita &
                                Artikel</span> sedang dalam tahap moderasi.
                        </p>

                        {{-- Info Box --}}
                        <div
                            class="bg-gray-50 dark:bg-black/20 rounded-3xl p-8 border border-gray-100 dark:border-white/5 text-left max-w-md mx-auto">
                            <h4
                                class="text-xs font-black text-gray-400 uppercase tracking-widest mb-6 border-b border-gray-200 dark:border-white/10 pb-4">
                                Rincian Pengajuan
                            </h4>
                            <div class="space-y-4">
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-400">ID Registrasi</span>
                                    <span class="font-bold text-gray-700 dark:text-gray-200">#KONT-{{ $application->id }}</span>
                                </div>
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-400">Nama Lengkap</span>
                                    <span class="font-bold text-gray-700 dark:text-gray-200">{{ $application->name }}</span>
                                </div>
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-400">Tanggal Daftar</span>
                                    <span
                                        class="font-bold text-gray-700 dark:text-gray-200">{{ $application->created_at->format('d F Y') }}</span>
                                </div>
                                <div class="flex justify-between items-center text-sm pt-2">
                                    <span class="text-gray-400">Status Saat Ini</span>
                                    <span
                                        class="flex items-center gap-2 text-yellow-600 font-black uppercase text-[10px] bg-yellow-400/10 px-3 py-1 rounded-full">
                                        <span class="size-1.5 bg-yellow-500 rounded-full animate-pulse"></span>
                                        Pending
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-12 flex flex-col md:flex-row items-center justify-center gap-6">
                            <div
                                class="flex items-center gap-3 text-gray-400 bg-gray-50 dark:bg-white/5 py-3 px-6 rounded-2xl border border-gray-100 dark:border-white/5">
                                <span class="material-symbols-outlined text-primary">schedule</span>
                                <span class="text-xs font-bold leading-tight uppercase tracking-widest">Estimasi 1-3 Hari
                                    Kerja</span>
                            </div>
                        </div>
                    </div>

                @elseif($application->status === 'approved')
                <div class="p-8 md:p-14 text-center">
                    <div
                        class="size-32 bg-gradient-to-br from-primary to-green-700 rounded-full flex items-center justify-center mx-auto mb-10 shadow-2xl shadow-primary/30">
                        <span class="material-symbols-outlined text-white text-6xl">verified_user</span>
                    </div>
                    <h2 class="text-4xl font-black text-gray-800 dark:text-white mb-4">Selamat Bergabung!</h2>
                    <p class="text-gray-500 dark:text-gray-400 text-lg mb-12 max-w-md mx-auto">
                        Akun kontributor Anda telah aktif. Sekarang Anda dapat mulai berbagi inspirasi melalui
                        Dashboard.
                    </p>
                    <a href="/admin"
                        class="group relative inline-flex items-center gap-4 bg-primary text-white font-black py-5 px-12 rounded-[1.5rem] hover:scale-105 active:scale-95 transition-all shadow-2xl shadow-primary/20">
                        <span class="material-symbols-outlined">dashboard</span>
                        MASUK DASHBOARD
                        <span
                            class="material-symbols-outlined group-hover:translate-x-1 transition-transform">arrow_forward_ios</span>
                    </a>
                </div>

            @elseif($application->status === 'rejected')
                <div class="p-8 md:p-14 text-center">
                    <div
                        class="size-32 bg-gradient-to-br from-rose-500 to-rose-700 rounded-full flex items-center justify-center mx-auto mb-10 shadow-2xl shadow-rose-500/30">
                        <span class="material-symbols-outlined text-white text-6xl">error_outline</span>
                    </div>
                    <h2 class="text-3xl font-black text-gray-800 dark:text-white mb-4">Pengajuan Ditolak</h2>

                    @if($application->note)
                        <div class="p-8 bg-rose-500/5 rounded-3xl border border-rose-500/10 text-left mb-10 max-w-md mx-auto">
                            <p class="text-[10px] font-black text-rose-500 uppercase tracking-widest mb-3">Pesan dari Admin:
                            </p>
                            <p class="text-rose-900 dark:text-rose-200 font-medium leading-relaxed italic">
                                "{{ $application->note }}"</p>
                        </div>
                    @endif

                    <a href="{{ route('daftar.kontributor') }}"
                        class="inline-flex items-center gap-3 bg-gray-100 dark:bg-white/10 text-gray-700 dark:text-white font-black py-4 px-10 rounded-2xl hover:bg-gray-200 transition-all">
                        <span class="material-symbols-outlined">edit_note</span>
                        Daftar Kembali
                    </a>
                </div>
            @endif
        </div>
    </div>

    <div class="mt-12 text-center">
        <a href="{{ route('home') }}"
            class="group inline-flex items-center gap-3 text-gray-500 dark:text-gray-400 font-black uppercase text-[11px] tracking-[0.2em] hover:text-primary transition-colors">
            <span
                class="material-symbols-outlined text-lg group-hover:-translate-x-1 transition-transform">arrow_back</span>
            Kembali ke Beranda Baktijaya
        </a>
    </div>
</div>
</div>