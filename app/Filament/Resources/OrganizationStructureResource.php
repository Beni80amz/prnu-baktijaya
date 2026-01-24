<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrganizationStructureResource\Pages;
use App\Filament\Resources\OrganizationStructureResource\RelationManagers;
use App\Models\OrganizationStructure;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrganizationStructureResource extends Resource
{
    protected static ?string $model = OrganizationStructure::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Lengkap')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('position')
                            ->label('Jabatan')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('type')
                            ->label('Tipe Struktur')
                            ->options([
                                'syuriyah' => 'Majelis Syuriyah',
                                'tanfidziyah' => 'Majelis Tanfidziyah',
                            ])
                            ->required(),
                        Forms\Components\Textarea::make('description')
                            ->label('Deskripsi / Profil Singkat')
                            ->columnSpanFull(),
                        Forms\Components\FileUpload::make('image')
                            ->label('Foto Profil')
                            ->image()
                            ->disk('public')
                            ->directory('organization-structures')
                            ->fetchFileInformation(false)
                            ->formatStateUsing(fn($state) => $state ? (is_array($state) ? $state : [$state]) : [])
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('order')
                            ->label('Urutan')
                            ->numeric()
                            ->default(0),
                        Forms\Components\Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('Foto')
                    ->disk('public')
                    ->circular(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('position')
                    ->label('Jabatan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('type')
                    ->label('Tipe')
                    ->badge()
                    ->colors([
                        'warning' => 'syuriyah',
                        'success' => 'tanfidziyah',
                    ]),
                Tables\Columns\TextColumn::make('order')
                    ->label('Urutan')
                    ->sortable(),
                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Aktif'),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('order', 'asc')
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'syuriyah' => 'Majelis Syuriyah',
                        'tanfidziyah' => 'Majelis Tanfidziyah',
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
            'index' => Pages\ListOrganizationStructures::route('/'),
            'create' => Pages\CreateOrganizationStructure::route('/create'),
            'edit' => Pages\EditOrganizationStructure::route('/{record}/edit'),
        ];
    }
}
