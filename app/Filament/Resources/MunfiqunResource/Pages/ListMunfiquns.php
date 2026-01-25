<?php

namespace App\Filament\Resources\MunfiqunResource\Pages;

use App\Filament\Resources\MunfiqunResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMunfiquns extends ListRecords
{
    protected static string $resource = MunfiqunResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
