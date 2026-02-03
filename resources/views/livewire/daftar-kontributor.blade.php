<div class="min-h-screen bg-background-light dark:bg-background-dark"
    style="padding-top: 150px !important; padding-bottom: 250px !important;">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <span
                class="inline-block py-1 px-3 rounded-full bg-primary/10 text-primary text-sm font-bold tracking-widest uppercase mb-4 animate-fade-in">Bergabung
                Menjadi Bagian Kami</span>
            <h1 class="text-4xl md:text-5xl font-extrabold text-background-dark dark:text-white mb-6">Daftar Kontributor
            </h1>
            <p class="text-gray-600 dark:text-gray-400 text-lg max-w-2xl mx-auto">
                Lengkapi biodata diri Anda untuk menjadi kontributor berita dan artikel di PRNU Baktijaya.
            </p>
        </div>

        <div
            class="bg-white dark:bg-white/5 rounded-3xl shadow-2xl overflow-hidden border border-gray-100 dark:border-white/10 backdrop-blur-xl">
            @if(!auth()->check())
                <div class="p-12 text-center">
                    <div
                        class="size-20 bg-primary/10 text-primary rounded-full flex items-center justify-center mx-auto mb-6">
                        <span class="material-symbols-outlined text-4xl">lock</span>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-4">Akses Terbatas</h2>
                    <p class="text-gray-500 dark:text-gray-400 mb-8">Anda harus login terlebih dahulu untuk mendaftar
                        sebagai kontributor.</p>
                    <a href="/admin/login"
                        class="inline-flex items-center gap-2 bg-primary text-white font-bold py-3 px-8 rounded-xl hover:scale-105 active:scale-95 transition-all">
                        <span class="material-symbols-outlined text-sm">login</span>
                        Login Sekarang
                    </a>
                </div>
            @else
                <form wire:submit.prevent="submit" class="p-8 md:p-12 space-y-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label
                                class="block text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Nama
                                Lengkap</label>
                            <input type="text" wire:model="name"
                                class="w-full px-5 py-3 rounded-2xl bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 focus:ring-2 focus:ring-primary/50 outline-none text-gray-800 dark:text-white transition-all shadow-inner">
                            @error('name') <span class="text-xs text-rose-500 font-bold mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label
                                class="block text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Email
                                Utama</label>
                            <input type="email" wire:model="email"
                                class="w-full px-5 py-3 rounded-2xl bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 focus:ring-2 focus:ring-primary/50 outline-none text-gray-800 dark:text-white transition-all shadow-inner"
                                readonly>
                            @error('email') <span class="text-xs text-rose-500 font-bold mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label
                                class="block text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">No.
                                WhatsApp</label>
                            <input type="text" wire:model="phone" placeholder="08xxxx"
                                class="w-full px-5 py-3 rounded-2xl bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 focus:ring-2 focus:ring-primary/50 outline-none text-gray-800 dark:text-white transition-all shadow-inner">
                            @error('phone') <span class="text-xs text-rose-500 font-bold mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label
                                class="block text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Alamat
                                Lengkap</label>
                            <textarea wire:model="address" rows="1"
                                class="w-full px-5 py-3 rounded-2xl bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 focus:ring-2 focus:ring-primary/50 outline-none text-gray-800 dark:text-white transition-all shadow-inner"></textarea>
                            @error('address') <span class="text-xs text-rose-500 font-bold mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label
                            class="block text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Pengalaman
                            Menulis (Berita/Artikel)</label>
                        <textarea wire:model="experience" rows="4"
                            placeholder="Ceritakan pengalaman Anda dalam menulis atau motivasi Anda..."
                            class="w-full px-5 py-4 rounded-2xl bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 focus:ring-2 focus:ring-primary/50 outline-none text-gray-800 dark:text-white transition-all shadow-inner"></textarea>
                        @error('experience') <span class="text-xs text-rose-500 font-bold mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label
                            class="block text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Biodata
                            Singkat (Opsional)</label>
                        <textarea wire:model="bio" rows="2" placeholder="Singkat saja..."
                            class="w-full px-5 py-4 rounded-2xl bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 focus:ring-2 focus:ring-primary/50 outline-none text-gray-800 dark:text-white transition-all shadow-inner"></textarea>
                        @error('bio') <span class="text-xs text-rose-500 font-bold mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="pt-6">
                        <button type="submit"
                            class="w-full py-4 rounded-2xl bg-primary text-white font-bold text-lg shadow-xl shadow-primary/20 hover:scale-[1.02] active:scale-[0.98] transition-all flex items-center justify-center gap-3">
                            <span wire:loading wire:target="submit"
                                class="animate-spin h-5 w-5 border-2 border-white/20 border-t-white rounded-full"></span>
                            <span wire:loading.remove wire:target="submit">Kirim Pendaftaran</span>
                        </button>
                    </div>
                </form>
            @endif
        </div>
    </div>
</div>