<?php

namespace App\Filament\Resources\DawuhResource\Pages;

use App\Filament\Resources\DawuhResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDawuhs extends ListRecords
{
    protected static string $resource = DawuhResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
