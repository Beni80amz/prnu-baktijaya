<?php

namespace App\Filament\Resources\KiaiResource\Pages;

use App\Filament\Resources\KiaiResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageKiais extends ManageRecords
{
    protected static string $resource = KiaiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->after(function (\App\Models\Kiai $record) {
                    $record->refresh();
                    if ($record->user) {
                        \Filament\Notifications\Notification::make()
                            ->success()
                            ->title('Akun Kiai Dibuat Otomatis')
                            ->body("Email: {$record->user->email}\nPassword: kiai12345")
                            ->persistent()
                            ->send();
                    }
                }),
        ];
    }
}
