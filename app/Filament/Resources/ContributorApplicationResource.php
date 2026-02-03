<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContributorApplicationResource\Pages;
use App\Filament\Resources\ContributorApplicationResource\RelationManagers;
use App\Models\ContributorApplication;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ContributorApplicationResource extends Resource
{
    protected static ?string $model = ContributorApplication::class;
    protected static ?string $navigationIcon = 'heroicon-o-user-plus';
    protected static ?string $navigationGroup = 'Sistem';
    protected static ?string $label = 'Pendaftaran Kontributor';
    protected static ?string $pluralLabel = 'Pendaftaran Kontributor';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Pemohon')
                    ->schema([
                        Forms\Components\TextInput::make('name')->readOnly(),
                        Forms\Components\TextInput::make('email')->readOnly(),
                        Forms\Components\TextInput::make('phone')->readOnly(),
                        Forms\Components\Textarea::make('address')->readOnly(),
                    ])->columns(2),
                Forms\Components\Section::make('Detail Aplikasi')
                    ->schema([
                        Forms\Components\Textarea::make('experience')->readOnly(),
                        Forms\Components\Textarea::make('bio')->readOnly(),
                    ]),
                Forms\Components\Section::make('Status & Catatan')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'approved' => 'Disetujui',
                                'rejected' => 'Ditolak',
                            ])->required(),
                        Forms\Components\Textarea::make('note')
                            ->placeholder('Alasan penolakan atau catatan tambahan...'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->label('Nama'),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label('Telepon'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'approved' => 'success',
                        'rejected' => 'danger',
                        'pending' => 'warning',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tgl Daftar')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('approve')
                    ->label('Setujui')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->hidden(fn(ContributorApplication $record) => $record->status !== 'pending')
                    ->action(function (ContributorApplication $record) {
                        $record->update([
                            'status' => 'approved',
                            'reviewed_at' => now(),
                            'reviewed_by' => auth()->id(),
                        ]);

                        $record->user->assignRole('kontributor');

                        \Filament\Notifications\Notification::make()
                            ->success()
                            ->title('Pendaftaran Disetujui')
                            ->body("Akun **{$record->name}** kini aktif sebagai Kontributor.\n\n**Email Login:** {$record->email}\n**Password:** (Gunakan password akun saat mendaftar)")
                            ->persistent()
                            ->send();
                    })
                    ->requiresConfirmation(),
                Tables\Actions\Action::make('reject')
                    ->label('Tolak')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->hidden(fn(ContributorApplication $record) => $record->status !== 'pending')
                    ->form([
                        Forms\Components\Textarea::make('note')
                            ->label('Alasan Penolakan')
                            ->required(),
                    ])
                    ->action(function (ContributorApplication $record, array $data) {
                        $record->update([
                            'status' => 'rejected',
                            'note' => $data['note'],
                            'reviewed_at' => now(),
                            'reviewed_by' => auth()->id(),
                        ]);

                        \Filament\Notifications\Notification::make()
                            ->warning()
                            ->title('Pendaftaran Ditolak')
                            ->body("Pendaftaran {$record->name} telah ditolak.")
                            ->send();
                    }),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageContributorApplications::route('/'),
        ];
    }
}
