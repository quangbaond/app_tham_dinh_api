<?php

namespace App\Filament\Resources\UserIdentificationsResource\Pages;

use App\Filament\Resources\UserIdentificationsResource;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUserIdentifications extends EditRecord
{
    protected static string $resource = UserIdentificationsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\Action::make('Thẩm Định')
                ->requiresConfirmation()
                ->action(fn(User $record) => $record->update(['status_1' => 1]))
                ->modalHeading('Thẩm định')
                ->modalDescription('Bạn có chắc chắn muốn thẩm định người dùng này?')
                ->modalSubmitActionLabel('Thẩm định')

        ];
    }
}
