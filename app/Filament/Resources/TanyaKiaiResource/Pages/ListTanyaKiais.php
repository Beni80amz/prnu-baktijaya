<?php

namespace App\Filament\Resources\TanyaKiaiResource\Pages;

use App\Filament\Resources\TanyaKiaiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTanyaKiais extends ListRecords
{
    protected static string $resource = TanyaKiaiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
