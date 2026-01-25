<?php

namespace App\Filament\Resources\MunfiqunResource\Pages;

use App\Filament\Resources\MunfiqunResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMunfiqun extends EditRecord
{
    protected static string $resource = MunfiqunResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
