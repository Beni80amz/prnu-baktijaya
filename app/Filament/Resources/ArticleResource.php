<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ArticleResource\Pages;
use App\Models\Article;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Forms\Set;
use Illuminate\Database\Eloquent\Builder;

class ArticleResource extends Resource
{
    protected static ?string $model = Article::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Konten';

    protected static ?string $label = 'Artikel';
    protected static ?string $pluralLabel = 'Artikel';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make(3)
                    ->schema([
                        Forms\Components\Section::make()
                            ->columnSpan(2)
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
                                Forms\Components\Select::make('type')
                                    ->options([
                                        'opini' => 'Opini',
                                        'khutbah' => 'Khutbah Jumat',
                                        'ceramah' => 'Ceramah/Materi',
                                        'kajian' => 'Kajian Kitab',
                                    ])
                                    ->required()
                                    ->default('opini'),
                                Forms\Components\RichEditor::make('content')
                                    ->required()
                                    ->columnSpanFull()
                                    ->toolbarButtons([
                                        'attachFiles',
                                        'blockquote',
                                        'bold',
                                        'bulletList',
                                        'codeBlock',
                                        'h2',
                                        'h3',
                                        'italic',
                                        'link',
                                        'orderedList',
                                        'redo',
                                        'strike',
                                        'table', // Ensure table is here
                                        'underline',
                                        'undo',
                                    ])
                                    ->extraInputAttributes(['dir' => 'auto', 'style' => 'min-height: 300px']),
                                Forms\Components\Textarea::make('excerpt')
                                    ->rows(3)
                                    ->columnSpanFull(),
                            ]),
                        Forms\Components\Section::make()
                            ->columnSpan(1)
                            ->schema([
                                Forms\Components\FileUpload::make('featured_image')
                                    ->image()
                                    ->directory('articles'),
                                Forms\Components\TextInput::make('author_name')
                                    ->label('Penulis Eksternal')
                                    ->placeholder('Jika bukan user sistem'),
                                Forms\Components\Select::make('category_id')
                                    ->relationship('category', 'name', fn(Builder $query) => $query->where('type', 'article'))
                                    ->searchable()
                                    ->preload()
                                    ->createOptionForm([
                                        Forms\Components\TextInput::make('name')->required(),
                                        Forms\Components\Hidden::make('type')->default('article'),
                                    ]),
                                Forms\Components\Hidden::make('user_id')
                                    ->default(fn() => auth()->id()),
                                Forms\Components\Select::make('status')
                                    ->options([
                                        'draft' => 'Draft',
                                        'published' => 'Published',
                                        'archived' => 'Archived',
                                    ])
                                    ->default('draft')
                                    ->required(),
                                Forms\Components\DateTimePicker::make('published_at')
                                    ->default(now()),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('featured_image'),
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->limit(50),
                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->color('warning'),
                Tables\Columns\TextColumn::make('category.name')
                    ->badge()
                    ->color('success'),
                Tables\Columns\TextColumn::make('author_name')
                    ->label('Penulis')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'draft' => 'gray',
                        'published' => 'success',
                        'archived' => 'warning',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('published_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('views')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'opini' => 'Opini',
                        'khutbah' => 'Khutbah Jumat',
                        'ceramah' => 'Ceramah/Materi',
                        'kajian' => 'Kajian Kitab',
                    ]),
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'published' => 'Published',
                        'archived' => 'Archived',
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
            'index' => Pages\ListArticles::route('/'),
            'create' => Pages\CreateArticle::route('/create'),
            'edit' => Pages\EditArticle::route('/{record}/edit'),
        ];
    }
}
