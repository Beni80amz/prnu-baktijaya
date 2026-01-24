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
        $query = Transaction::latest('transaction_date');

        if ($this->period === 'month') {
            $query->whereMonth('transaction_date', Carbon::now()->month)
                ->whereYear('transaction_date', Carbon::now()->year);
        } elseif ($this->period === 'year') {
            $query->whereYear('transaction_date', Carbon::now()->year);
        }

        // Get category summary
        $incomeSummary = Transaction::where('type', 'income')
            ->select('category', DB::raw('SUM(amount) as total'))
            ->groupBy('category')
            ->get();

        $expenseSummary = Transaction::where('type', 'expense')
            ->select('category', DB::raw('SUM(amount) as total'))
            ->groupBy('category')
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
