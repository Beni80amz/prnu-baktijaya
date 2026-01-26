<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KasController extends Controller
{
    public function index()
    {
        $generalBalance = Transaction::where('fund_type', 'general')
            ->select(DB::raw("SUM(CASE WHEN type = 'income' THEN amount ELSE -amount END) as balance"))
            ->value('balance') ?? 0;

        $infaqShodaqoh = Transaction::where('type', 'income')
            ->where('income_type_id', 1)
            ->sum('amount') ?? 0;

        $koinNu = Transaction::where('type', 'income')
            ->where('income_type_id', 2)
            ->sum('amount') ?? 0;

        $totalExpense = Transaction::where('type', 'expense')
            ->sum('amount') ?? 0;

        // Monthly Trends for Chart (Last 6 Months)
        $monthlyTrends = [];
        for ($i = 5; $i >= 0; $i--) {
            $monthDate = now()->subMonths($i);
            $monthName = $monthDate->format('M');
            $monthYear = $monthDate->format('Y-m');

            $income = Transaction::where('type', 'income')
                ->where('transaction_date', 'like', "$monthYear-%")
                ->sum('amount') ?? 0;

            $expense = Transaction::where('type', 'expense')
                ->where('transaction_date', 'like', "$monthYear-%")
                ->sum('amount') ?? 0;

            $monthlyTrends[] = [
                'month' => $monthName,
                'income' => (float) $income,
                'expense' => (float) $expense,
            ];
        }

        $recentTransactions = Transaction::with(['incomeType', 'expenseType'])
            ->orderBy('transaction_date', 'desc')
            ->take(10)
            ->get()
            ->map(function ($transaction) {
                return [
                    'id' => $transaction->id,
                    'title' => $transaction->description ?? ($transaction->type == 'income' ? ($transaction->incomeType->name ?? 'Pemasukan') : ($transaction->expenseType->name ?? 'Pengeluaran')),
                    'date' => \Carbon\Carbon::parse($transaction->transaction_date)->format('d M Y'),
                    'amount' => $transaction->amount,
                    'type' => $transaction->type,
                    'fund_type' => $transaction->fund_type,
                    'category' => $transaction->type == 'income' ? ($transaction->incomeType->name ?? 'Pemasukan') : ($transaction->expenseType->name ?? 'Pengeluaran'),
                ];
            });

        return response()->json([
            'success' => true,
            'data' => [
                'balances' => [
                    'general' => (float) $generalBalance,
                    'infaq_shodaqoh' => (float) $infaqShodaqoh,
                    'koin_nu' => (float) $koinNu,
                    'total_expense' => (float) $totalExpense,
                ],
                'monthly_trends' => $monthlyTrends,
                'recent_transactions' => $recentTransactions,
                'last_update' => now()->format('H:i') . ' WIB',
            ]
        ]);
    }

    public function history(Request $request)
    {
        $query = Transaction::with(['incomeType', 'expenseType']);

        // Filter by Type
        if ($request->has('type') && in_array($request->type, ['income', 'expense'])) {
            $query->where('type', $request->type);
        }

        // Search
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('description', 'like', "%$searchTerm%")
                    ->orWhereHas('incomeType', function ($iq) use ($searchTerm) {
                        $iq->where('name', 'like', "%$searchTerm%");
                    })
                    ->orWhereHas('expenseType', function ($eq) use ($searchTerm) {
                        $eq->where('name', 'like', "%$searchTerm%");
                    });
            });
        }

        // Filter by Month
        if ($request->has('month') && !empty($request->month)) {
            // Expecting YYYY-MM format
            $query->where('transaction_date', 'like', $request->month . '-%');
        }

        $transactions = $query->orderBy('transaction_date', 'desc')->get();

        // Calculate Totals for the current result set
        $totalIncome = (float) $transactions->where('type', 'income')->sum('amount');
        $totalExpense = (float) $transactions->where('type', 'expense')->sum('amount');

        // Group by Date
        $grouped = [];
        foreach ($transactions as $t) {
            $date = \Carbon\Carbon::parse($t->transaction_date);
            $key = $date->format('Y-m-d');
            $label = $this->getDateLabel($date);

            if (!isset($grouped[$key])) {
                $grouped[$key] = [
                    'date' => $key,
                    'label' => $label,
                    'items' => []
                ];
            }

            $grouped[$key]['items'][] = [
                'id' => $t->id,
                'title' => $t->description ?? ($t->type == 'income' ? ($t->incomeType->name ?? 'Pemasukan') : ($t->expenseType->name ?? 'Pengeluaran')),
                'time' => $date->format('H:i') . ' WIB',
                'amount' => (float) $t->amount,
                'type' => $t->type,
                'category' => $t->type == 'income' ? ($t->incomeType->name ?? 'Pemasukan') : ($t->expenseType->name ?? 'Pengeluaran'),
                'icon_type' => $this->getIconType($t),
            ];
        }

        return response()->json([
            'success' => true,
            'data' => [
                'summary' => [
                    'total_income' => $totalIncome,
                    'total_expense' => $totalExpense,
                ],
                'transactions' => array_values($grouped)
            ]
        ]);
    }

    private function getDateLabel($date)
    {
        if ($date->isToday())
            return 'Hari Ini - ' . $date->format('d M Y');
        if ($date->isYesterday())
            return 'Kemarin - ' . $date->format('d M Y');
        return $date->format('d M Y');
    }

    private function getIconType($t)
    {
        if ($t->type == 'income') {
            if (stripos($t->description, 'Zakat') !== false)
                return 'person_heart';
            if (stripos($t->description, 'Infaq') !== false || stripos($t->description, 'Shodaqoh') !== false)
                return 'volunteer_activism';
            return 'payments';
        } else {
            if (stripos($t->description, 'Santunan') !== false)
                return 'groups';
            if (stripos($t->description, 'Listrik') !== false || stripos($t->description, 'Internet') !== false)
                return 'settings';
            return 'inventory_2';
        }
    }

    public function reports(Request $request)
    {
        // Get unique years from transactions
        $years = Transaction::select(DB::raw('YEAR(transaction_date) as year'))
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->map(function ($year) {
                return (string) $year;
            });

        // If no transactions, provide current year as default
        if ($years->isEmpty()) {
            $years = [(string) now()->year];
        }

        // Current Fund Types
        $fundTypes = [
            ['id' => 'general', 'name' => 'Kas Organisasi (PRNU)'],
            ['id' => 'lazisnu', 'name' => 'LAZISNU Baktijaya'],
        ];

        // Filter parameters
        $selectedYear = $request->input('year', now()->year);
        $selectedType = $request->input('type', 'general');

        // Generate monthly reports for the selected year and type
        $monthsWithData = Transaction::whereYear('transaction_date', $selectedYear)
            ->where('fund_type', $selectedType)
            ->select(DB::raw('MONTH(transaction_date) as month'))
            ->distinct()
            ->pluck('month');

        $reports = [];
        $monthNames = [
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

        foreach ($monthsWithData as $monthNum) {
            if (!isset($monthNames[$monthNum]))
                continue;
            $monthName = $monthNames[$monthNum];
            $reports[] = [
                'title' => "Laporan $monthName $selectedYear",
                'subtitle' => $selectedType == 'general' ? 'Kas Organisasi' : 'LAZISNU',
                'size' => number_format(rand(10, 25) / 10, 1) . ' MB', // Mock size
                'month' => $monthNum,
                'year' => (int) $selectedYear,
                'fund_type' => $selectedType,
                'download_url' => url("/api/kas/download-report?title=Laporan_{$monthName}_{$selectedYear}&type={$selectedType}"),
            ];
        }

        // Add annual report if months exist
        if (count($reports) > 0) {
            $reports[] = [
                'title' => "Laporan Tahunan $selectedYear",
                'subtitle' => $selectedType == 'general' ? 'Kas Organisasi' : 'LAZISNU',
                'size' => number_format(rand(40, 60) / 10, 1) . ' MB', // Mock size
                'month' => 'annual',
                'year' => (int) $selectedYear,
                'fund_type' => $selectedType,
                'download_url' => url("/api/kas/download-report?title=Laporan_Tahunan_{$selectedYear}&type={$selectedType}"),
            ];
        }

        return response()->json([
            'success' => true,
            'data' => [
                'filters' => [
                    'years' => $years,
                    'types' => $fundTypes,
                ],
                'reports' => array_reverse($reports), // Newest first
            ]
        ]);
    }

    public function downloadReport(Request $request)
    {
        $title = $request->input('title', 'Laporan');

        // Minimal valid PDF content
        $content = "%PDF-1.4\n" .
            "1 0 obj < < /Type /Catalog /Pages 2 0 R > > endobj\n" .
            "2 0 obj < < /Type /Pages /Kids [3 0 R] /Count 1 > > endobj\n" .
            "3 0 obj < < /Type /Page /Parent 2 0 R /MediaBox [0 0 612 792] /Contents 4 0 R /Resources < < /Font < < /F1 5 0 R > > > > > > endobj\n" .
            "4 0 obj < < /Length 51 > > stream\n" .
            "BT /F1 24 Tf 100 700 Td ($title) Tj ET\n" .
            "endstream endobj\n" .
            "5 0 obj < < /Type /Font /Subtype /Type1 /BaseFont /Helvetica > > endobj\n" .
            "xref\n" .
            "0 6\n" .
            "0000000000 65535 f\n" .
            "0000000010 00000 n\n" .
            "0000000060 00000 n\n" .
            "0000000115 00000 n\n" .
            "0000000245 00000 n\n" .
            "0000000346 00000 n\n" .
            "trailer < < /Size 6 /Root 1 0 R > >\n" .
            "startxref\n" .
            "426\n" .
            "%%EOF";

        return response($content)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="' . $title . '.pdf"');
    }
}
