<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class RecentIncomeTable extends BaseWidget
{
    protected static ?string $heading = 'Pemasukan Terbaru (1 Bulan Terakhir)';
    protected int|string|array $columnSpan = 'full';
    protected static ?int $sort = 3;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Transaction::query()
                    ->where('type', 'income')
                    ->where('transaction_date', '>=', now()->subMonth())
                    ->latest('transaction_date')
            )
            ->columns([
                Tables\Columns\TextColumn::make('transaction_date')
                    ->label('Tanggal')
                    ->date('d F Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('incomeType.name')
                    ->label('Jenis Pemasukan')
                    ->badge()
                    ->color('success')
                    ->searchable(),
                Tables\Columns\TextColumn::make('amount')
                    ->label('Jumlah')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('description')
                    ->label('Keterangan')
                    ->limit(50),
            ])
            ->paginated([5, 10, 25]);
    }
}
