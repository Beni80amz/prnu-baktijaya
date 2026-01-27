<?php

namespace App\Filament\Resources\TransactionResource\Pages;

use App\Filament\Resources\TransactionResource;
use App\Models\Transaction;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Response;

class ListTransactions extends ListRecords
{
    protected static string $resource = TransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('exportExcel')
                ->label('Export Excel')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success')
                ->action(function () {
                    return $this->exportExcel();
                }),
            Actions\Action::make('exportPdf')
                ->label('Export PDF')
                ->icon('heroicon-o-document-text')
                ->color('gray')
                ->url(function () {
                    $filters = $this->tableFilters;
                    $params = [];

                    if (!empty($filters['type']['value'])) {
                        $params['type'] = $filters['type']['value'];
                    }
                    if (!empty($filters['region']['value'])) {
                        $params['region'] = $filters['region']['value'];
                    }

                    return route('admin.transactions.print', $params);
                })
                ->openUrlInNewTab(),
            Actions\CreateAction::make(),
        ];
    }

    public function exportExcel()
    {
        // Get active filters
        $filters = $this->tableFilters;
        $typeFilter = $filters['type']['value'] ?? null;
        $regionFilter = $filters['region']['value'] ?? null;

        $query = Transaction::with(['incomeType', 'expenseType'])
            ->latest('transaction_date');

        // Apply filters
        if ($typeFilter) {
            $query->where('type', $typeFilter);
        }
        if ($regionFilter) {
            $query->where('region_id', $regionFilter);
        }

        $transactions = $query->get();

        // Generate filename with filter info
        $filterLabel = '';
        if ($typeFilter === 'income') {
            $filterLabel = ' - Pemasukan';
        } elseif ($typeFilter === 'expense') {
            $filterLabel = ' - Pengeluaran';
        }

        $fileName = 'Laporan Keuangan PRNU Baktijaya' . $filterLabel . ' ' . now()->setTimezone('Asia/Jakarta')->format('d-m-Y H.i') . '.csv';

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
                    ucfirst($tx->type === 'income' ? 'Pemasukan' : 'Pengeluaran'),
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

