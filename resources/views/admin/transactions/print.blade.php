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
            gap: 10px;
            margin-bottom: 15px;
        }

        .summary-card {
            border: 1px solid #000;
            border-radius: 8px;
            padding: 10px;
            text-align: center;
        }

        .summary-card.primary {
            background-color: #006064;
            color: #fff;
            border-color: #004d40;
        }

        .summary-card.primary .label,
        .summary-card.primary .amount {
            color: #fff;
        }

        .summary-card.income {
            background-color: #ecfdf5;
            border-color: #16a34a;
        }

        .summary-card.income .label {
            color: #15803d;
        }

        .summary-card.income .amount {
            color: #16a34a;
        }

        .summary-card.expense {
            background-color: #fef2f2;
            border-color: #dc2626;
        }

        .summary-card.expense .label {
            color: #b91c1c;
        }

        .summary-card.expense .amount {
            color: #dc2626;
        }

        .summary-card .label {
            font-size: 9pt;
            text-transform: uppercase;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .summary-card .amount {
            font-size: 14pt;
            font-weight: bold;
        }

        /* Category Summary */
        .category-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 15px;
        }

        .category-box {
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 10px;
        }

        .category-box.income {
            background-color: #ecfdf5;
            border-color: #16a34a;
        }

        .category-box.expense {
            background-color: #fef2f2;
            border-color: #dc2626;
        }

        .category-box h4 {
            font-size: 11pt;
            margin-bottom: 8px;
            font-weight: bold;
        }

        .category-item {
            display: flex;
            justify-content: space-between;
            font-size: 10pt;
            padding: 3px 0;
        }

        .category-item .value.income {
            color: #16a34a;
            font-weight: bold;
        }

        .category-item .value.expense {
            color: #dc2626;
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

    <!-- Report Title -->
    <div class="report-title">
        <h2>LAPORAN KOIN NU</h2>
        <p>Dicetak pada: {{ now()->setTimezone('Asia/Jakarta')->format('d M Y H:i') }}</p>
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
    <div class="category-section">
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
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $tx->transaction_date->format('d M Y') }}</td>
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