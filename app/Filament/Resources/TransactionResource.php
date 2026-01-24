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

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $navigationGroup = 'Keuangan';

    protected static ?string $label = 'Transaksi Keuangan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                    Forms\Components\Section::make()
                        ->schema([
                                Forms\Components\Grid::make(3)
                                    ->schema([
                                            Forms\Components\Select::make('type')
                                                ->options([
                                                        'income' => 'Pemasukan',
                                                        'expense' => 'Pengeluaran',
                                                    ])
                                                ->required()
                                                ->live(),
                                            Forms\Components\Select::make('category')
                                                ->options([
                                                        'dakwah' => 'Dakwah',
                                                        'yatim' => 'Yatim & Dhuafa',
                                                        'operasional' => 'Operasional',
                                                        'infaq' => 'Infaq',
                                                        'zakat' => 'Zakat',
                                                        'qurban' => 'Qurban',
                                                        'lainnya' => 'Lainnya',
                                                    ])
                                                ->required(),
                                            Forms\Components\DatePicker::make('transaction_date')
                                                ->required()
                                                ->default(now()),
                                        ]),
                                Forms\Components\TextInput::make('amount')
                                    ->required()
                                    ->numeric()
                                    ->prefix('Rp'),
                                Forms\Components\TextInput::make('description')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\Textarea::make('notes')
                                    ->rows(2)
                                    ->columnSpanFull(),
                                Forms\Components\FileUpload::make('proof_image')
                                    ->image()
                                    ->directory('transactions')
                                    ->columnSpanFull(),
                                Forms\Components\Hidden::make('user_id')
                                    ->default(fn() => auth()->id()),
                                Forms\Components\Toggle::make('is_verified')
                                    ->label('Terverifikasi')
                                    ->default(false)
                                    ->disabled(fn() => !auth()->user()->hasRole(['super_admin', 'admin_bendahara'])),
                            ]),
                ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                    Tables\Columns\TextColumn::make('transaction_date')
                        ->date('d M Y')
                        ->sortable(),
                    Tables\Columns\TextColumn::make('type')
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
                    Tables\Columns\TextColumn::make('category')
                        ->formatStateUsing(fn(string $state): string => ucwords($state))
                        ->searchable(),
                    Tables\Columns\TextColumn::make('description')
                        ->searchable()
                        ->limit(30),
                    Tables\Columns\TextColumn::make('amount')
                        ->money('IDR')
                        ->sortable()
                        ->alignment('right'),
                    Tables\Columns\IconColumn::make('is_verified')
                        ->boolean(),
                    Tables\Columns\TextColumn::make('user.name')
                        ->label('Input Oleh')
                        ->toggleable(isToggledHiddenByDefault: true),
                    Tables\Columns\TextColumn::make('created_at')
                        ->dateTime()
                        ->sortable()
                        ->toggleable(isToggledHiddenByDefault: true),
                ])
            ->filters([
                    Tables\Filters\SelectFilter::make('type')
                        ->options([
                                'income' => 'Pemasukan',
                                'expense' => 'Pengeluaran',
                            ]),
                    Tables\Filters\SelectFilter::make('category')
                        ->options([
                                'dakwah' => 'Dakwah',
                                'yatim' => 'Yatim & Dhuafa',
                                'operasional' => 'Operasional',
                                'infaq' => 'Infaq',
                                'zakat' => 'Zakat',
                                'qurban' => 'Qurban',
                                'lainnya' => 'Lainnya',
                            ]),
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
