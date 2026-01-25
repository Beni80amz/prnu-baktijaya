<div>
    <!-- Page Header -->
    <section class="bg-gradient-to-br from-primary to-primary-dark py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl md:text-5xl font-black text-white mb-4">Transparansi Keuangan</h1>
            <p class="text-white/80 text-lg max-w-2xl mx-auto">Laporan keuangan KAS Organisasi PRNU Baktijaya secara
                terbuka dan transparan</p>
        </div>
    </section>

    <!-- Balance Cards -->
    <section class="py-12 bg-white dark:bg-background-dark border-b border-primary/10 dark:border-white/10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Total Balance -->
                <div class="bg-gradient-to-br from-primary to-primary-dark rounded-2xl p-8 text-white text-center">
                    <span class="material-symbols-outlined text-4xl mb-3 opacity-80">account_balance_wallet</span>
                    <p class="text-white/80 text-sm font-bold uppercase tracking-wider mb-2">Saldo KAS</p>
                    <p class="text-4xl font-black">Rp {{ number_format($balance, 0, ',', '.') }}</p>
                </div>
                <!-- Total Income -->
                <div
                    class="bg-green-500/10 dark:bg-green-500/20 rounded-2xl p-8 text-center border border-green-500/20">
                    <span
                        class="material-symbols-outlined text-4xl mb-3 text-green-600 dark:text-green-400">trending_up</span>
                    <p class="text-green-700 dark:text-green-300 text-sm font-bold uppercase tracking-wider mb-2">Total
                        Pemasukan</p>
                    <p class="text-3xl font-black text-green-600 dark:text-green-400">Rp
                        {{ number_format($totalIncome, 0, ',', '.') }}
                    </p>
                </div>
                <!-- Total Expense -->
                <div class="bg-red-500/10 dark:bg-red-500/20 rounded-2xl p-8 text-center border border-red-500/20">
                    <span
                        class="material-symbols-outlined text-4xl mb-3 text-red-600 dark:text-red-400">trending_down</span>
                    <p class="text-red-700 dark:text-red-300 text-sm font-bold uppercase tracking-wider mb-2">Total
                        Pengeluaran</p>
                    <p class="text-3xl font-black text-red-600 dark:text-red-400">Rp
                        {{ number_format($totalExpense, 0, ',', '.') }}
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Period Filter -->
    <section
        class="py-6 bg-background-light dark:bg-background-dark/50 border-b border-primary/10 dark:border-white/10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-wrap gap-2 justify-center">
                <button wire:click="$set('period', 'month')"
                    class="px-6 py-2 rounded-lg text-sm font-bold transition-all {{ $period == 'month' ? 'bg-primary text-white' : 'bg-white dark:bg-white/10 text-gray-600 dark:text-white/70 hover:bg-primary/10' }}">
                    Bulan Ini
                </button>
                <button wire:click="$set('period', 'year')"
                    class="px-6 py-2 rounded-lg text-sm font-bold transition-all {{ $period == 'year' ? 'bg-primary text-white' : 'bg-white dark:bg-white/10 text-gray-600 dark:text-white/70 hover:bg-primary/10' }}">
                    Tahun Ini
                </button>
                <button wire:click="$set('period', 'all')"
                    class="px-6 py-2 rounded-lg text-sm font-bold transition-all {{ $period == 'all' ? 'bg-primary text-white' : 'bg-white dark:bg-white/10 text-gray-600 dark:text-white/70 hover:bg-primary/10' }}">
                    Semua
                </button>
            </div>
        </div>
    </section>

    <!-- Transactions Table -->
    <section class="py-12 bg-background-light dark:bg-background-dark">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Transactions List -->
                <div class="lg:col-span-2">
                    <div
                        class="bg-white dark:bg-white/5 rounded-2xl shadow-sm border border-primary/5 dark:border-white/5 overflow-hidden">
                        <div class="px-6 py-4 border-b border-primary/10 dark:border-white/10">
                            <h3 class="font-bold text-lg text-background-dark dark:text-white">Riwayat Transaksi</h3>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-gray-50 dark:bg-white/5">
                                    <tr
                                        class="text-left text-xs font-bold text-gray-500 dark:text-white/50 uppercase tracking-wider">
                                        <th class="px-6 py-3">Tanggal</th>
                                        <th class="px-6 py-3">Keterangan</th>
                                        <th class="px-6 py-3">Kategori</th>
                                        <th class="px-6 py-3 text-right">Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-primary/5 dark:divide-white/5">
                                    @forelse($transactions as $tx)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-white/5">
                                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-white/70">
                                                {{ $tx->transaction_date->format('d M Y') }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-background-dark dark:text-white">
                                                {{ $tx->description }}
                                            </td>
                                            <td class="px-6 py-4">
                                                <span
                                                    class="px-2 py-1 text-xs font-bold rounded-full {{ $tx->type === 'income' ? 'bg-green-100 text-green-700 dark:bg-green-500/20 dark:text-green-400' : 'bg-red-100 text-red-700 dark:bg-red-500/20 dark:text-red-400' }}">
                                                    {{ ucfirst($tx->type === 'income' ? ($tx->incomeType?->name ?? '-') : ($tx->expenseType?->name ?? '-')) }}
                                                </span>
                                            </td>
                                            <td
                                                class="px-6 py-4 text-right font-bold {{ $tx->type === 'income' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                                {{ $tx->type === 'income' ? '+' : '-' }} Rp
                                                {{ number_format($tx->amount, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-6 py-12 text-center text-gray-500 dark:text-white/50">
                                                Belum ada transaksi
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="px-6 py-4 border-t border-primary/10 dark:border-white/10">
                            {{ $transactions->links() }}
                        </div>
                    </div>
                </div>

                <!-- Category Summary -->
                <div>
                    <div
                        class="bg-white dark:bg-white/5 rounded-2xl shadow-sm border border-primary/5 dark:border-white/5 p-6 mb-6">
                        <h4 class="font-bold text-background-dark dark:text-white mb-4">Pemasukan per Kategori</h4>
                        <div class="space-y-3">
                            @forelse($incomeSummary as $item)
                                <div class="flex justify-between items-center">
                                    <span
                                        class="text-sm text-gray-600 dark:text-white/70">{{ ucfirst($item->category) }}</span>
                                    <span class="font-bold text-green-600 dark:text-green-400">Rp
                                        {{ number_format($item->total, 0, ',', '.') }}</span>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500 dark:text-white/50">Belum ada data</p>
                            @endforelse
                        </div>
                    </div>

                    <div
                        class="bg-white dark:bg-white/5 rounded-2xl shadow-sm border border-primary/5 dark:border-white/5 p-6">
                        <h4 class="font-bold text-background-dark dark:text-white mb-4">Pengeluaran per Kategori</h4>
                        <div class="space-y-3">
                            @forelse($expenseSummary as $item)
                                <div class="flex justify-between items-center">
                                    <span
                                        class="text-sm text-gray-600 dark:text-white/70">{{ ucfirst($item->category) }}</span>
                                    <span class="font-bold text-red-600 dark:text-red-400">Rp
                                        {{ number_format($item->total, 0, ',', '.') }}</span>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500 dark:text-white/50">Belum ada data</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>