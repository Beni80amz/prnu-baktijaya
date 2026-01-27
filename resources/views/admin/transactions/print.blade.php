<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Keuangan PRNU Baktijaya {{ now()->setTimezone('Asia/Jakarta')->format('d-m-Y H.i') }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            color: #000;
            background: #fff;
            padding: 1cm;
        }

        @media print {
            @page {
                size: A4;
                margin: 1cm;
            }

            body {
                padding: 0;
            }

            .no-print {
                display: none !important;
            }
        }

        /* Header / Kop */
        .kop-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 3px solid #006064;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }

        .kop-logo {
            width: 80px;
            height: 80px;
        }

        .kop-logo img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        .kop-text {
            flex: 1;
            text-align: center;
            padding: 0 10px;
        }

        .kop-title {
            font-size: 20pt;
            font-weight: 900;
            color: #006064;
            margin: 0;
        }

        .kop-subtitle {
            font-size: 11pt;
            font-weight: bold;
            margin: 0;
        }

        .kop-address {
            font-size: 9pt;
            color: #333;
            margin: 0;
        }

        .kop-contact {
            font-size: 9pt;
            color: #0284c7;
            margin: 0;
        }

        /* Report Title */
        .report-title {
            text-align: center;
            margin-bottom: 15px;
        }

        .report-title h2 {
            font-size: 16pt;
            font-weight: 800;
            text-transform: uppercase;
            margin: 0;
        }

        .report-title p {
            font-size: 9pt;
            color: #555;
            margin-top: 2px;
        }

        /* Summary Cards */
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            margin-bottom: 20px;
        }

        .summary-card {
            border-radius: 10px;
            padding: 15px 12px;
            text-align: center;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        .summary-card.primary {
            background-color: #006064 !important;
            background: linear-gradient(135deg, #006064 0%, #004d40 100%) !important;
            color: #fff;
            border: 2px solid #004d40;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .summary-card.primary .label,
        .summary-card.primary .amount {
            color: #fff !important;
        }

        .summary-card.income {
            background-color: #dcfce7 !important;
            background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%) !important;
            border: 2px solid #22c55e;
        }

        .summary-card.income .label {
            color: #166534 !important;
        }

        .summary-card.income .amount {
            color: #15803d !important;
        }

        .summary-card.expense {
            background-color: #fee2e2 !important;
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%) !important;
            border: 2px solid #ef4444;
        }

        .summary-card.expense .label {
            color: #991b1b !important;
        }

        .summary-card.expense .amount {
            color: #dc2626 !important;
        }

        .summary-card .label {
            font-size: 10pt;
            text-transform: uppercase;
            font-weight: bold;
            margin-bottom: 8px;
            letter-spacing: 0.5px;
        }

        .summary-card .amount {
            font-size: 16pt;
            font-weight: 900;
        }

        /* Category Summary */
        .category-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 20px;
        }

        .category-box {
            border-radius: 10px;
            padding: 15px;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        .category-box.income {
            background-color: #dcfce7 !important;
            background: linear-gradient(180deg, #dcfce7 0%, #f0fdf4 100%) !important;
            border: 2px solid #22c55e;
        }

        .category-box.income h4 {
            color: #166534 !important;
            border-bottom: 2px solid #22c55e;
            padding-bottom: 8px;
            margin-bottom: 12px;
        }

        .category-box.expense {
            background-color: #fee2e2 !important;
            background: linear-gradient(180deg, #fee2e2 0%, #fef2f2 100%) !important;
            border: 2px solid #ef4444;
        }

        .category-box.expense h4 {
            color: #991b1b !important;
            border-bottom: 2px solid #ef4444;
            padding-bottom: 8px;
            margin-bottom: 12px;
        }

        .category-box h4 {
            font-size: 11pt;
            font-weight: bold;
        }

        .category-item {
            display: flex;
            justify-content: space-between;
            font-size: 10pt;
            padding: 5px 0;
            border-bottom: 1px dashed #ccc;
        }

        .category-item:last-child {
            border-bottom: none;
        }

        .category-item .value.income {
            color: #15803d !important;
            font-weight: bold;
        }

        .category-item .value.expense {
            color: #dc2626 !important;
            font-weight: bold;
        }

        /* Table */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        th {
            background-color: #eee;
            border: 1px solid #000;
            padding: 6px 4px;
            font-size: 10pt;
            text-align: left;
        }

        td {
            border: 1px solid #000;
            padding: 5px 4px;
            font-size: 10pt;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .income-text {
            color: #16a34a;
        }

        .expense-text {
            color: #dc2626;
        }

        /* Footer */
        .footer {
            margin-top: 30px;
            text-align: center;
        }

        .footer p {
            font-size: 9pt;
            color: #666;
        }

        .qr-code {
            margin: 10px auto;
        }

        /* Print Button */
        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #006064;
            color: #fff;
            border: none;
            padding: 10px 20px;
            font-size: 14px;
            cursor: pointer;
            border-radius: 5px;
            z-index: 1000;
        }

        .print-button:hover {
            background: #004d40;
        }
    </style>
</head>

<body>
    <button class="print-button no-print" onclick="window.print()">🖨️ Cetak / Print</button>

    <!-- Header / KOP -->
    <div class="kop-container">
        <div class="kop-logo">
            @php
                $logo = \App\Models\Setting::where('key', 'logo')
                    ->orWhere('key', 'logo_website')
                    ->orWhere('key', 'site_logo')
                    ->orWhere('label', 'Logo Website')
                    ->value('value');
            @endphp
            @if($logo)
                <img src="{{ asset('storage/' . $logo) }}" alt="Logo">
            @else
                <img src="{{ asset('images/logo.png') }}" onerror="this.style.display='none'" alt="Logo">
            @endif
        </div>
        <div class="kop-text">
            <h1 class="kop-title">PRNU BAKTIJAYA</h1>
            <p class="kop-subtitle">Merawat Jagad, Membangun Peradaban</p>
            <p class="kop-address">Jalan Keadilan Raya No. 1, Baktijaya, Sukmajaya, Kota Depok</p>
            <p class="kop-contact">Email: prnu355@gmail.com | Kontak: 0894-0967-7894</p>
        </div>
        <div class="kop-logo"></div>
    </div>

    @php
        // Indonesian month names
        $bulanIndo = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];
        $now = now()->setTimezone('Asia/Jakarta');
        $tanggalCetak = $now->format('d') . ' ' . $bulanIndo[(int) $now->format('m')] . ' ' . $now->format('Y H:i');
    @endphp

    <!-- Report Title -->
    <div class="report-title">
        <h2>LAPORAN KOIN NU @if($filterLabel) - {{ strtoupper($filterLabel) }} @endif</h2>
        <p>Dicetak pada: {{ $tanggalCetak }} WIB</p>
    </div>

    <!-- Summary Cards -->
    <div class="summary-grid">
        <div class="summary-card primary">
            <div class="label">Saldo KAS</div>
            <div class="amount">Rp {{ number_format($balance, 0, ',', '.') }}</div>
        </div>
        <div class="summary-card income">
            <div class="label">Total Pemasukan</div>
            <div class="amount">Rp {{ number_format($totalIncome, 0, ',', '.') }}</div>
        </div>
        <div class="summary-card expense">
            <div class="label">Total Pengeluaran</div>
            <div class="amount">Rp {{ number_format($totalExpense, 0, ',', '.') }}</div>
        </div>
    </div>

    <!-- Category Summary -->
    <div class="category-section"
        style="{{ ($typeFilter === 'income' || $typeFilter === 'expense') ? 'grid-template-columns: 1fr;' : '' }}">
        @if(!$typeFilter || $typeFilter === 'income')
            <div class="category-box income">
                <h4>Pemasukan per Kategori</h4>
                @forelse($incomeSummary as $item)
                    <div class="category-item">
                        <span>{{ ucfirst($item->category) }}</span>
                        <span class="value income">Rp {{ number_format($item->total, 0, ',', '.') }}</span>
                    </div>
                @empty
                    <p style="font-size: 10pt; color: #666;">Belum ada data</p>
                @endforelse
            </div>
        @endif
        @if(!$typeFilter || $typeFilter === 'expense')
            <div class="category-box expense">
                <h4>Pengeluaran per Kategori</h4>
                @forelse($expenseSummary as $item)
                    <div class="category-item">
                        <span>{{ ucfirst($item->category) }}</span>
                        <span class="value expense">Rp {{ number_format($item->total, 0, ',', '.') }}</span>
                    </div>
                @empty
                    <p style="font-size: 10pt; color: #666;">Belum ada data</p>
                @endforelse
            </div>
        @endif
    </div>

    <!-- Transaction Table -->
    <h3 style="font-size: 12pt; margin-bottom: 10px;">Riwayat Transaksi</h3>
    <table>
        <thead>
            <tr>
                <th class="text-center" style="width: 40px;">No</th>
                <th>Tanggal</th>
                <th>Keterangan</th>
                <th>Kategori</th>
                <th class="text-right">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $index => $tx)
                @php
                    $txDate = $tx->transaction_date;
                    $tanggalTx = $txDate->format('d') . ' ' . $bulanIndo[(int) $txDate->format('m')] . ' ' . $txDate->format('Y');
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $tanggalTx }}</td>
                    <td>{{ $tx->description }}</td>
                    <td>
                        <span class="{{ $tx->type === 'income' ? 'income-text' : 'expense-text' }}">
                            {{ $tx->type === 'income' ? ($tx->incomeType?->name ?? '-') : ($tx->expenseType?->name ?? '-') }}
                        </span>
                    </td>
                    <td class="text-right {{ $tx->type === 'income' ? 'income-text' : 'expense-text' }}">
                        {{ $tx->type === 'income' ? '+' : '-' }} Rp {{ number_format($tx->amount, 0, ',', '.') }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">Belum ada transaksi</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Footer -->
    <div class="footer">
        <p>Dokumen ini diverifikasi secara digital.<br>Scan QR Code untuk validasi keaslian.</p>
        <div class="qr-code">
            {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(100)->margin(0)->generate(route('kas-digital')) !!}
        </div>
        <p>PRNU Baktijaya © {{ date('Y') }}</p>
    </div>
</body>

</html>