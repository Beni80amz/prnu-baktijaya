<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DonationResource\Pages;
use App\Filament\Resources\DonationResource\RelationManagers;
use App\Models\Donation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DonationResource extends Resource
{
    protected static ?string $model = Donation::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $navigationGroup = 'Keuangan';
    protected static ?string $navigationLabel = 'Donasi Masuk';
    protected static ?string $pluralModelLabel = 'Data Donasi';
    protected static ?string $modelLabel = 'Donasi';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Donasi')
                    ->schema([
                        Forms\Components\TextInput::make('transaction_id')
                            ->label('ID Transaksi')
                            ->disabled()
                            ->required(),
                        Forms\Components\TextInput::make('created_at')
                            ->label('Tanggal')
                            ->disabled(),
                        Forms\Components\TextInput::make('campaign_name')
                            ->label('Tujuan Donasi')
                            ->columnSpanFull()
                            ->disabled(),
                        Forms\Components\TextInput::make('amount')
                            ->label('Nominal')
                            ->numeric()
                            ->prefix('Rp')
                            ->disabled(),
                        Forms\Components\TextInput::make('payment_method')
                            ->label('Metode Pembayaran')
                            ->disabled(),
                        Forms\Components\TextInput::make('bank_name')
                            ->label('Bank Tujuan')
                            ->disabled(),
                    ])->columns(2),

                Forms\Components\Section::make('Data Donatur')
                    ->schema([
                        Forms\Components\TextInput::make('donor_name')
                            ->label('Nama Donatur')
                            ->disabled(),
                        Forms\Components\TextInput::make('donor_phone')
                            ->label('No. WhatsApp')
                            ->disabled(),
                        Forms\Components\Textarea::make('donor_purpose')
                            ->label('Doa / Pesan')
                            ->columnSpanFull()
                            ->disabled(),
                        Forms\Components\Toggle::make('is_anonymous')
                            ->label('Hamba Allah (Anonim)')
                            ->disabled(),
                    ])->columns(2),

                Forms\Components\Section::make('Status Verifikasi')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->options([
                                'pending' => 'Menunggu Verifikasi',
                                'verified' => 'Terverifikasi',
                                'rejected' => 'Ditolak',
                            ])
                            ->required(),
                        Forms\Components\DateTimePicker::make('verified_at')
                            ->label('Tanggal Verifikasi')
                            ->disabled(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('transaction_id')
                    ->label('ID Transaksi')
                    ->searchable()
                    ->copyable(),
                Tables\Columns\TextColumn::make('donor_name')
                    ->label('Nama')
                    ->searchable()
                    ->description(fn(Donation $record) => $record->is_anonymous ? '(Anonim)' : null),
                Tables\Columns\TextColumn::make('campaign_name')
                    ->label('Tujuan')
                    ->limit(20)
                    ->searchable(),
                Tables\Columns\TextColumn::make('amount')
                    ->label('Nominal')
                    ->money('IDR')
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('payment_method')
                    ->label('Metode')
                    ->badge(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'pending' => 'warning',
                        'verified' => 'success',
                        'rejected' => 'danger',
                    })
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'pending' => 'Menunggu',
                        'verified' => 'Sah',
                        'rejected' => 'Ditolak',
                    }),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Menunggu Verifikasi',
                        'verified' => 'Terverifikasi',
                        'rejected' => 'Ditolak',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('verify')
                    ->label('Verifikasi')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn(Donation $record) => $record->status === 'pending')
                    ->action(fn(Donation $record) => $record->update([
                        'status' => 'verified',
                        'verified_at' => now(),
                    ])),

                Tables\Actions\Action::make('reject')
                    ->label('Tolak')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(fn(Donation $record) => $record->status === 'pending')
                    ->action(fn(Donation $record) => $record->update([
                        'status' => 'rejected',
                    ])),

                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListDonations::route('/'),
            'create' => Pages\CreateDonation::route('/create'),
            'edit' => Pages\EditDonation::route('/{record}/edit'),
        ];
    }
}
