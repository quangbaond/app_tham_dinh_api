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

    protected function tinhlai($khoanvay, $thoihanvay, $phantram)
    {
        $laixuat = $phantram / 12;
        $goc = $khoanvay;
        $thoihan = (int)str_replace(' tháng', '', $thoihanvay);
        $lichtra = [];
        $goc_con_lai = $goc;
        $goc_moi_ky = $goc / $thoihan;
        $lai = 0;
        $tong_goc_lai = 0;

        for ($i = 1; $i <= $thoihan; $i++) {
            $lai = $goc_con_lai * $laixuat / 100;
            $tong_goc_lai = $goc_moi_ky + $lai;
            $goc_con_lai = $goc_con_lai - $goc_moi_ky;
            $date = date('Y-m-d', strtotime("+$i months"));
            $lichtra[] = [
                'ngay_tra' => $date,
                'so_tien_tra' => 0,
                'so_goc_con_no' => $goc_con_lai,
                'so_tien_lai' => $lai,
                'tong_goc_lai' => $tong_goc_lai,
                'status' => 0,
                'status_1' => 0,
                'status_2' => 0,
                'status_3' => 0,
            ];
        }
        return $lichtra;
    }
}
