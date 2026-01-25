<?php

namespace App\Filament\Resources\IncomeTypeResource\Pages;

use App\Filament\Resources\IncomeTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListIncomeTypes extends ListRecords
{
    protected static string $resource = IncomeTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
