<div>
    <section
        class="relative overflow-hidden pt-44 pb-32 bg-background-light dark:bg-background-dark min-h-screen text-background-dark dark:text-white">
        <!-- Background Elements -->
        <div
            class="absolute top-0 left-0 w-1/3 h-1/3 bg-emerald-500/5 rounded-full blur-3xl -translate-y-1/2 -translate-x-1/2">
        </div>
        <div
            class="absolute bottom-0 right-0 w-1/4 h-1/4 bg-amber-500/5 rounded-full blur-3xl translate-y-1/2 translate-x-1/2">
        </div>

        <div class="container mx-auto px-4 relative z-10">
            <div class="max-w-4xl mx-auto">
                <!-- Header -->
                <div class="text-center mb-16">
                    <span
                        class="inline-block py-1 px-3 rounded-full bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 text-sm font-bold tracking-widest uppercase mb-4 animate-fade-in">Khidmat
                        Jam'iyah</span>
                    <h1 class="text-4xl md:text-5xl font-black mb-6">Ruang Doa & Tahlil Digital</h1>
                    <p class="text-gray-600 dark:text-gray-400 text-lg max-w-2xl mx-auto leading-relaxed">
                        Khidmat permohonan doa virtual. Nama-nama yang Anda kirimkan akan dibacakan rutin dalam majelis
                        tahlil dan taklim PRNU Baktijaya.
                    </p>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-start">
                    <!-- Info Card -->
                    <div class="space-y-8">
                        <div class="bg-primary text-white p-8 rounded-3xl shadow-2xl relative overflow-hidden group">
                            <div
                                class="absolute top-0 right-0 p-4 opacity-10 group-hover:scale-110 transition-transform duration-700">
                                <span class="material-symbols-outlined text-9xl">format_quote</span>
                            </div>
                            <h3 class="text-2xl font-bold mb-4">Fadhilah Doa Jama'i</h3>
                            <p class="text-white/90 leading-relaxed text-sm italic">
                                "Sesungguhnya tidaklah suatu kaum berkumpul, lalu sebagian mereka berdoa dan sebagian
                                yang lain mengamininya, melainkan Allah akan mengabulkan doa mereka."
                            </p>
                            <p class="text-xs font-bold mt-4 text-secondary tracking-wider">HR. AL-HAKIM</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div
                                class="p-6 bg-white dark:bg-white/5 rounded-2xl border border-primary/5 dark:border-white/10 shadow-sm">
                                <span class="material-symbols-outlined text-primary mb-3">auto_awesome</span>
                                <h4 class="font-bold text-sm mb-1">Didoakan Rutin</h4>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Setiap malam Sabtu dan majelis
                                    Lailatul Ijtima'.</p>
                            </div>
                            <div
                                class="p-6 bg-white dark:bg-white/5 rounded-2xl border border-primary/5 dark:border-white/10 shadow-sm">
                                <span class="material-symbols-outlined text-primary mb-3">volunteer_activism</span>
                                <h4 class="font-bold text-sm mb-1">Khidmat Gratis</h4>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Layanan ini murni khidmat sosial
                                    untuk jamaah.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Form Card -->
                    <div
                        class="bg-white dark:bg-white/5 p-8 md:p-10 rounded-3xl shadow-2xl border border-gray-100 dark:border-white/10 backdrop-blur-xl">
                        @if (session()->has('message'))
                            <div
                                class="mb-8 p-4 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 rounded-2xl font-bold flex items-center gap-3 animate-bounce">
                                <span class="material-symbols-outlined">check_circle</span>
                                {{ session('message') }}
                            </div>
                        @endif

                        <form wire:submit.prevent="submit" class="space-y-6">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Nama
                                    Pengirim</label>
                                <input type="text" wire:model="name"
                                    class="w-full px-5 py-3 rounded-2xl bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 focus:ring-2 focus:ring-primary/50 outline-none transition-all">
                                @error('name') <span class="text-xs text-rose-500 font-bold mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Nama Ahli
                                    Kubur / Yang Didoakan</label>
                                <input type="text" wire:model="deceased_name" placeholder="Contoh: Fulan bin Fulan"
                                    class="w-full px-5 py-3 rounded-2xl bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 focus:ring-2 focus:ring-primary/50 outline-none transition-all">
                                @error('deceased_name') <span
                                class="text-xs text-rose-500 font-bold mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Jenis
                                    Permohonan</label>
                                <select wire:model="request_type"
                                    class="w-full px-5 py-3 rounded-2xl bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 focus:ring-2 focus:ring-primary/50 outline-none transition-all">
                                    <option value="tahlil">Tahlil & Kirim Doa (Ahli Kubur)</option>
                                    <option value="doa_khusus">Doa Khusus (Hajat / Kesembuhan / Syukuran)</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Pesan/Hajat
                                    (Opsional)</label>
                                <textarea wire:model="notes" rows="3"
                                    class="w-full px-5 py-3 rounded-2xl bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 focus:ring-2 focus:ring-primary/50 outline-none transition-all"></textarea>
                            </div>

                            <button type="submit"
                                class="w-full py-4 rounded-2xl bg-primary text-white font-bold text-lg shadow-xl shadow-primary/20 hover:scale-[1.02] active:scale-[0.98] transition-all flex items-center justify-center gap-3 group">
                                <span wire:loading wire:target="submit"
                                    class="animate-spin h-5 w-5 border-2 border-white/20 border-t-white rounded-full"></span>
                                <span wire:loading.remove wire:target="submit" class="flex items-center gap-2">
                                    Kirim ke Majelis Doa
                                    <span
                                        class="material-symbols-outlined text-sm group-hover:translate-x-1 transition-transform">send</span>
                                </span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>