<?php

namespace App\Filament\Resources\OrganizationStructureResource\Pages;

use App\Filament\Resources\OrganizationStructureResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOrganizationStructure extends EditRecord
{
    protected static string $resource = OrganizationStructureResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
