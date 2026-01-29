<div class="min-h-screen bg-background-light dark:bg-background-dark font-sans transition-colors duration-300" x-data="{ showFilters: false }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <header class="mb-10">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
                <div>
                    <!-- FIX: Text color changed to avoiding pure white issues if background lags, but mainly relying on background fix -->
                    <h1 class="text-3xl font-black text-[#0c1d1d] dark:text-gray-200 transition-colors">Ringkasan Transparansi Keuangan</h1>
                    <p class="text-gray-500 dark:text-gray-400 font-medium transition-colors">Laporan realtime pengelolaan dana umat PRNU Baktijaya</p>
                </div>
                <!-- Buttons Removed -->
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Saldo Card -->
                <div class="bg-primary rounded-2xl p-6 text-white shadow-xl shadow-primary/20 relative overflow-hidden group">
                    <div class="absolute top-0 right-0 p-4 opacity-20 group-hover:scale-110 transition-transform duration-500">
                        <span class="material-symbols-outlined text-7xl">account_balance</span>
                    </div>
                    <div class="relative z-10">
                        <p class="text-white/80 text-[11px] font-bold uppercase tracking-widest mb-3">Saldo Saat Ini</p>
                        <h3 class="text-3xl font-black mb-2 tracking-tight">Rp {{ number_format($balance, 0, ',', '.') }}</h3>
                        <div class="flex items-center gap-2">
                             <div class="flex h-1.5 w-full bg-black/20 rounded-full overflow-hidden max-w-[100px]">
                                <div class="w-full bg-accent rounded-full animate-pulse"></div>
                            </div>
                            <p class="text-[10px] text-white/90 font-medium">Update Realtime</p>
                        </div>
                    </div>
                </div>

                <!-- Income Card -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 border border-gray-100 dark:border-background-dark shadow-sm transition-all duration-300 hover:shadow-lg group">
                    <div class="flex items-center gap-3 mb-5">
                        <div class="size-10 bg-green-50 dark:bg-green-900/20 text-green-600 dark:text-green-400 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                            <span class="material-symbols-outlined">south_west</span>
                        </div>
                        <p class="text-gray-400 dark:text-gray-500 text-[11px] font-bold uppercase tracking-widest">Total Infaq</p>
                    </div>
                    <div wire:loading.class="opacity-50">
                        <h3 class="text-2xl font-black text-[#0c1d1d] dark:text-white mb-3 transition-colors">Rp {{ number_format($totalIncome, 0, ',', '.') }}</h3>
                        <div class="h-1.5 w-full bg-gray-50 dark:bg-gray-700 rounded-full overflow-hidden">
                            <div class="h-full bg-green-500 rounded-full" style="width: 100%"></div>
                        </div>
                    </div>
                </div>

                <!-- Expense Card -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 border border-gray-100 dark:border-background-dark shadow-sm transition-all duration-300 hover:shadow-lg group">
                    <div class="flex items-center gap-3 mb-5">
                        <div class="size-10 bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                            <span class="material-symbols-outlined">north_east</span>
                        </div>
                        <p class="text-gray-400 dark:text-gray-500 text-[11px] font-bold uppercase tracking-widest">Penyaluran</p>
                    </div>
                    <div wire:loading.class="opacity-50">
                        <h3 class="text-2xl font-black text-[#0c1d1d] dark:text-white mb-3 transition-colors">Rp {{ number_format($totalExpense, 0, ',', '.') }}</h3>
                        <div class="h-1.5 w-full bg-gray-50 dark:bg-gray-700 rounded-full overflow-hidden">
                            <div class="h-full bg-red-500 rounded-full" style="width: {{ $totalIncome > 0 ? min(($totalExpense / $totalIncome) * 100, 100) : 0 }}%"></div>
                        </div>
                    </div>
                </div>

                <!-- Most Active Card -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 border border-gray-200 dark:border-background-dark shadow-sm relative transition-all duration-300 hover:shadow-lg group">
                    <div class="absolute top-4 right-4">
                         <!-- FIX: Smaller font size for "Paling Aktif" -->
                         <span class="bg-accent text-white text-[10px] font-bold px-2 py-0.5 rounded-full uppercase tracking-wider shadow-sm shadow-accent/30">Paling Aktif</span>
                    </div>
                    <div class="flex items-center gap-3 mb-5">
                        <div class="size-10 bg-[#fdf8e8] dark:bg-yellow-900/20 text-accent dark:text-yellow-500 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform border border-accent/10 dark:border-yellow-500/20">
                            <span class="material-symbols-outlined">handshake</span>
                        </div>
                        <p class="text-gray-400 dark:text-gray-500 text-[11px] font-bold uppercase tracking-widest">Kategori</p>
                    </div>
                    @if($mostActiveCategory)
                        <h3 class="text-lg font-black text-[#0c1d1d] dark:text-white truncate mb-1 transition-colors" title="{{ $mostActiveCategory['name'] }}">{{ Str::limit($mostActiveCategory['name'], 18) }}</h3>
                        <p class="text-xs font-semibold {{ $mostActiveCategory['type'] === 'Pemasukan' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }} flex items-center gap-1">
                             {{ $mostActiveCategory['count'] }} Transaksi
                        </p>
                    @else
                        <h3 class="text-lg font-black text-gray-400 dark:text-gray-600 transition-colors">-</h3>
                        <p class="text-xs text-gray-400 dark:text-gray-600 mt-2">Belum ada data</p>
                    @endif
                </div>
            </div>
        </header>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Chart Section -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-300 dark:border-background-dark p-8 shadow-sm">
                    <div class="flex items-center justify-between mb-8">
                        <div>
                            <h4 class="text-lg font-bold text-[#0c1d1d] dark:text-white transition-colors">Statistik Arus Kas</h4>
                            <!-- Fix label to 3 months -->
                            <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Perbandingan Infaq vs Penyaluran 3 Bulan Terakhir</p>
                        </div>
                        <div class="flex gap-4">
                            <div class="flex items-center gap-2">
                                <span class="size-3 rounded-full bg-primary"></span>
                                <span class="text-xs font-bold text-gray-600 dark:text-gray-300">Infaq</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="size-3 rounded-full bg-accent"></span>
                                <span class="text-xs font-bold text-gray-600 dark:text-gray-300">Penyaluran</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- FIX: Changed border color for chart base -->
                    <div class="h-64 flex items-end justify-between gap-4 px-2 border-b border-gray-200 dark:border-background-dark pb-2">
                        @foreach($monthlyStats as $stat)
                            <div class="flex-1 flex flex-col items-center gap-2 group h-full justify-end">
                                <div class="w-full flex gap-1 items-end h-full relative">
                                    <div class="flex-1 bg-primary rounded-t-sm transition-all duration-500 group-hover:bg-primary-dark relative" style="height: {{ $stat['income_pct'] }}%">
                                        <div class="opacity-0 group-hover:opacity-100 absolute -top-10 left-1/2 -translate-x-1/2 bg-black text-white text-[10px] px-2 py-1 rounded whitespace-nowrap z-10 pointer-events-none shadow-lg transition-opacity duration-200">
                                            Rp {{ number_format($stat['income'], 0, ',', '.') }}
                                        </div>
                                    </div>
                                    <div class="flex-1 bg-accent rounded-t-sm transition-all duration-500 group-hover:bg-[#c09d2f] relative" style="height: {{ $stat['expense_pct'] }}%">
                                         <div class="opacity-0 group-hover:opacity-100 absolute -top-10 left-1/2 -translate-x-1/2 bg-black text-white text-[10px] px-2 py-1 rounded whitespace-nowrap z-10 pointer-events-none shadow-lg transition-opacity duration-200">
                                            Rp {{ number_format($stat['expense'], 0, ',', '.') }}
                                        </div>
                                    </div>
                                </div>
                                <span class="text-[10px] font-bold uppercase tracking-wider truncate w-full text-center {{ $loop->last ? 'text-primary' : 'text-gray-400 dark:text-gray-500' }}">{{ $stat['month'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Transaction History -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-300 dark:border-background-dark overflow-hidden shadow-sm">
                    <div class="p-6 border-b border-gray-200 dark:border-background-dark flex flex-col lg:flex-row lg:items-center justify-between gap-4 bg-white dark:bg-gray-800">
                        <h4 class="text-lg font-bold text-[#0c1d1d] dark:text-white transition-colors">Riwayat Transaksi Terbaru</h4>
                        
                        <div class="flex flex-wrap items-center gap-3">
                             <button wire:click="exportExcel"
                                class="bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 py-2 px-4 rounded-full hover:bg-gray-100 dark:hover:bg-gray-600 hover:border-primary/30 text-gray-600 dark:text-gray-300 hover:text-primary dark:hover:text-primary-light flex items-center gap-2 font-bold text-xs transition-all shadow-sm">
                                <span class="material-symbols-outlined text-lg">download</span> Unduh Excel
                            </button>

                            <!-- Date Filter -->
                            <div class="relative">
                                <button @click="showFilters = !showFilters"
                                    class="bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 py-2 px-4 rounded-full hover:bg-gray-100 dark:hover:bg-gray-600 hover:border-primary/30 text-gray-600 dark:text-gray-300 hover:text-primary dark:hover:text-primary-light flex items-center gap-2 font-bold text-xs transition-all shadow-sm">
                                    <span class="material-symbols-outlined text-lg">filter_list</span>
                                    {{ $period === 'month' ? 'Bulan Ini' : ($period === 'year' ? 'Tahun Ini' : 'Semua Data') }}
                                </button>
                                
                                <!-- Dropdown Menu -->
                                <div x-show="showFilters" @click.away="showFilters = false" style="display: none;"
                                     class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-xl shadow-xl border border-gray-100 dark:border-background-dark py-2 z-50 origin-top-right transition-all transform key-filter-dropdown"
                                     x-transition:enter="transition ease-out duration-100"
                                     x-transition:enter-start="opacity-0 scale-95"
                                     x-transition:enter-end="opacity-100 scale-100"
                                     x-transition:leave="transition ease-in duration-75"
                                     x-transition:leave-start="opacity-100 scale-100"
                                     x-transition:leave-end="opacity-0 scale-95">
                                    <button wire:click="$set('period', 'month')" @click="showFilters = false" class="w-full text-left px-4 py-2.5 text-sm hover:bg-gray-50 dark:hover:bg-gray-700 {{ $period === 'month' ? 'text-primary font-bold bg-primary/5 dark:bg-primary/10' : 'text-gray-600 dark:text-gray-300' }}">Bulan Ini</button>
                                    <button wire:click="$set('period', 'year')" @click="showFilters = false" class="w-full text-left px-4 py-2.5 text-sm hover:bg-gray-50 dark:hover:bg-gray-700 {{ $period === 'year' ? 'text-primary font-bold bg-primary/5 dark:bg-primary/10' : 'text-gray-600 dark:text-gray-300' }}">Tahun Ini</button>
                                    <button wire:click="$set('period', 'all')" @click="showFilters = false" class="w-full text-left px-4 py-2.5 text-sm hover:bg-gray-50 dark:hover:bg-gray-700 {{ $period === 'all' ? 'text-primary font-bold bg-primary/5 dark:bg-primary/10' : 'text-gray-600 dark:text-gray-300' }}">Semua Data</button>
                                </div>
                            </div>

                            <!-- Search Input -->
                            <div class="relative w-48 group">
                                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari..." class="w-full text-xs bg-gray-50 dark:bg-gray-700 border-none rounded-full py-2.5 pl-4 pr-10 focus:ring-1 focus:ring-primary focus:bg-white dark:focus:bg-gray-800 text-gray-700 dark:text-gray-200 transition-all">
                                <span class="material-symbols-outlined text-sm absolute right-3 top-2.5 text-gray-400 group-focus-within:text-primary transition-colors">search</span>
                            </div>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-background-dark text-[10px] font-black uppercase tracking-widest text-gray-400 dark:text-gray-500">
                                <tr>
                                    <th class="px-6 py-4">Tanggal</th>
                                    <th class="px-6 py-4">Deskripsi</th>
                                    <th class="px-6 py-4">Kategori</th>
                                    <th class="px-6 py-4 text-right">Jumlah</th>
                                </tr>
                            </thead>
                            <!-- FIX: Changed table row border color -->
                            <tbody class="divide-y divide-gray-200 dark:divide-background-dark">
                                @forelse($transactions as $tx)
                                    <tr class="hover:bg-gray-50/80 dark:hover:bg-gray-700/50 transition-colors group">
                                        <td class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 whitespace-nowrap">{{ $tx->transaction_date->format('d M Y') }}</td>
                                        <td class="px-6 py-4">
                                            <div class="font-bold text-[#0c1d1d] dark:text-gray-200 text-sm group-hover:text-primary transition-colors">{{ $tx->description }}</div>
                                            <div class="text-[10px] text-gray-400 dark:text-gray-500 mt-0.5">{{ $tx->type === 'income' ? ($tx->incomeType->name ?? '-') : ($tx->expenseType->name ?? '-') }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($tx->type === 'income')
                                                <span class="text-[10px] font-bold text-green-600 dark:text-green-400 uppercase tracking-wider">PEMASUKAN</span>
                                            @else
                                                <span class="text-[10px] font-bold text-red-600 dark:text-red-400 uppercase tracking-wider">PENGELUARAN</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-right font-black text-sm {{ $tx->type === 'income' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                            {{ $tx->type === 'income' ? '+' : '-' }}Rp {{ number_format($tx->amount, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                            <div class="flex flex-col items-center gap-2">
                                                <span class="material-symbols-outlined text-gray-300 dark:text-gray-600 text-4xl">receipt_long</span>
                                                <p class="text-sm font-medium text-gray-400 dark:text-gray-500">Belum ada transaksi</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="p-4 border-t border-gray-100 dark:border-background-dark">
                        {{ $transactions->links(data: ['scrollTo' => false]) }}
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="space-y-8">
                <!-- Allocation -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-300 dark:border-background-dark p-6 shadow-sm">
                    <h4 class="text-lg font-bold text-[#0c1d1d] dark:text-white mb-6 transition-colors">Alokasi Dana (Tahun Ini)</h4>
                    <div class="space-y-6">
                        @forelse($allocationStats as $stat)
                            <div>
                                <div class="flex justify-between text-xs font-bold mb-2">
                                    <span class="text-gray-700 dark:text-gray-300 uppercase tracking-wide">{{ $stat['name'] }}</span>
                                    <span class="{{ $stat['text_class'] }}">{{ $stat['percentage'] }}%</span>
                                </div>
                                <div class="h-1.5 w-full bg-gray-100 dark:bg-gray-700 rounded-full overflow-hidden">
                                    <div class="h-full {{ $stat['color_class'] }} rounded-full" style="width: {{ $stat['percentage'] }}%"></div>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500 dark:text-gray-400 text-center py-4">Belum ada data pengeluaran tahun ini.</p>
                        @endforelse
                    </div>
                     <div class="mt-8 pt-4 border-t border-gray-50 dark:border-background-dark flex gap-3">
                         <span class="material-symbols-outlined text-primary/50 text-xl">info</span>
                         <p class="text-xs text-gray-400 dark:text-gray-500 leading-relaxed">
                             Laporan penggunaan dana transparan.
                         </p>
                    </div>
                </div>

                <!-- Recent Donors (Moved Above Sistem Transparan) -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-300 dark:border-background-dark p-6 shadow-sm">
                    <h4 class="text-md font-bold text-[#0c1d1d] dark:text-white mb-5 flex items-center gap-2 transition-colors">
                        <span class="material-symbols-outlined text-accent text-lg">stars</span>
                        Donatur Terkini
                    </h4>
                    <div class="space-y-4">
                        @forelse($recentDonors as $donor)
                            <div class="flex items-center gap-3 p-3 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors border border-transparent hover:border-gray-100 dark:hover:border-gray-600">
                                <div class="size-9 bg-primary/10 dark:bg-primary/20 rounded-full flex items-center justify-center text-[10px] font-bold text-primary dark:text-primary-light border border-primary/20 dark:border-primary/30 shrink-0">
                                    {{ substr($donor->description, 0, 2) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <!-- FIX: Changed donor name color to text-primary -->
                                    <p class="text-xs font-bold text-primary dark:text-primary-light truncate">{{ Str::limit($donor->description, 20) }}</p>
                                    <p class="text-[9px] text-gray-400 dark:text-gray-500 uppercase font-semibold mt-0.5 tracking-wide">{{ $donor->transaction_date->diffForHumans() }} • <span class="text-green-600 dark:text-green-400">Rp {{ number_format($donor->amount, 0, ',', '.') }}</span></p>
                                </div>
                            </div>
                        @empty
                             <p class="text-xs text-gray-400 dark:text-gray-500 text-center py-4">Belum ada donatur baru-baru ini.</p>
                        @endforelse
                    </div>
                </div>

                <!-- Call to Action (Sistem Transparan) -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-8 border border-gray-200 dark:border-background-dark shadow-sm relative overflow-hidden group hover:shadow-md transition-shadow">
                     <div class="absolute -top-10 -right-10 size-32 bg-[#fdf8e8] dark:bg-gray-700 rounded-full blur-2xl opacity-50 group-hover:opacity-100 transition-opacity"></div>
                    
                    <div class="relative z-10 flex flex-col items-center text-center">
                        <div class="size-16 bg-[#fdf8e8] dark:bg-yellow-900/20 rounded-full flex items-center justify-center mb-4 border border-accent/20 dark:border-yellow-500/20 shadow-sm">
                            <span class="material-symbols-outlined text-accent dark:text-yellow-500 text-3xl">verified_user</span>
                        </div>
                        <h4 class="text-lg font-bold text-[#0c1d1d] dark:text-white mb-2 transition-colors">Sistem Transparan</h4>
                        <p class="text-gray-500 dark:text-gray-400 text-sm leading-relaxed mb-6">Seluruh data keuangan diaudit secara berkala oleh Tim Audit Internal PRNU Baktijaya dan dipublikasikan secara rutin.</p>
                        <button class="w-full bg-primary hover:bg-primary-dark text-white font-bold py-3 px-4 rounded-xl transition-all shadow-lg shadow-primary/20 flex items-center justify-center gap-2 transform hover:-translate-y-0.5">
                            Hubungi Bendahara <span class="material-symbols-outlined text-sm">mail</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>