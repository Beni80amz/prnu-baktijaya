<?php

namespace App\Filament\Resources\BanomResource\Pages;

use App\Filament\Resources\BanomResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBanoms extends ListRecords
{
    protected static string $resource = BanomResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
