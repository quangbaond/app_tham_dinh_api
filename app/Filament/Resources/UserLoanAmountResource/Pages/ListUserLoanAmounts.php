<?php

namespace App\Filament\Resources\UserLoanAmountResource\Pages;

use App\Filament\Resources\UserLoanAmountResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUserLoanAmounts extends ListRecords
{
    protected static string $resource = UserLoanAmountResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
