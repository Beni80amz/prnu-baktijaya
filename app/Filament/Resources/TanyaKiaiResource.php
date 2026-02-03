<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TanyaKiaiResource\Pages;
use App\Models\TanyaKiai;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class TanyaKiaiResource extends Resource
{
    protected static ?string $model = TanyaKiai::class;

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        if (auth()->user()->hasRole('kiai')) {
            $query->whereHas('kiai', function ($q) {
                $q->where('user_id', auth()->id());
            });
        }

        return $query;
    }

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    protected static ?string $navigationGroup = 'Layanan';

    protected static ?string $label = 'Tanya Kiai';
    protected static ?string $pluralLabel = 'Tanya Kiai';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Identitas Penanya')
                    ->columns(3)
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('phone')
                            ->tel()
                            ->maxLength(255),
                    ]),
                Forms\Components\Section::make('Tujuan Pertanyaan')
                    ->schema([
                        Forms\Components\Select::make('kiai_id')
                            ->relationship('kiai', 'name')
                            ->label('Pilih Kiai')
                            ->searchable()
                            ->preload()
                            ->required(),
                    ]),
                Forms\Components\Section::make('Pertanyaan & Jawaban')
                    ->schema([
                        Forms\Components\Select::make('category')
                            ->options([
                                'ibadah' => 'Ibadah',
                                'muamalah' => 'Muamalah',
                                'keluarga' => 'Keluarga',
                                'akhlak' => 'Akhlak',
                                'aqidah' => 'Aqidah',
                                'lainnya' => 'Lainnya',
                            ])
                            ->required(),
                        Forms\Components\Textarea::make('question')
                            ->label('Pertanyaan')
                            ->required()
                            ->rows(3),
                        Forms\Components\RichEditor::make('answer')
                            ->label('Jawaban Kiai')
                            ->visible(fn($record) => auth()->user()->can('answer_questions') || auth()->user()->hasRole('super_admin') || auth()->user()->hasRole('admin_layanan')),
                        Forms\Components\Hidden::make('answered_by')
                            ->default(fn() => auth()->id()),
                        Forms\Components\Hidden::make('answered_at')
                            ->default(now()),
                    ]),
                Forms\Components\Section::make('Status')
                    ->columns(2)
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->options([
                                'pending' => 'Menunggu',
                                'answered' => 'Terjawab',
                                'published' => 'Dipublikasikan',
                                'rejected' => 'Ditolak',
                            ])
                            ->required()
                            ->default('pending'),
                        Forms\Components\Toggle::make('is_public')
                            ->label('Tampilkan di Website'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->label('Penanya'),
                Tables\Columns\TextColumn::make('kiai.name')
                    ->label('Ditujukan Ke')
                    ->searchable(),
                Tables\Columns\TextColumn::make('category')
                    ->badge(),
                Tables\Columns\TextColumn::make('question')
                    ->limit(50)
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'pending' => 'gray',
                        'answered' => 'info',
                        'published' => 'success',
                        'rejected' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\IconColumn::make('is_public')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Menunggu',
                        'answered' => 'Terjawab',
                        'published' => 'Dipublikasikan',
                        'rejected' => 'Ditolak',
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
            'index' => Pages\ListTanyaKiais::route('/'),
            'create' => Pages\CreateTanyaKiai::route('/create'),
            'edit' => Pages\EditTanyaKiai::route('/{record}/edit'),
        ];
    }
}
