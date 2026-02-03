<div>
    <!-- Hero Section -->
    <section class="relative py-20 bg-white dark:bg-background-dark overflow-hidden">
        <div class="absolute top-0 right-0 w-1/3 h-full bg-primary/5 dark:bg-primary/10 -skew-x-12 translate-x-1/2">
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="flex flex-col lg:flex-row gap-16 items-center">
                <div class="lg:w-1/2 space-y-6">
                    <div
                        class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-primary/10 border border-primary/20 text-primary dark:text-accent font-bold text-xs uppercase tracking-widest">
                        <span class="size-2 bg-accent rounded-full"></span>
                        Profil Organisasi
                    </div>
                    <h1 class="text-4xl lg:text-5xl font-black text-primary dark:text-white leading-[1.1]">
                        {!! str_replace('Masyarakat', '<span class="text-accent">Masyarakat</span>', $settings['profile_hero_title'] ?? 'Khidmah NU Ranting Baktijaya Untuk Masyarakat') !!}
                    </h1>
                    <p class="text-lg text-gray-600 dark:text-white/70 leading-relaxed max-w-xl">
                        {{ $settings['profile_description'] ?? 'Pengurus Ranting Nahdlatul Ulama (PRNU) Baktijaya...' }}
                    </p>
                    <div class="flex flex-wrap gap-4 pt-4">
                        <div
                            class="flex items-center gap-3 bg-background-light dark:bg-white/5 p-4 rounded-2xl border border-primary/10 dark:border-white/10 w-full sm:w-auto">
                            <span class="material-symbols-outlined text-accent text-3xl">verified_user</span>
                            <div>
                                <p class="text-xs font-bold text-gray-500 dark:text-white/50 uppercase">Legalitas</p>
                                <p class="text-sm font-bold text-primary dark:text-white">
                                    {{ $settings['stats_legalitas'] ?? '-' }}
                                </p>
                            </div>
                        </div>
                        <div
                            class="flex items-center gap-3 bg-background-light dark:bg-white/5 p-4 rounded-2xl border border-primary/10 dark:border-white/10 w-full sm:w-auto">
                            <span class="material-symbols-outlined text-accent text-3xl">groups</span>
                            <div>
                                <p class="text-xs font-bold text-gray-500 dark:text-white/50 uppercase">Basis Massa</p>
                                <p class="text-sm font-bold text-primary dark:text-white">
                                    {{ $settings['stats_basis_massa'] ?? '-' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="lg:w-1/2">
                    <div class="relative group">
                        <div
                            class="absolute -inset-4 bg-accent/20 rounded-3xl blur-2xl group-hover:bg-accent/30 transition-all">
                        </div>
                        <img alt="PRNU Baktijaya Activity"
                            class="relative rounded-3xl shadow-2xl w-full object-cover border-4 border-white dark:border-white/10"
                            style="aspect-ratio: 4/3;"
                            src="{{ (!empty($settings['profile_image']) && str_starts_with($settings['profile_image'], 'http')) ? $settings['profile_image'] : (!empty($settings['profile_image']) ? asset('storage/' . $settings['profile_image']) : 'https://placehold.co/800x600') }}" />
                        <div
                            class="absolute -bottom-6 -left-6 bg-white dark:bg-background-dark p-6 rounded-2xl shadow-xl border border-primary/10 dark:border-white/10 hidden md:block z-20">
                            <div class="flex items-center gap-4">
                                <div class="size-12 bg-primary rounded-xl flex items-center justify-center">
                                    <span class="material-symbols-outlined text-white">history</span>
                                </div>
                                <div>
                                    <p class="text-2xl font-black text-primary dark:text-white leading-none">
                                        {{ $settings['stats_tahun_khidmat'] ?? '0' }}
                                    </p>
                                    <p class="text-xs font-bold text-gray-500 dark:text-white/50 uppercase">Tahun
                                        Berkhidmat</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Visi & Misi Section -->
    <section class="py-24 bg-background-light dark:bg-background-dark/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-12">
                <!-- Visi -->
                <div class="bg-primary rounded-3xl p-10 text-white relative overflow-hidden group">
                    <div class="absolute top-0 right-0 p-8 opacity-10 group-hover:scale-110 transition-transform">
                        <span class="material-symbols-outlined text-[120px]">visibility</span>
                    </div>
                    <div class="relative z-10">
                        <div class="size-14 bg-white/20 rounded-2xl flex items-center justify-center mb-6">
                            <span class="material-symbols-outlined text-accent text-3xl">lightbulb</span>
                        </div>
                        <h2 class="text-3xl font-black mb-6">Visi Kami</h2>
                        <p class="text-xl leading-relaxed text-white/90 italic">
                            {{ $settings['visi'] ?? 'Visi belum diatur.' }}
                        </p>
                    </div>
                </div>

                <!-- Misi -->
                <div
                    class="bg-white dark:bg-white/5 rounded-3xl p-10 border border-primary/10 dark:border-white/10 shadow-sm">
                    <h2 class="text-3xl font-black text-primary dark:text-white mb-8 flex items-center gap-4">
                        <span class="material-symbols-outlined text-accent text-4xl">task_alt</span>
                        Misi Organisasi
                    </h2>
                    <div class="space-y-6">
                        @for($i = 1; $i <= 3; $i++)
                            @if(isset($settings["misi_$i"]))
                                <div class="flex gap-4">
                                    <div class="size-8 bg-primary/10 rounded-lg flex items-center justify-center shrink-0 mt-1">
                                        <span class="text-primary dark:text-accent font-bold">{{ $i }}</span>
                                    </div>
                                    <p class="text-gray-700 dark:text-white/70 leading-relaxed">
                                        {!! str_replace(':', ':</span>', str_replace(['Penguatan Akidah', 'Pemberdayaan Ekonomi', 'Layanan Sosial'], ['<span class="font-bold text-primary dark:text-accent">Penguatan Akidah', '<span class="font-bold text-primary dark:text-accent">Pemberdayaan Ekonomi', '<span class="font-bold text-primary dark:text-accent">Layanan Sosial'], $settings["misi_$i"])) !!}
                                    </p>
                                </div>
                            @endif
                        @endfor
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Struktur Organisasi Header -->
    <section class="py-16 bg-white dark:bg-background-dark border-b border-primary/10 dark:border-white/10">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <h2 class="text-3xl font-black text-primary dark:text-white mb-4">Struktur Organisasi</h2>
            <div class="w-24 h-1.5 bg-accent mx-auto rounded-full mb-6"></div>
            <p class="text-gray-500 dark:text-white/60 max-w-2xl mx-auto">Sinergi antara Jajaran Syuriyah (Pengarah) dan
                Tanfidziyah (Pelaksana) dalam menjalankan roda organisasi.</p>
        </div>
    </section>

    <!-- Struktur Organisasi Content -->
    <section class="py-20 bg-background-light dark:bg-background-dark/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Majelis Syuriyah -->
            <div class="mb-20">
                <div class="flex items-center gap-4 mb-10">
                    <h3
                        class="text-xl font-black text-primary dark:text-white uppercase tracking-widest bg-primary/10 px-6 py-2 rounded-xl">
                        Majelis Syuriyah</h3>
                    <div class="flex-1 h-px bg-primary/20 dark:bg-white/10"></div>
                </div>
                @if($syuriyah->isEmpty())
                    <div class="text-center py-8 text-gray-500">Belum ada data pengurus Syuriyah.</div>
                @else
                    <div
                        class="grid grid-cols-1 md:grid-cols-{{ count($syuriyah) > 3 ? 3 : (count($syuriyah) ?: 1) }} gap-8">
                        @foreach($syuriyah as $s)
                            <div
                                class="bg-white dark:bg-white/5 p-8 rounded-3xl border border-primary/5 dark:border-white/10 shadow-sm hover:shadow-md transition-shadow text-center">
                                @if($s->image)
                                    <img src="{{ asset('storage/' . $s->image) }}" alt="{{ $s->name }}"
                                        class="size-20 rounded-full object-cover mb-4 mx-auto border-4 border-primary/10 dark:border-white/10 shadow-sm">
                                @else
                                    <div class="size-20 rounded-full bg-primary/5 flex items-center justify-center mb-4 mx-auto border-4 border-primary/10 dark:border-white/5">
                                        <span class="material-symbols-outlined text-4xl text-primary/20">person</span>
                                    </div>
                                @endif
                                <p class="text-accent font-bold text-xs uppercase mb-1">{{ $s->position }}</p>
                                <h4 class="text-xl font-extrabold text-primary dark:text-white mb-4">{{ $s->name }}</h4>
                                <p class="text-sm text-gray-500 dark:text-white/50 leading-relaxed">{{ $s->description }}</p>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Majelis Tanfidziyah -->
            <!-- Majelis Tanfidziyah -->
            @livewire('tanfidziyah-section')

            <!-- Relawan -->
            <!-- Relawan -->
            @livewire('volunteer-section')
        </div>
    </section>

    <!-- Banom & Lembaga Section -->
    <section class="py-24 bg-white dark:bg-background-dark">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-black text-primary dark:text-white">Banom & Lembaga</h2>
                <p class="text-gray-500 dark:text-white/60 mt-4">Badan Otonom dan Lembaga di bawah naungan PRNU
                    Baktijaya.</p>
            </div>
            @if($banoms->isEmpty())
                <div class="text-center py-8 text-gray-500">Belum ada data Banom/Lembaga.</div>
            @else
                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-6">
                    @foreach($banoms as $banom)
                        <div class="flex flex-col items-center gap-4 group cursor-pointer">
                            <div
                                class="size-20 rounded-3xl bg-background-light dark:bg-white/5 flex items-center justify-center border border-primary/10 dark:border-white/10 group-hover:border-accent group-hover:bg-accent/5 transition-all">
                                @if($banom->icon)
                                    <span
                                        class="material-symbols-outlined text-primary dark:text-white text-4xl group-hover:text-accent">{{ $banom->icon }}</span>
                                @elseif($banom->image)
                                    <img src="{{ asset('storage/' . $banom->image) }}" alt="{{ $banom->name }}"
                                        class="w-10 h-10 object-contain">
                                @else
                                    <span
                                        class="material-symbols-outlined text-primary dark:text-white text-4xl group-hover:text-accent">business</span>
                                @endif
                            </div>
                            <span class="font-bold text-sm text-primary dark:text-white">{{ $banom->name }}</span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-16 bg-primary dark:bg-primary-dark">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl font-black text-white mb-6">Mari Berkontribusi Untuk Umat</h2>
            <p class="text-white/80 max-w-2xl mx-auto mb-10 text-lg">Pintu kami selalu terbuka untuk Anda yang ingin
                berkhidmat dan berkolaborasi dalam membangun Baktijaya yang lebih baik.</p>
            <div class="flex flex-wrap justify-center gap-4">
                <a href="{{ route('daftar.kontributor') }}"
                    class="bg-accent hover:bg-yellow-600 text-white font-black py-4 px-10 rounded-2xl shadow-xl transition-all flex items-center gap-3">
                    <span class="material-symbols-outlined">group_add</span>
                    Gabung Kontributor Berita & Artikel
                </a>
                <button
                    class="bg-white/10 hover:bg-white/20 text-white border-2 border-white/20 font-black py-4 px-10 rounded-2xl backdrop-blur-sm transition-all">
                    Hubungi Kami
                </button>
            </div>
        </div>
    </section>
</div>