<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SettingResource\Pages;
use App\Models\Setting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SettingResource extends Resource
{
    protected static ?string $model = Setting::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationGroup = 'Pengaturan Website';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('label')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('key')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->disabled(fn($record) => $record !== null), // Disable on edit
                        Forms\Components\Select::make('type')
                            ->options([
                                'text' => 'Text',
                                'textarea' => 'Long Text',
                                'image' => 'Image',
                                'number' => 'Number',
                                'boolean' => 'Switch',
                            ])
                            ->required()
                            ->live(),
                        Forms\Components\Select::make('group')
                            ->options([
                                'general' => 'Umum',
                                'contact' => 'Kontak',
                                'social' => 'Sosial Media',
                                'prayer' => 'Jadwal Sholat',
                            ])
                            ->required(),
                        Forms\Components\Textarea::make('description')
                            ->columnSpanFull(),

                        // Value fields based on type
                        Forms\Components\TextInput::make('value_text')
                            ->label('Value')
                            ->visible(fn(Forms\Get $get) => in_array($get('type'), ['text', 'number', null]))
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('value_textarea')
                            ->label('Value')
                            ->visible(fn(Forms\Get $get) => $get('type') === 'textarea')
                            ->columnSpanFull(),
                        Forms\Components\FileUpload::make('value_image')
                            ->label('Value')
                            ->image()
                            ->disk('public') // Fix: Explicitly use public disk
                            ->directory('settings')
                            ->fetchFileInformation(false)
                            ->visible(fn(Forms\Get $get) => $get('type') === 'image')
                            ->columnSpanFull(),
                        Forms\Components\Toggle::make('value_boolean')
                            ->label('Value')
                            ->visible(fn(Forms\Get $get) => $get('type') === 'boolean'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('label')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('key')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('value')
                    ->limit(50),
                Tables\Columns\TextColumn::make('group')
                    ->badge(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('group')
                    ->options([
                        'general' => 'Umum',
                        'contact' => 'Kontak',
                        'social' => 'Sosial Media',
                    ]),
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
            'index' => Pages\ListSettings::route('/'),
            'create' => Pages\CreateSetting::route('/create'),
            'edit' => Pages\EditSetting::route('/{record}/edit'),
        ];
    }
}
