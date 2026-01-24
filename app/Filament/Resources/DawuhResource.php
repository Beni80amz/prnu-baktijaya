<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DawuhResource\Pages;
use App\Models\Dawuh;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class DawuhResource extends Resource
{
    protected static ?string $model = Dawuh::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-bottom-center-text';

    protected static ?string $navigationGroup = 'Konten';

    protected static ?string $pluralLabel = 'Dawuh Ulama';
    protected static ?string $label = 'Dawuh Ulama';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                    Forms\Components\Section::make()
                        ->schema([
                                Forms\Components\Textarea::make('quote')
                                    ->label('Kutipan (Indonesia)')
                                    ->required()
                                    ->rows(3)
                                    ->columnSpanFull(),
                                Forms\Components\Textarea::make('quote_arabic')
                                    ->label('Kutipan (Arab)')
                                    ->rows(3)
                                    ->columnSpanFull(),
                                Forms\Components\Grid::make(3)
                                    ->schema([
                                            Forms\Components\TextInput::make('ulama_title')
                                                ->label('Gelar (KH, Habib, dll)')
                                                ->placeholder('KH.'),
                                            Forms\Components\TextInput::make('ulama_name')
                                                ->label('Nama Ulama')
                                                ->required(),
                                            Forms\Components\TextInput::make('source')
                                                ->label('Sumber/Kitab'),
                                        ]),
                                Forms\Components\DatePicker::make('display_date')
                                    ->label('Tampilkan Pada Tanggal'),
                                Forms\Components\Toggle::make('is_active')
                                    ->required()
                                    ->default(true),
                            ]),
                ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                    Tables\Columns\TextColumn::make('quote')
                        ->limit(50)
                        ->searchable(),
                    Tables\Columns\TextColumn::make('ulama_name')
                        ->formatStateUsing(fn($record) => $record->ulama_title . ' ' . $record->ulama_name)
                        ->searchable(),
                    Tables\Columns\TextColumn::make('source')
                        ->searchable(),
                    Tables\Columns\IconColumn::make('is_active')
                        ->boolean(),
                    Tables\Columns\TextColumn::make('created_at')
                        ->dateTime()
                        ->sortable()
                        ->toggleable(isToggledHiddenByDefault: true),
                ])
            ->filters([
                    //
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
            'index' => Pages\ListDawuhs::route('/'),
            'create' => Pages\CreateDawuh::route('/create'),
            'edit' => Pages\EditDawuh::route('/{record}/edit'),
        ];
    }
}
