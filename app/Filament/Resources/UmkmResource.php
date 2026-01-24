<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UmkmResource\Pages;
use App\Models\Umkm;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Forms\Set;

class UmkmResource extends Resource
{
    protected static ?string $model = Umkm::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?string $navigationGroup = 'Layanan';

    protected static ?string $label = 'UMKM Warga';
    protected static ?string $pluralLabel = 'UMKM Warga';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                    Forms\Components\Section::make('Informasi Usaha')
                        ->schema([
                                Forms\Components\TextInput::make('business_name')
                                    ->required()
                                    ->maxLength(255)
                                    ->label('Nama Usaha')
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn(Set $set, ?string $state) => $set('slug', Str::slug($state))),
                                Forms\Components\TextInput::make('slug')
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(ignoreRecord: true),
                                Forms\Components\Select::make('category')
                                    ->options([
                                            'makanan' => 'Makanan & Minuman',
                                            'pakaian' => 'Fashion & Pakaian',
                                            'kerajinan' => 'Kerajinan',
                                            'jasa' => 'Jasa',
                                            'pertanian' => 'Pertanian/Peternakan',
                                            'lainnya' => 'Lainnya',
                                        ])
                                    ->required(),
                                Forms\Components\Textarea::make('description')
                                    ->rows(3)
                                    ->columnSpanFull(),
                            ]),
                    Forms\Components\Section::make('Kontak & Lokasi')
                        ->columns(2)
                        ->schema([
                                Forms\Components\TextInput::make('owner_name')
                                    ->required()
                                    ->label('Nama Pemilik'),
                                Forms\Components\TextInput::make('phone')
                                    ->tel()
                                    ->required(),
                                Forms\Components\TextInput::make('whatsapp')
                                    ->tel()
                                    ->prefix('+62')
                                    ->placeholder('8123xxxx'),
                                Forms\Components\TextInput::make('email')
                                    ->email(),
                                Forms\Components\Textarea::make('address')
                                    ->rows(2)
                                    ->columnSpanFull(),
                            ]),
                    Forms\Components\Section::make('Media')
                        ->schema([
                                Forms\Components\FileUpload::make('featured_image')
                                    ->image()
                                    ->directory('umkm/featured')
                                    ->label('Foto Utama'),
                                Forms\Components\FileUpload::make('images')
                                    ->image()
                                    ->multiple()
                                    ->directory('umkm/gallery')
                                    ->maxFiles(5)
                                    ->label('Galeri Produk'),
                                Forms\Components\Grid::make(2)
                                    ->schema([
                                            Forms\Components\Toggle::make('is_active')
                                                ->default(true),
                                            Forms\Components\Toggle::make('is_featured')
                                                ->label('Tampilkan sbg Unggulan'),
                                        ]),
                            ]),
                ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                    Tables\Columns\ImageColumn::make('featured_image'),
                    Tables\Columns\TextColumn::make('business_name')
                        ->searchable()
                        ->sortable(),
                    Tables\Columns\TextColumn::make('owner_name')
                        ->searchable(),
                    Tables\Columns\TextColumn::make('category')
                        ->badge(),
                    Tables\Columns\TextColumn::make('phone')
                        ->toggleable(),
                    Tables\Columns\IconColumn::make('is_active')
                        ->boolean(),
                    Tables\Columns\IconColumn::make('is_featured')
                        ->boolean()
                        ->label('Unggulan'),
                    Tables\Columns\TextColumn::make('created_at')
                        ->dateTime()
                        ->sortable()
                        ->toggleable(isToggledHiddenByDefault: true),
                ])
            ->filters([
                    Tables\Filters\SelectFilter::make('category')
                        ->options([
                                'makanan' => 'Makanan & Minuman',
                                'pakaian' => 'Fashion & Pakaian',
                                'kerajinan' => 'Kerajinan',
                                'jasa' => 'Jasa',
                                'pertanian' => 'Pertanian/Peternakan',
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
            'index' => Pages\ListUmkms::route('/'),
            'create' => Pages\CreateUmkm::route('/create'),
            'edit' => Pages\EditUmkm::route('/{record}/edit'),
        ];
    }
}
