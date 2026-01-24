<?php

namespace App\Filament\Resources\OrganizationStructureResource\Pages;

use App\Filament\Resources\OrganizationStructureResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOrganizationStructures extends ListRecords
{
    protected static string $resource = OrganizationStructureResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
