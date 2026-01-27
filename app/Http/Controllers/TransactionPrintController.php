<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class TransactionPrintController extends Controller
{
    public function print()
    {
        // Get total balance
        $totalIncome = Transaction::where('type', 'income')->sum('amount');
        $totalExpense = Transaction::where('type', 'expense')->sum('amount');
        $balance = $totalIncome - $totalExpense;

        // Get all transactions
        $transactions = Transaction::with(['incomeType', 'expenseType'])
            ->latest('transaction_date')
            ->get();

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

        return view('admin.transactions.print', compact(
            'balance',
            'totalIncome',
            'totalExpense',
            'transactions',
            'incomeSummary',
            'expenseSummary'
        ));
    }
}
