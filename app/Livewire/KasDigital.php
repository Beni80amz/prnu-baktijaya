<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class KasDigital extends Component
{
    public string $period = 'month'; // month, year, all

    public function render()
    {
        // Get total balance
        $totalIncome = Transaction::where('type', 'income')->sum('amount');
        $totalExpense = Transaction::where('type', 'expense')->sum('amount');
        $balance = $totalIncome - $totalExpense;

        // Get transactions based on period
        $query = Transaction::with(['incomeType', 'expenseType'])->latest('transaction_date');

        if ($this->period === 'month') {
            $query->whereMonth('transaction_date', Carbon::now()->month)
                ->whereYear('transaction_date', Carbon::now()->year);
        } elseif ($this->period === 'year') {
            $query->whereYear('transaction_date', Carbon::now()->year);
        }

        // Get category summary
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

        return view('livewire.kas-digital', [
            'balance' => $balance,
            'totalIncome' => $totalIncome,
            'totalExpense' => $totalExpense,
            'transactions' => $query->paginate(20),
            'incomeSummary' => $incomeSummary,
            'expenseSummary' => $expenseSummary,
        ]);
    }
}
