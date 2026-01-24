<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GalleryResource\Pages;
use App\Models\Gallery;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Forms\Set;
use Illuminate\Database\Eloquent\Builder;

class GalleryResource extends Resource
{
    protected static ?string $model = Gallery::class;

    protected static ?string $navigationIcon = 'heroicon-o-camera';

    protected static ?string $navigationGroup = 'Konten';

    protected static ?string $label = 'Galeri';
    protected static ?string $pluralLabel = 'Galeri';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn(Set $set, ?string $state) => $set('slug', Str::slug($state))),
                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                        Forms\Components\Select::make('category_id')
                            ->relationship('category', 'name', fn(Builder $query) => $query->where('type', 'gallery'))
                            ->createOptionForm([
                                Forms\Components\TextInput::make('name')->required(),
                                Forms\Components\Hidden::make('type')->default('gallery'),
                            ])
                            ->searchable()
                            ->preload(),
                        Forms\Components\Textarea::make('description')
                            ->rows(2)
                            ->columnSpanFull(),
                        Forms\Components\Select::make('type')
                            ->options([
                                'photo' => 'Foto',
                                'video' => 'Video',
                            ])
                            ->required()
                            ->default('photo')
                            ->live(),

                        // Photo Layout
                        Forms\Components\FileUpload::make('images')
                            ->visible(fn(Forms\Get $get) => $get('type') === 'photo')
                            ->image()
                            ->multiple()
                            ->reorderable()
                            ->directory('galleries')
                            ->columnSpanFull(),

                        // Video Layout
                        Forms\Components\FileUpload::make('images')
                            ->visible(fn(Forms\Get $get) => $get('type') === 'video')
                            ->label('Thumbnail Video (Opsional)')
                            ->helperText('Jika kosong, akan mengambil otomatis dari YouTube')
                            ->image()
                            ->multiple()
                            ->maxFiles(1)
                            ->directory('galleries')
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('video_url')
                            ->visible(fn(Forms\Get $get) => $get('type') === 'video')
                            ->url()
                            ->label('YouTube URL')
                            ->columnSpanFull(),

                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\DatePicker::make('event_date')
                                    ->default(now()),
                                Forms\Components\Toggle::make('is_active')
                                    ->required()
                                    ->default(true),
                                Forms\Components\Toggle::make('is_featured')
                                    ->label('Tampilkan di Homepage'),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('images')
                    ->circular()
                    ->stacked()
                    ->limit(3),
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('category.name')
                    ->badge(),
                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'photo' => 'success',
                        'video' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('event_date')
                    ->date()
                    ->sortable(),
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
                        'photo' => 'Foto',
                        'video' => 'Video',
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
            'index' => Pages\ListGalleries::route('/'),
            'create' => Pages\CreateGallery::route('/create'),
            'edit' => Pages\EditGallery::route('/{record}/edit'),
        ];
    }
}
