<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MunfiqunResource\Pages;
use App\Filament\Resources\MunfiqunResource\RelationManagers;
use App\Models\Munfiqun;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MunfiqunResource extends Resource
{
    protected static ?string $model = Munfiqun::class;

    protected static ?string $navigationGroup = 'Master Data';

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('code')
                    ->disabled()
                    ->dehydrated()
                    ->placeholder('Otomatis (Kode Wilayah + Urutan)')
                    ->maxLength(255),
                Forms\Components\Select::make('volunteer_id')
                    ->relationship('volunteer', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('volunteer.name')
                    ->label('Relawan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('volunteer.region.name')
                    ->label('Wilayah')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListMunfiquns::route('/'),
            'create' => Pages\CreateMunfiqun::route('/create'),
            'edit' => Pages\EditMunfiqun::route('/{record}/edit'),
        ];
    }
}
