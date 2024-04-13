<?php

namespace App\Filament\Resources\UserLoanAmountResource\Pages;

use App\Filament\Resources\UserLoanAmountResource;
use App\Models\SettingPeriod;
use Filament\Actions;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Mockery\Matcher\Not;

class CreateUserLoanAmount extends CreateRecord
{
    protected static string $resource = UserLoanAmountResource::class;


    protected function afterCreate(): void
    {
        $data = $this->form->getRecord()->toArray();

        $khoanvay = $data['khoan_vay'];
        $thoihanvay = SettingPeriod::find($data['thoi_han_vay'])->title;
        $phantram = SettingPeriod::where('value', $thoihanvay)->first()->value;
        $lichtra = $this->tinhlai($khoanvay, $thoihanvay, $phantram);
        foreach ($lichtra as $item) {
            $this->form->getRecord()->userHistoryLoanAmounts()->create($item);
        }

        Notification::make()
            ->title('Tạo lịch trả thành công')
            ->success()
            ->send();
    }
}
