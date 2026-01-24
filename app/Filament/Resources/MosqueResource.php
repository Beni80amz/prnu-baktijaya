<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MosqueResource\Pages;
use App\Models\Mosque;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Forms\Set;

class MosqueResource extends Resource
{
    protected static ?string $model = Mosque::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';

    protected static ?string $navigationGroup = 'Layanan';

    protected static ?string $label = 'Peta Masjid';
    protected static ?string $pluralLabel = 'Data Masjid/Musholla';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                    Forms\Components\Section::make('Identitas Masjid')
                        ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->maxLength(255)
                                    ->label('Nama Masjid/Musholla')
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn(Set $set, ?string $state) => $set('slug', Str::slug($state))),
                                Forms\Components\TextInput::make('slug')
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(ignoreRecord: true),
                                Forms\Components\Select::make('type')
                                    ->options([
                                            'masjid' => 'Masjid Jami',
                                            'musholla' => 'Musholla',
                                            'langgar' => 'Langgar',
                                        ])
                                    ->required()
                                    ->default('masjid'),
                                Forms\Components\TextInput::make('capacity')
                                    ->numeric()
                                    ->label('Kapasitas Jamaah'),
                            ]),
                    Forms\Components\Section::make('Lokasi & Kontak')
                        ->columns(2)
                        ->schema([
                                Forms\Components\TextInput::make('takmir_name')
                                    ->label('Ketua Takmir/Kontak'),
                                Forms\Components\TextInput::make('phone')
                                    ->tel(),
                                Forms\Components\Textarea::make('address')
                                    ->required()
                                    ->columnSpanFull(),
                                Forms\Components\TextInput::make('village')
                                    ->label('Kelurahan/Desa'),
                                Forms\Components\Grid::make(2)
                                    ->columnSpanFull()
                                    ->schema([
                                            Forms\Components\TextInput::make('latitude')
                                                ->numeric(),
                                            Forms\Components\TextInput::make('longitude')
                                                ->numeric(),
                                        ]),
                            ]),
                    Forms\Components\Section::make('Fasilitas & Foto')
                        ->schema([
                                Forms\Components\Grid::make(2)
                                    ->schema([
                                            Forms\Components\Toggle::make('has_wudu_facility')
                                                ->label('Ada Tempat Wudhu')
                                                ->default(true),
                                            Forms\Components\Toggle::make('has_parking')
                                                ->label('Ada Parkir')
                                                ->default(false),
                                        ]),
                                Forms\Components\FileUpload::make('image')
                                    ->image()
                                    ->directory('mosques')
                                    ->label('Foto Depan'),
                                Forms\Components\Textarea::make('description')
                                    ->label('Deskripsi Singkat')
                                    ->rows(2),
                                Forms\Components\Toggle::make('is_active')
                                    ->default(true),
                            ]),
                ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                    Tables\Columns\ImageColumn::make('image'),
                    Tables\Columns\TextColumn::make('name')
                        ->searchable()
                        ->sortable(),
                    Tables\Columns\TextColumn::make('type')
                        ->badge()
                        ->color(fn(string $state): string => match ($state) {
                            'masjid' => 'success',
                            'musholla' => 'info',
                            'langgar' => 'warning',
                            default => 'gray',
                        }),
                    Tables\Columns\TextColumn::make('takmir_name')
                        ->label('Takmir')
                        ->searchable(),
                    Tables\Columns\TextColumn::make('village')
                        ->label('Wilayah'),
                    Tables\Columns\IconColumn::make('is_active')
                        ->boolean(),
                    Tables\Columns\TextColumn::make('created_at')
                        ->dateTime()
                        ->sortable()
                        ->toggleable(isToggledHiddenByDefault: true),
                ])
            ->filters([
                    Tables\Filters\SelectFilter::make('type')
                        ->options([
                                'masjid' => 'Masjid Jami',
                                'musholla' => 'Musholla',
                                'langgar' => 'Langgar',
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
            'index' => Pages\ListMosques::route('/'),
            'create' => Pages\CreateMosque::route('/create'),
            'edit' => Pages\EditMosque::route('/{record}/edit'),
        ];
    }
}
