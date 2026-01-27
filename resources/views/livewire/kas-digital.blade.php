<div>
    <style>
        @media print {

            /* Page Setup - A4 Explicit */
            @page {
                size: A4;
                margin: 1cm;
            }

            body {
                margin: 0 !important;
                padding: 0 !important;
                background-color: white !important;
                color: black !important;
                font-family: 'Times New Roman', Times, serif !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            /* Hide Screen Elements */
            .no-print,
            nav,
            header,
            footer,
            button,
            .hidden-print {
                display: none !important;
            }

            /* Show Print Elements */
            #print-header,
            #print-footer {
                display: block !important;
                visibility: visible !important;
            }

            /* Universal Text Reset */
            div,
            p,
            h1,
            h2,
            h3,
            h4,
            span,
            label {
                color: black !important;
                text-shadow: none !important;
            }

            /* Colors */
            .print-primary,
            .text-primary {
                color: #006064 !important;
            }

            .print-green,
            .text-green-600,
            .text-green-700 {
                color: #16a34a !important;
            }

            .print-red,
            .text-red-600,
            .text-red-700 {
                color: #dc2626 !important;
            }

            /* Background Removal */
            .bg-white,
            .bg-gray-50,
            .bg-gradient-to-br,
            .bg-green-500,
            .bg-red-500,
            [class*="dark:bg-"] {
                background: white !important;
                background-color: white !important;
                background-image: none !important;
            }

            .shadow-sm,
            .shadow-lg,
            .shadow {
                box-shadow: none !important;
            }

            /* Clean Containers */
            div:not(.print-card):not(.kop-separator):not(table):not(tr):not(td):not(th) {
                border: none !important;
            }

            div:not(.print-card) {
                border-radius: 0 !important;
            }

            /* Reduce Section Padding (Tighten Layout) */
            section {
                padding-top: 5px !important;
                padding-bottom: 5px !important;
            }

            /* Specific Components */
            .kop-separator {
                border-bottom: 3px solid #006064 !important;
                margin-bottom: 5px !important;
                padding-bottom: 5px !important;
                position: relative !important;
                display: flex !important;
                justify-content: space-between !important;
            }

            /* CARDS: Thinner Border */
            .print-card {
                border: 1px solid #000 !important;
                border-radius: 8px !important;
                padding: 10px !important;
                background: white !important;
                color: black !important;
                box-shadow: none !important;
            }

            .print-card .print-label {
                font-size: 9pt !important;
                color: #555 !important;
            }

            .print-card .print-amount {
                font-size: 14pt !important;
                font-weight: bold !important;
            }

            /* Color Overrides for Cards */
            .print-card-primary {
                background-color: #006064 !important;
                color: white !important;
                border-color: #004d40 !important;
            }

            /* Explicitly force all children of primary card to be white */
            .print-card-primary,
            .print-card-primary * {
                color: white !important;
            }

            .print-card-green {
                background-color: #ecfdf5 !important;
                color: #166534 !important;
                border-color: #16a34a !important;
            }

            .print-card-green .print-label {
                color: #15803d !important;
            }

            .print-card-green .print-amount {
                color: #16a34a !important;
            }

            .print-card-red {
                background-color: #fef2f2 !important;
                color: #991b1b !important;
                border-color: #dc2626 !important;
            }

            .print-card-red .print-label {
                color: #b91c1c !important;
            }

            .print-card-red .print-amount {
                color: #dc2626 !important;
            }

            /* Category Summary Backgrounds */
            .print-bg-green-soft {
                background-color: #ecfdf5 !important;
                border: 1px solid #16a34a !important;
                border-radius: 8px !important;
            }

            .print-bg-red-soft {
                background-color: #fef2f2 !important;
                border: 1px solid #dc2626 !important;
                border-radius: 8px !important;
            }

            /* Grid Layout */
            #balance-grid {
                display: grid !important;
                grid-template-columns: repeat(3, 1fr) !important;
                gap: 10px !important;
                margin-bottom: 5px !important;
                /* Tight spacing */
                width: 100% !important;
            }

            /* Table Styling */
            table {
                width: 100% !important;
                border-collapse: collapse !important;
                margin-bottom: 10px !important;
            }

            th {
                background-color: #eee !important;
                color: black !important;
                border: 1px solid black !important;
                padding: 4px !important;
                font-size: 10pt !important;
            }

            td {
                border: 1px solid black !important;
                padding: 4px !important;
                color: black !important;
                font-size: 10pt !important;
            }

            tr {
                page-break-inside: avoid;
            }

            /* Footer - STATIC Position (No Overlap) */
            #print-footer {
                position: relative !important;
                width: 100%;
                text-align: center;
                background: white;
                margin-top: 2cm !important;
                padding-bottom: 1cm;
                page-break-inside: avoid;
            }
        }
    </style>

    <!-- Print Header -->
    <div id="print-header" class="hidden">
        <div class="kop-separator">
            <!-- Logo -->
            <div style="width: 100px; height: 80px; display: flex; align-items: center; justify-content: center;">
                @php
                    $logo = \App\Models\Setting::where('key', 'logo')
                        ->orWhere('key', 'logo_website')
                        ->orWhere('key', 'site_logo')
                        ->orWhere('label', 'Logo Website')
                        ->value('value');
                @endphp
                @if($logo)
                    <img src="{{ asset('storage/' . $logo) }}"
                        style="max-width: 100%; max-height: 100%; object-fit: contain; display: block;" alt="Logo">
                @else
                    <img src="{{ asset('images/logo.png') }}" onerror="this.style.display='none'"
                        style="max-width: 100%; max-height: 100%; object-fit: contain;" alt="Logo">
                @endif
            </div>

            <!-- Text Info -->
            <div style="flex: 1; text-align: center; padding: 0 5px;">
                <h1 style="font-size: 20pt; font-weight: 900; color: #006064 !important; margin: 0; line-height: 1.1;">
                    PRNU BAKTIJAYA</h1>
                <p style="font-size: 11pt; font-weight: bold; margin: 0; color: black !important;">Merawat Jagad,
                    Membangun Peradaban</p>
                <p style="font-size: 9pt; color: #333 !important; margin: 0;">Jalan Keadilan Raya No. 1, Baktijaya,
                    Sukmajaya, Kota Depok</p>
                <p style="font-size: 9pt; color: #0284c7 !important; margin: 0;">Email: prnu355@gmail.com &nbsp;|&nbsp;
                    Kontak: 0894-0967-7894</p>
            </div>

            <div style="width: 100px;"></div>
        </div>

        <div style="text-align: center; margin-bottom: 10px;">
            <h2
                style="font-size: 16pt; font-weight: 800; text-transform: uppercase; margin: 0; color: black !important;">
                LAPORAN KOIN NU</h2>
            <p style="font-size: 9pt; color: #555 !important; margin-top: 0;">Dicetak pada:
                {{ now()->setTimezone('Asia/Jakarta')->format('d M Y H:i') }}
            </p>
        </div>
    </div>

    <!-- Page Header (Screen Only) -->
    <section class="bg-gradient-to-br from-primary to-primary-dark py-20 no-print">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl md:text-5xl font-black text-white mb-4">Transparansi Keuangan</h1>
            <p class="text-white/80 text-lg max-w-2xl mx-auto">Laporan keuangan/KAS Organisasi PRNU Baktijaya secara
                terbuka dan transparan</p>
        </div>
    </section>

    <!-- Balance Cards -->
    <section
        class="py-12 px-4 sm:px-0 bg-white dark:bg-background-dark border-b border-primary/10 dark:border-white/10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div id="balance-grid" class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Total Balance -->
                <div
                    class="bg-gradient-to-br from-primary to-primary-dark rounded-2xl p-8 text-white text-center print-card print-card-primary">
                    <span
                        class="material-symbols-outlined text-6xl mb-4 opacity-80 no-print">account_balance_wallet</span>
                    <p class="text-white/80 text-sm font-bold uppercase tracking-wider mb-2 print-label"
                        style="color: white !important;">Saldo KAS</p>
                    <p class="text-4xl font-black print-amount print-primary" style="color: white;">Rp
                        {{ number_format($balance, 0, ',', '.') }}
                    </p>
                </div>
                <!-- Total Income -->
                <div
                    class="bg-green-500/10 dark:bg-green-500/20 rounded-2xl p-8 text-center border border-green-500/20 print-card print-card-green">
                    <span
                        class="material-symbols-outlined text-6xl mb-4 text-green-600 dark:text-green-400 no-print">trending_up</span>
                    <p
                        class="text-green-700 dark:text-green-300 text-sm font-bold uppercase tracking-wider mb-2 print-label">
                        Total Pemasukan</p>
                    <p class="text-3xl font-black text-green-600 dark:text-green-400 print-amount print-green">Rp
                        {{ number_format($totalIncome, 0, ',', '.') }}
                    </p>
                </div>
                <!-- Total Expense -->
                <div
                    class="bg-red-500/10 dark:bg-red-500/20 rounded-2xl p-8 text-center border border-red-500/20 print-card print-card-red">
                    <span
                        class="material-symbols-outlined text-6xl mb-4 text-red-600 dark:text-red-400 no-print">trending_down</span>
                    <p
                        class="text-red-700 dark:text-red-300 text-sm font-bold uppercase tracking-wider mb-2 print-label">
                        Total Pengeluaran</p>
                    <p class="text-3xl font-black text-red-600 dark:text-red-400 print-amount print-red">Rp
                        {{ number_format($totalExpense, 0, ',', '.') }}
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Period Filter -->
    <section
        class="py-6 bg-background-light dark:bg-background-dark/50 border-b border-primary/10 dark:border-white/10 no-print">
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
                <!-- Transactions List -->
                <div class="lg:col-span-2">
                    <!-- Control Bar -->
                    <div class="mb-6 flex flex-col md:flex-row gap-4 justify-between items-center no-print">
                        <div class="flex-1 w-full md:w-auto flex gap-2">
                            <!-- Search -->
                            <div class="relative flex-1">
                                <span
                                    class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">search</span>
                                <input wire:model.live.debounce.300ms="search" type="text"
                                    class="w-full pl-10 pr-4 py-2 bg-white dark:bg-white/5 border border-primary/10 dark:border-white/10 rounded-lg text-sm text-gray-700 dark:text-white focus:ring-2 focus:ring-primary/50 focus:border-primary"
                                    placeholder="Cari transaksi...">
                            </div>
                            <!-- Filter Category -->
                            <select wire:model.live="categoryFilter"
                                class="pl-4 pr-8 py-2 bg-white dark:bg-white/5 border border-primary/10 dark:border-white/10 rounded-lg text-sm text-gray-700 dark:text-white focus:ring-2 focus:ring-primary/50 focus:border-primary">
                                <option value="">Semua Kategori</option>
                                <optgroup label="Pemasukan">
                                    @foreach($incomeTypes as $type)
                                        <option value="income:{{ $type->id }}">{{ $type->name }}</option>
                                    @endforeach
                                </optgroup>
                                <optgroup label="Pengeluaran">
                                    @foreach($expenseTypes as $type)
                                        <option value="expense:{{ $type->id }}">{{ $type->name }}</option>
                                    @endforeach
                                </optgroup>
                            </select>
                        </div>
                    </div>

                    <div
                        class="bg-white dark:bg-white/5 rounded-2xl shadow-sm border border-primary/5 dark:border-white/5 overflow-hidden print:border-0 print:shadow-none">
                        <div
                            class="px-6 py-4 border-b border-primary/10 dark:border-white/10 flex justify-between items-center">
                            <h3 class="font-bold text-background-dark dark:text-white">Riwayat Transaksi</h3>
                            <span class="text-xs text-gray-500 print:block hidden">Dicetak pada:
                                {{ now()->setTimezone('Asia/Jakarta')->format('d M Y H:i') }}</span>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead
                                    class="bg-gray-50/50 dark:bg-white/5 border-b border-gray-100 dark:border-white/5">
                                    <tr
                                        class="text-left text-xs font-bold text-gray-500 dark:text-white/50 uppercase tracking-wider">
                                        <th class="px-6 py-4 text-center w-16">No</th>
                                        <th class="px-6 py-4">Tanggal</th>
                                        <th class="px-6 py-4">Keterangan</th>
                                        <th class="px-6 py-4">Kategori</th>
                                        <th class="px-6 py-4 text-right">Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($transactions as $tx)
                                                                    <tr
                                                                        class="border-b border-gray-100 dark:border-white/5 hover:bg-gray-50 dark:hover:bg-white/5 transition-colors group">
                                                                        <td
                                                                            class="px-6 py-4 text-sm text-center text-gray-500 dark:text-white/50 font-medium">
                                                                            {{ ($transactions->currentPage() - 1) * $transactions->perPage() + $loop->iteration }}
                                                                        </td>
                                                                        <td
                                                                            class="px-6 py-4 text-sm text-gray-600 dark:text-white/70 whitespace-nowrap">
                                                                            <div class="font-medium text-gray-900 dark:text-white">
                                                                                {{ $tx->transaction_date->format('d M Y') }}
                                                                            </div>
                                                                        </td>
                                                                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-white/70">
                                                                            {{ $tx->description }}
                                                                        </td>
                                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                                            <span
                                                                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold border
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        {{ $tx->type === 'income'
                                        ? 'bg-green-50 text-green-700 border-green-200 dark:bg-green-500/20 dark:text-green-400 dark:border-green-500/30'
                                        : 'bg-red-50 text-red-700 border-red-200 dark:bg-red-500/20 dark:text-red-400 dark:border-red-500/30' }}">
                                                                                {{ ucfirst($tx->type === 'income' ? ($tx->incomeType?->name ?? '-') : ($tx->expenseType?->name ?? '-')) }}
                                                                            </span>
                                                                        </td>
                                                                        <td
                                                                            class="px-6 py-4 text-right whitespace-nowrap font-bold {{ $tx->type === 'income' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                                                            {{ $tx->type === 'income' ? '+' : '-' }} Rp
                                                                            {{ number_format($tx->amount, 0, ',', '.') }}
                                                                        </td>
                                                                    </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-6 py-12 text-center text-gray-500 dark:text-white/50">
                                                <div class="flex flex-col items-center justify-center space-y-2">
                                                    <span
                                                        class="material-symbols-outlined text-4xl opacity-20">receipt_long</span>
                                                    <p>Belum ada transaksi</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="px-6 py-4 border-t border-primary/10 dark:border-white/10 print:hidden">
                            {{ $transactions->links('vendor.livewire.tailwind') }}
                        </div>
                    </div>
                </div>

                <!-- Category Summary -->
                <div>
                    <div
                        class="bg-white dark:bg-white/5 rounded-2xl shadow-sm border border-primary/5 dark:border-white/5 p-6 mb-6 print:p-2 print:mb-2 print-bg-green-soft">
                        <h4 class="font-bold text-background-dark dark:text-white mb-4 print:mb-1">Pemasukan per
                            Kategori</h4>
                        <div class="space-y-3 print:space-y-1">
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
                        class="bg-white dark:bg-white/5 rounded-2xl shadow-sm border border-primary/5 dark:border-white/5 p-6 print:p-2 print-bg-red-soft">
                        <h4 class="font-bold text-background-dark dark:text-white mb-4 print:mb-1">Pengeluaran per
                            Kategori</h4>
                        <div class="space-y-3 print:space-y-1">
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
    <!-- Print Footer (Controlled by CSS ID) -->
    <div id="print-footer" class="hidden">
        <div class="flex flex-col items-center justify-center gap-2">
            <p class="text-xs text-gray-500 font-mono">Dokumen ini diverifikasi secara digital.<br>Scan QR Code untuk
                validasi keaslian.</p>
            <div class="inline-block" style="line-height: 0;">
                {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(120)->margin(0)->generate(route('kas-digital')) !!}
            </div>
            <p class="text-[10px] text-gray-400 mt-1">PRNU Baktijaya © {{ date('Y') }}</p>
        </div>
    </div>
</div>