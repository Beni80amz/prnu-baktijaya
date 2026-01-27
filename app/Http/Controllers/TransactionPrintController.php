<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionPrintController extends Controller
{
    public function print(Request $request)
    {
        // Get filter parameters
        $typeFilter = $request->input('type');
        $regionFilter = $request->input('region');

        // Build query with filters
        $transactionQuery = Transaction::with(['incomeType', 'expenseType'])
            ->latest('transaction_date');

        if ($typeFilter) {
            $transactionQuery->where('type', $typeFilter);
        }
        if ($regionFilter) {
            $transactionQuery->where('region_id', $regionFilter);
        }

        $transactions = $transactionQuery->get();

        // Calculate totals based on filtered data or global if no filter
        if ($typeFilter) {
            // If filtered, show only the filtered type totals
            $totalIncome = $typeFilter === 'income' ? $transactions->sum('amount') : 0;
            $totalExpense = $typeFilter === 'expense' ? $transactions->sum('amount') : 0;
        } else {
            // Global totals
            $totalIncome = Transaction::where('type', 'income')->sum('amount');
            $totalExpense = Transaction::where('type', 'expense')->sum('amount');
        }
        $balance = $totalIncome - $totalExpense;

        // Get category summary based on filter
        $incomeSummary = collect();
        $expenseSummary = collect();

        if (!$typeFilter || $typeFilter === 'income') {
            $incomeQuery = Transaction::join('income_types', 'transactions.income_type_id', '=', 'income_types.id')
                ->where('transactions.type', 'income')
                ->select('income_types.name as category', DB::raw('SUM(transactions.amount) as total'))
                ->groupBy('income_types.name');

            if ($regionFilter) {
                $incomeQuery->where('region_id', $regionFilter);
            }

            $incomeSummary = $incomeQuery->get();
        }

        if (!$typeFilter || $typeFilter === 'expense') {
            $expenseQuery = Transaction::join('expense_types', 'transactions.expense_type_id', '=', 'expense_types.id')
                ->where('transactions.type', 'expense')
                ->select('expense_types.name as category', DB::raw('SUM(transactions.amount) as total'))
                ->groupBy('expense_types.name');

            if ($regionFilter) {
                $expenseQuery->where('region_id', $regionFilter);
            }

            $expenseSummary = $expenseQuery->get();
        }

        // Pass filter info to view
        $filterLabel = null;
        if ($typeFilter === 'income') {
            $filterLabel = 'Pemasukan';
        } elseif ($typeFilter === 'expense') {
            $filterLabel = 'Pengeluaran';
        }

        return view('admin.transactions.print', compact(
            'balance',
            'totalIncome',
            'totalExpense',
            'transactions',
            'incomeSummary',
            'expenseSummary',
            'filterLabel',
            'typeFilter'
        ));
    }
}

