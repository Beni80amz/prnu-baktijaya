<?php

namespace App\Filament\Resources\SettingResource\Pages;

use App\Filament\Resources\SettingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSetting extends EditRecord
{
    protected static string $resource = SettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $type = $data['type'];
        $value = $data['value'] ?? null;

        if (in_array($type, ['text', 'number'])) {
            $data['value_text'] = $value;
        } elseif ($type === 'textarea') {
            $data['value_textarea'] = $value;
        } elseif ($type === 'image') {
            $data['value_image'] = $value ? [$value] : [];
        } elseif ($type === 'boolean') {
            $data['value_boolean'] = (bool) $value;
        }

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $type = $data['type'];

        if (in_array($type, ['text', 'number']) && isset($data['value_text'])) {
            $data['value'] = $data['value_text'];
        } elseif ($type === 'textarea' && isset($data['value_textarea'])) {
            $data['value'] = $data['value_textarea'];
        } elseif ($type === 'image' && isset($data['value_image'])) {
            // FileUpload returns an array, so we need to get the first value
            $imageValue = $data['value_image'];
            $data['value'] = is_array($imageValue) ? (array_values($imageValue)[0] ?? null) : $imageValue;
        } elseif ($type === 'boolean' && isset($data['value_boolean'])) {
            $data['value'] = $data['value_boolean'];
        }

        unset($data['value_text'], $data['value_textarea'], $data['value_image'], $data['value_boolean']);

        return $data;
    }
}
