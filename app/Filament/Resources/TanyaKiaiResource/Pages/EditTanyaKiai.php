<?php

namespace App\Filament\Resources\TanyaKiaiResource\Pages;

use App\Filament\Resources\TanyaKiaiResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTanyaKiai extends EditRecord
{
    protected static string $resource = TanyaKiaiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
