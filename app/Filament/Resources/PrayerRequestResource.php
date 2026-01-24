<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PrayerRequestResource\Pages;
use App\Models\PrayerRequest;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PrayerRequestResource extends Resource
{
    protected static ?string $model = PrayerRequest::class;

    protected static ?string $navigationIcon = 'heroicon-o-heart';

    protected static ?string $navigationGroup = 'Layanan';

    protected static ?string $label = 'Permohonan Doa';
    protected static ?string $pluralLabel = 'Permohonan Doa';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                    Forms\Components\Section::make('Pemohon')
                        ->columns(3)
                        ->schema([
                                Forms\Components\TextInput::make('requester_name')
                                    ->required()
                                    ->label('Nama Pemohon'),
                                Forms\Components\TextInput::make('requester_phone')
                                    ->tel(),
                                Forms\Components\TextInput::make('requester_email')
                                    ->email(),
                            ]),
                    Forms\Components\Section::make('Detail Doa')
                        ->schema([
                                Forms\Components\Select::make('type')
                                    ->options([
                                            'tahlil' => 'Tahlil',
                                            'yasin' => 'Yasin',
                                            'istighotsah' => 'Istighotsah',
                                            'doa_umum' => 'Doa Umum',
                                        ])
                                    ->required()
                                    ->default('doa_umum'),
                                Forms\Components\TagsInput::make('names')
                                    ->label('Nama-nama yang didoakan (Alm/Almh)')
                                    ->placeholder('Tambahkan nama...')
                                    ->required(),
                                Forms\Components\Textarea::make('notes')
                                    ->label('Catatan Tambahan'),
                                Forms\Components\DatePicker::make('requested_date')
                                    ->label('Tanggal Pelaksanaan (Opsional)'),
                            ]),
                    Forms\Components\Section::make('Status')
                        ->schema([
                                Forms\Components\Select::make('status')
                                    ->options([
                                            'pending' => 'Menunggu',
                                            'scheduled' => 'Dijadwalkan',
                                            'completed' => 'Selesai',
                                        ])
                                    ->required()
                                    ->default('pending'),
                            ]),
                ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                    Tables\Columns\TextColumn::make('requester_name')
                        ->searchable()
                        ->label('Pemohon'),
                    Tables\Columns\TextColumn::make('type')
                        ->badge()
                        ->formatStateUsing(fn(string $state): string => ucfirst(str_replace('_', ' ', $state))),
                    Tables\Columns\TextColumn::make('names')
                        ->limit(50),
                    Tables\Columns\TextColumn::make('requested_date')
                        ->date(),
                    Tables\Columns\TextColumn::make('status')
                        ->badge()
                        ->color(fn(string $state): string => match ($state) {
                            'pending' => 'gray',
                            'scheduled' => 'warning',
                            'completed' => 'success',
                            default => 'gray',
                        }),
                    Tables\Columns\TextColumn::make('created_at')
                        ->dateTime()
                        ->sortable()
                        ->toggleable(isToggledHiddenByDefault: true),
                ])
            ->filters([
                    Tables\Filters\SelectFilter::make('status')
                        ->options([
                                'pending' => 'Menunggu',
                                'scheduled' => 'Dijadwalkan',
                                'completed' => 'Selesai',
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
            'index' => Pages\ListPrayerRequests::route('/'),
            'create' => Pages\CreatePrayerRequest::route('/create'),
            'edit' => Pages\EditPrayerRequest::route('/{record}/edit'),
        ];
    }
}
