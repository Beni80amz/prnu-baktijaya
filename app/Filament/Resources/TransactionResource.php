<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransactionResource\Pages;
use App\Models\Transaction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-wallet';

    protected static ?string $navigationGroup = 'Keuangan';
    protected static ?int $navigationSort = 2;

    protected static ?string $label = 'Transaksi Keuangan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('type')
                                    ->label('Tipe Transaksi')
                                    ->options([
                                        'income' => 'Pemasukan',
                                        'expense' => 'Pengeluaran',
                                    ])
                                    ->required()
                                    ->live()
                                    ->afterStateUpdated(fn(Forms\Set $set) => $set('income_type_id', null) && $set('expense_type_id', null)),
                                Forms\Components\DatePicker::make('transaction_date')
                                    ->label('Tanggal')
                                    ->required()
                                    ->default(now()),
                            ]),
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('income_type_id')
                                    ->label('Jenis Pemasukan')
                                    ->relationship('incomeType', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required(fn(Forms\Get $get) => $get('type') === 'income')
                                    ->visible(fn(Forms\Get $get) => $get('type') === 'income'),
                                Forms\Components\Select::make('expense_type_id')
                                    ->label('Jenis Pengeluaran')
                                    ->relationship('expenseType', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required(fn(Forms\Get $get) => $get('type') === 'expense')
                                    ->visible(fn(Forms\Get $get) => $get('type') === 'expense'),
                            ]),
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('region_id')
                                    ->label('Wilayah')
                                    ->relationship('region', 'name')
                                    ->searchable()
                                    ->preload(),
                                Forms\Components\Select::make('volunteer_id')
                                    ->label('Relawan')
                                    ->relationship('volunteer', 'name')
                                    ->searchable()
                                    ->preload(),
                            ]),
                        Forms\Components\TextInput::make('amount')
                            ->label('Nominal')
                            ->required()
                            ->numeric()
                            ->inputMode('decimal')
                            ->step(0.01)
                            ->prefix('Rp'),
                        Forms\Components\Textarea::make('description')
                            ->label('Keterangan')
                            ->nullable()
                            ->columnSpanFull(),
                        Forms\Components\FileUpload::make('proof_image')
                            ->label('Bukti Transaksi')
                            ->image()
                            ->directory('transactions')
                            ->columnSpanFull(),
                        Forms\Components\Hidden::make('user_id')
                            ->default(fn() => auth()->id()),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('transaction_date')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->label('Tipe')
                    ->badge()
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'income' => 'Pemasukan',
                        'expense' => 'Pengeluaran',
                        default => $state,
                    })
                    ->color(fn(string $state): string => match ($state) {
                        'income' => 'success',
                        'expense' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('category_name')
                    ->label('Kategori')
                    ->state(function (Transaction $record): string {
                        return $record->type === 'income'
                            ? ($record->incomeType?->name ?? '-')
                            : ($record->expenseType?->name ?? '-');
                    }),
                Tables\Columns\TextColumn::make('amount')
                    ->label('Nominal')
                    ->money('IDR')
                    ->sortable()
                    ->alignment('right'),
                Tables\Columns\TextColumn::make('region.name')
                    ->label('Wilayah')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('volunteer.name')
                    ->label('Relawan')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('description')
                    ->label('Keterangan')
                    ->limit(30)
                    ->searchable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'income' => 'Pemasukan',
                        'expense' => 'Pengeluaran',
                    ]),
                Tables\Filters\SelectFilter::make('region')
                    ->relationship('region', 'name'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTransactions::route('/'),
            'create' => Pages\CreateTransaction::route('/create'),
            'edit' => Pages\EditTransaction::route('/{record}/edit'),
        ];
    }
}
