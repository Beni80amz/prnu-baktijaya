<div>
    <section class="relative overflow-hidden pt-44 pb-32 bg-background-light dark:bg-background-dark min-h-screen">
        <div class="container mx-auto px-4 relative z-10">
            <div class="max-w-4xl mx-auto">
                <!-- Header -->
                <div class="text-center mb-16">
                    <span
                        class="inline-block py-1 px-3 rounded-full bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400 text-sm font-bold tracking-widest uppercase mb-4 animate-fade-in">Zakat
                        & Shadaqah</span>
                    <h1 class="text-4xl md:text-5xl font-extrabold text-background-dark dark:text-white mb-6">Kalkulator
                        Zakat Online</h1>
                    <p class="text-gray-600 dark:text-gray-400 text-lg max-w-2xl mx-auto">
                        Hitung kewajiban zakat Anda dengan mudah dan tepat sesuai dengan syariat Islam.
                    </p>
                </div>

                <!-- Main Calculator Card -->
                <div
                    class="bg-white dark:bg-white/5 rounded-3xl shadow-2xl overflow-hidden border border-gray-100 dark:border-white/10 mb-10">
                    <div class="grid grid-cols-1 md:grid-cols-12">
                        <!-- Left: Selection -->
                        <div
                            class="md:col-span-4 bg-gray-50 dark:bg-white/5 p-8 border-b md:border-b-0 md:border-r border-gray-100 dark:border-white/10">
                            <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-6">Pilih Jenis Zakat</h3>
                            <div class="space-y-3">
                                <button wire:click="$set('type', 'maal')"
                                    class="w-full flex items-center gap-3 p-4 rounded-2xl transition-all {{ $type === 'maal' ? 'bg-primary text-white shadow-lg shadow-primary/20' : 'bg-white dark:bg-white/5 text-gray-600 dark:text-gray-400 hover:bg-gray-100' }}">
                                    <span class="material-symbols-outlined">payments</span>
                                    <span class="font-bold text-sm">Zakat Maal (Harta)</span>
                                </button>
                                <button wire:click="$set('type', 'profesi')"
                                    class="w-full flex items-center gap-3 p-4 rounded-2xl transition-all {{ $type === 'profesi' ? 'bg-primary text-white shadow-lg shadow-primary/20' : 'bg-white dark:bg-white/5 text-gray-600 dark:text-gray-400 hover:bg-gray-100' }}">
                                    <span class="material-symbols-outlined">work</span>
                                    <span class="font-bold text-sm">Zakat Penghasilan</span>
                                </button>
                                <button wire:click="$set('type', 'emas')"
                                    class="w-full flex items-center gap-3 p-4 rounded-2xl transition-all {{ $type === 'emas' ? 'bg-primary text-white shadow-lg shadow-primary/20' : 'bg-white dark:bg-white/5 text-gray-600 dark:text-gray-400 hover:bg-gray-100' }}">
                                    <span class="material-symbols-outlined">diamond</span>
                                    <span class="font-bold text-sm">Zakat Emas</span>
                                </button>
                            </div>

                            <div
                                class="mt-12 p-4 rounded-2xl bg-emerald-50 dark:bg-emerald-900/10 border border-emerald-100 dark:border-emerald-900/20">
                                <p class="text-xs text-emerald-800 dark:text-emerald-400 leading-relaxed font-medium">
                                    <span class="font-bold block mb-1">Informasi Nisab:</span>
                                    Harga Emas saat ini: <b>Rp {{ number_format($goldPrice, 0, ',', '.') }}/gram</b>.
                                    Nisab 85gr emas: <b>Rp {{ number_format($goldPrice * 85, 0, ',', '.') }}</b>.
                                </p>
                            </div>
                        </div>

                        <!-- Right: Inputs & Results -->
                        <div class="md:col-span-8 p-8 md:p-12">
                            @if($type === 'maal')
                                <div class="space-y-6 animate-fade-in">
                                    <div class="space-y-2">
                                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300">Total Harta
                                            (Tabungan/Saham/Piutang)</label>
                                        <div class="relative">
                                            <span
                                                class="absolute left-5 top-1/2 -translate-y-1/2 text-gray-400 font-bold">Rp</span>
                                            <input type="number" wire:model.live="totalWealth"
                                                class="w-full pl-12 pr-6 py-4 rounded-2xl bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 focus:ring-2 focus:ring-primary/50 outline-none text-xl font-bold text-gray-800 dark:text-white transition-all">
                                        </div>
                                    </div>

                                    <div class="p-8 rounded-3xl bg-primary/10 border border-primary/20 text-center">
                                        <p class="text-primary font-bold uppercase tracking-widest text-xs mb-2">Total Zakat
                                            Maal Anda</p>
                                        <h2 class="text-4xl font-black text-primary">Rp
                                            {{ number_format($this->maalZakat, 0, ',', '.') }}
                                        </h2>
                                    </div>
                                </div>
                            @elseif($type === 'profesi')
                                <div class="space-y-6 animate-fade-in">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div class="space-y-2">
                                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300">Gaji
                                                Bulanan</label>
                                            <div class="relative">
                                                <span
                                                    class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm font-bold">Rp</span>
                                                <input type="number" wire:model.live="monthlySalary"
                                                    class="w-full pl-10 pr-4 py-3 rounded-xl bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 focus:ring-2 focus:ring-primary transition-all">
                                            </div>
                                        </div>
                                        <div class="space-y-2">
                                            <label
                                                class="block text-sm font-bold text-gray-700 dark:text-gray-300">Penghasilan
                                                Lain</label>
                                            <div class="relative">
                                                <span
                                                    class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm font-bold">Rp</span>
                                                <input type="number" wire:model.live="otherIncome"
                                                    class="w-full pl-10 pr-4 py-3 rounded-xl bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 focus:ring-2 focus:ring-primary transition-all">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="p-8 rounded-3xl bg-secondary/10 border border-secondary/20 text-center">
                                        <p class="text-secondary font-bold uppercase tracking-widest text-xs mb-2">Zakat
                                            Profesi Per Bulan</p>
                                        <h2 class="text-4xl font-black text-secondary">Rp
                                            {{ number_format($this->profesiZakat, 0, ',', '.') }}
                                        </h2>
                                    </div>
                                </div>
                            @elseif($type === 'emas')
                                <div class="space-y-6 animate-fade-in">
                                    <div class="space-y-2">
                                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300">Berat Emas
                                            (Gram)</label>
                                        <div class="relative">
                                            <input type="number" wire:model.live="goldWeight"
                                                class="w-full pr-16 pl-6 py-4 rounded-2xl bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 focus:ring-2 focus:ring-primary/50 outline-none text-xl font-bold text-gray-800 dark:text-white transition-all">
                                            <span
                                                class="absolute right-6 top-1/2 -translate-y-1/2 text-gray-400 font-bold">gram</span>
                                        </div>
                                    </div>

                                    <div class="p-8 rounded-3xl bg-amber-500/10 border border-amber-500/20 text-center">
                                        <p class="text-amber-600 font-bold uppercase tracking-widest text-xs mb-2">Zakat
                                            Emas Anda</p>
                                        <h2 class="text-4xl font-black text-amber-600">Rp
                                            {{ number_format($this->emasZakat, 0, ',', '.') }}
                                        </h2>
                                    </div>
                                </div>
                            @endif

                            <div class="mt-10 flex flex-col md:flex-row items-center gap-6">
                                <a href="https://lazisnubaktijaya.org" target="_blank"
                                    class="flex-1 w-full py-4 rounded-2xl bg-primary text-white font-bold text-center shadow-lg hover:scale-105 transition-all">
                                    Salurkan Lewat LAZISNU
                                </a>
                                <div class="flex-1">
                                    <p class="text-xs text-gray-400 font-medium italic leading-relaxed">
                                        *Perhitungan ini hanya estimasi berdasarkan nisab emas saat ini. Silakan
                                        konsultasikan dengan Kiai untuk perhitungan lebih rinci.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>