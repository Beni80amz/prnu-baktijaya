<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class IncomeExpenseChart extends ChartWidget
{
    protected static ?string $heading = 'Pemasukan vs Pengeluaran (3 Bulan Terakhir)';
    protected static string $color = 'info';
    protected static ?string $pollingInterval = '30s';

    protected function getData(): array
    {
        $start = now()->startOfMonth()->subMonths(2);
        $end = now()->endOfMonth();

        // Since Flowframe/Trend might not be installed (it's common but not in composer.json),
        // I'll use a manual query to be safe.

        $months = [];
        $incomeData = [];
        $expenseData = [];

        for ($i = 2; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthName = $date->translatedFormat('F');
            $months[] = $monthName;

            $income = Transaction::where('type', 'income')
                ->whereMonth('transaction_date', $date->month)
                ->whereYear('transaction_date', $date->year)
                ->sum('amount');

            $expense = Transaction::where('type', 'expense')
                ->whereMonth('transaction_date', $date->month)
                ->whereYear('transaction_date', $date->year)
                ->sum('amount');

            $incomeData[] = $income;
            $expenseData[] = $expense;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Pemasukan',
                    'data' => $incomeData,
                    'backgroundColor' => '#34d399', // success
                    'borderColor' => '#10b981',
                ],
                [
                    'label' => 'Pengeluaran',
                    'data' => $expenseData,
                    'backgroundColor' => '#f87171', // danger
                    'borderColor' => '#ef4444',
                ],
            ],
            'labels' => $months,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
