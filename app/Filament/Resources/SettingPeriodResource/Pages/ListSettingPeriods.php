<?php

namespace App\Filament\Resources\SettingPeriodResource\Pages;

use App\Filament\Resources\SettingPeriodResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSettingPeriods extends ListRecords
{
    protected static string $resource = SettingPeriodResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
