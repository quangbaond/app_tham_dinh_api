<?php

namespace App\Filament\Resources\UserIdentificationsResource\Pages;

use App\Filament\Resources\UserIdentificationsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUserIdentifications extends ListRecords
{
    protected static string $resource = UserIdentificationsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
