<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class KasDigital extends Component
{
    use WithPagination;

    public string $period = 'month'; // month, year, all
    public string $search = '';
    public string $categoryFilter = ''; // format: "type:id" e.g., "income:1" or "expense:5" or "" for all
    public int $perPage = 20;

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedCategoryFilter()
    {
        $this->resetPage();
    }

    public function showAll()
    {
        $this->perPage = 9999;
        $this->resetPage();
    }

    public function render()
    {
        // Get total balance (Always global calculation for summary cards)
        $totalIncome = Transaction::where('type', 'income')->sum('amount');
        $totalExpense = Transaction::where('type', 'expense')->sum('amount');
        $balance = $totalIncome - $totalExpense;

        // Get transactions query
        $query = Transaction::with(['incomeType', 'expenseType'])->latest('transaction_date');

        // Apply Period Filter
        if ($this->period === 'month') {
            $query->whereMonth('transaction_date', Carbon::now()->month)
                ->whereYear('transaction_date', Carbon::now()->year);
        } elseif ($this->period === 'year') {
            $query->whereYear('transaction_date', Carbon::now()->year);
        }

        // Apply Search Filter
        if ($this->search) {
            $query->where('description', 'like', '%' . $this->search . '%');
        }

        // Apply Category Filter
        if ($this->categoryFilter) {
            $parts = explode(':', $this->categoryFilter);
            if (count($parts) === 2) {
                $type = $parts[0]; // 'income' or 'expense'
                $id = $parts[1];

                $query->where('type', $type);
                if ($type === 'income') {
                    $query->where('income_type_id', $id);
                } else {
                    $query->where('expense_type_id', $id);
                }
            }
        }

        // Get category summary (Always global for the sidebar summary)
        $incomeSummary = Transaction::join('income_types', 'transactions.income_type_id', '=', 'income_types.id')
            ->where('transactions.type', 'income')
            ->select('income_types.name as category', DB::raw('SUM(transactions.amount) as total'))
            ->groupBy('income_types.name')
            ->get();

        $expenseSummary = Transaction::join('expense_types', 'transactions.expense_type_id', '=', 'expense_types.id')
            ->where('transactions.type', 'expense')
            ->select('expense_types.name as category', DB::raw('SUM(transactions.amount) as total'))
            ->groupBy('expense_types.name')
            ->get();

        // For Filter Dropdown
        $incomeTypes = \App\Models\IncomeType::all();
        $expenseTypes = \App\Models\ExpenseType::all();

        return view('livewire.kas-digital', [
            'balance' => $balance,
            'totalIncome' => $totalIncome,
            'totalExpense' => $totalExpense,
            'transactions' => $query->paginate($this->perPage),
            'incomeSummary' => $incomeSummary,
            'expenseSummary' => $expenseSummary,
            'incomeTypes' => $incomeTypes,
            'expenseTypes' => $expenseTypes,
            'monthlyStats' => $this->getMonthlyStats(),
            'allocationStats' => $this->getAllocationStats(),
            'recentDonors' => $this->getRecentDonors(),
            'mostActiveCategory' => $this->getMostActiveCategory(),
        ]);
    }

    private function getMonthlyStats()
    {
        $stats = [];
        $now = Carbon::now();

        // Find max value for scaling
        $maxVal = 0;

        for ($i = 5; $i >= 0; $i--) {
            $date = $now->copy()->subMonths($i);
            $monthName = $date->format('M');

            $income = Transaction::where('type', 'income')
                ->whereYear('transaction_date', $date->year)
                ->whereMonth('transaction_date', $date->month)
                ->sum('amount');

            $expense = Transaction::where('type', 'expense')
                ->whereYear('transaction_date', $date->year)
                ->whereMonth('transaction_date', $date->month)
                ->sum('amount');

            $maxVal = max($maxVal, $income, $expense);

            $stats[] = [
                'month' => $monthName,
                'income' => $income,
                'expense' => $expense,
            ];
        }

        // Add percentages for UI
        foreach ($stats as &$stat) {
            $stat['income_pct'] = $maxVal > 0 ? ($stat['income'] / $maxVal) * 100 : 0;
            $stat['expense_pct'] = $maxVal > 0 ? ($stat['expense'] / $maxVal) * 100 : 0;
        }

        return $stats;
    }

    private function getAllocationStats()
    {
        // Get expenses for the current year
        $expenses = Transaction::with('expenseType')
            ->where('type', 'expense')
            ->whereYear('transaction_date', Carbon::now()->year)
            ->get();

        $total = $expenses->sum('amount');
        if ($total == 0)
            return [];

        $colors = ['bg-primary', 'bg-accent', 'bg-teal-600', 'bg-gray-400', 'bg-red-400'];
        $textColors = ['text-primary', 'text-accent', 'text-teal-600', 'text-gray-400', 'text-red-400'];

        return $expenses->groupBy('expense_type_id')
            ->map(function ($group, $key) use ($total, $colors, $textColors) {
                $categoryName = $group->first()->expenseType->name ?? 'Lainnya';
                $amount = $group->sum('amount');
                $percentage = round(($amount / $total) * 100);

                // Deterministic color assignment based on ID? Or index?
                // Just random for now since index is tricky in map
                $colorIndex = $key % count($colors);

                return [
                    'name' => $categoryName,
                    'percentage' => $percentage,
                    'color_class' => $colors[$colorIndex],
                    'text_class' => $textColors[$colorIndex]
                ];
            })->sortByDesc('percentage')->take(4)->values();
    }

    private function getRecentDonors()
    {
        return Transaction::where('type', 'income')
            ->latest('created_at')
            ->take(3)
            ->get();
    }

    private function getMostActiveCategory()
    {
        // Find the category (income or expense) with the most transaction count this month
        $income = Transaction::where('type', 'income')
            ->whereMonth('transaction_date', Carbon::now()->month)
            ->select('income_type_id', DB::raw('count(*) as total'), DB::raw('sum(amount) as amount_sum'))
            ->groupBy('income_type_id')
            ->orderByDesc('total')
            ->with('incomeType')
            ->first();

        // Calculate total stats for percentage calculation if needed
        $totalFlow = Transaction::whereMonth('transaction_date', Carbon::now()->month)->sum('amount');

        if ($income) {
            return [
                'name' => $income->incomeType->name ?? 'Umum',
                'type' => 'Pemasukan',
                'count' => $income->total,
                'percentage' => $totalFlow > 0 ? round(($income->amount_sum / $totalFlow) * 100) : 0,
                'description' => 'Kontribusi terbesar bulan ini'
            ];
        }

        return null;
    }

    public function exportExcel()
    {
        $fileName = 'Laporan Keuangan PRNU Baktijaya ' . now()->setTimezone('Asia/Jakarta')->format('d-m-Y H.i') . '.csv';

        // Prepare Query with same filters as render (duplicated logic for simplicity, could be refactored)
        $query = Transaction::with(['incomeType', 'expenseType'])->latest('transaction_date');

        if ($this->period === 'month') {
            $query->whereMonth('transaction_date', Carbon::now()->month)
                ->whereYear('transaction_date', Carbon::now()->year);
        } elseif ($this->period === 'year') {
            $query->whereYear('transaction_date', Carbon::now()->year);
        }

        if ($this->search) {
            $query->where('description', 'like', '%' . $this->search . '%');
        }

        if ($this->categoryFilter) {
            $parts = explode(':', $this->categoryFilter);
            if (count($parts) === 2) {
                $type = $parts[0];
                $id = $parts[1];
                $query->where('type', $type);
                if ($type === 'income') {
                    $query->where('income_type_id', $id);
                } else {
                    $query->where('expense_type_id', $id);
                }
            }
        }

        $transactions = $query->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$fileName\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($transactions) {
            $file = fopen('php://output', 'w');

            // BOM for Excel UTF-8
            fputs($file, "\xEF\xBB\xBF");

            fputcsv($file, ['No', 'Tanggal', 'Tipe', 'Kategori', 'Keterangan', 'Jumlah (Rp)']);

            foreach ($transactions as $index => $tx) {
                $category = $tx->type === 'income' ? ($tx->incomeType->name ?? '-') : ($tx->expenseType->name ?? '-');
                $amount = $tx->type === 'income' ? $tx->amount : -$tx->amount;

                fputcsv($file, [
                    $index + 1,
                    $tx->transaction_date->format('Y-m-d'),
                    ucfirst($tx->type),
                    $category,
                    $tx->description,
                    $amount
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
