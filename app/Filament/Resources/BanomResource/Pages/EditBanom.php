<?php

namespace App\Filament\Resources\BanomResource\Pages;

use App\Filament\Resources\BanomResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBanom extends EditRecord
{
    protected static string $resource = BanomResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
