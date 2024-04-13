<?php

namespace App\Filament\Resources\SettingPeriodResource\Pages;

use App\Filament\Resources\SettingPeriodResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSettingPeriod extends EditRecord
{
    protected static string $resource = SettingPeriodResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
