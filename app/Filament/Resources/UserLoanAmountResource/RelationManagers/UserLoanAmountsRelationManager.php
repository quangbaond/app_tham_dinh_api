<?php

namespace App\Filament\Resources\UserLoanAmountResource\RelationManagers;

use App\Models\SettingPeriod;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserLoanAmountsRelationManager extends RelationManager
{
    protected static string $relationship = 'UserLoanAmounts';

    protected static ?string $title = 'Khoản vay';

    protected static ?string $label = 'Khoản vay';

    protected static ?string $modelLabel = 'Khoản vay';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('khoan_vay')
                    ->label('Khoản vay')
                    ->numeric()
                    ->required(),
                Forms\Components\Select::make('thoi_han_vay')
                    ->label('Thời hạn vay')
                    ->options(SettingPeriod::pluck('title', 'id')->toArray())
                    ->required()
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('Khoản vay')
            ->columns([
                Tables\Columns\TextColumn::make('khoan_vay')
                    ->label('Khoản vay'),
                Tables\Columns\TextColumn::make('thoi_han_vay')
                    ->label('Thời hạn vay')
                    ->formatStateUsing(fn ($state) => SettingPeriod::where('value', $state)->first()->title ?? $state),
                // Tables\Columns\TextColumn::make('status')
                //     ->label('Trạng thái')
                //     ->formatStateUsing(function ($state) {
                //         switch ($state) {
                //             case 0:
                //                 return 'Chờ duyệt';
                //             case 1:
                //                 return 'Đã duyệt';
                //             case 2:
                //                 return 'Từ chối';
                //             case 3:
                //                 return 'Chưa hoàn thành';
                //             case 4:
                //                 return 'Hoàn thành';
                //             default:
                //                 return $state;
                //         }
                //     }),
                Tables\Columns\SelectColumn::make('status')
                    ->label('Trạng thái')
                    ->options([
                        0 => 'Chờ duyệt',
                        1 => 'Đã duyệt',
                        2 => 'Từ chối',
                        3 => 'Chưa hoàn thành',
                        4 => 'Hoàn thành',
                    ])
                    ->afterStateUpdated(function (Builder $query, $state) {
                        switch ($state) {
                            case 0:
                                Notification::make()
                                    ->title('Chờ duyệt khoản vay thành công')
                                    ->success()
                                    ->send();
                                break;
                            case 1:
                                Notification::make()
                                    ->title('Duyệt khoản vay thành công')
                                    ->success()
                                    ->send();
                                break;
                            case 2:
                                Notification::make()
                                    ->title('Từ chối khoản vay thành công')
                                    ->success()
                                    ->send();
                                break;
                            case 3:
                                Notification::make()
                                    ->title('Chưa hoàn thành khoản vay')
                                    ->success()
                                    ->send();
                                break;
                            case 4:
                                Notification::make()
                                    ->title('Hoàn thành khoản vay')
                                    ->success()
                                    ->send();
                                break;
                            default:
                                break;
                        }
                    }),

            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->after(function ($record) {
                        $data = $record->toArray();
                        $khoanvay = $data['khoan_vay'];
                        $thoihanvay = SettingPeriod::find($data['thoi_han_vay'])->title;
                        $phantram = SettingPeriod::where('value', $thoihanvay)->first()->value;
                        $lichtra = $this->tinhlai($khoanvay, $thoihanvay, $phantram);
                        foreach ($lichtra as $item) {
                            $record->userHistoryLoanAmounts()->create($item);
                        }
                        Notification::make()
                            ->title('Tạo lịch trả thành công')
                            ->success()
                            ->send();
                    }),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make()
                        ->after(function ($record) {
                            $data = $record->toArray();
                            $khoanvay = $data['khoan_vay'];
                            $thoihanvay = SettingPeriod::find($data['thoi_han_vay'])->title;
                            $phantram = SettingPeriod::where('value', $thoihanvay)->first()->value;
                            $lichtra = $this->tinhlai($khoanvay, $thoihanvay, $phantram);
                            $record->userHistoryLoanAmounts()->delete();
                            foreach ($lichtra as $item) {
                                $record->userHistoryLoanAmounts()->create($item);
                            }
                            Notification::make()
                                ->title('Cập nhật lịch trả thành công')
                                ->success()
                                ->send();
                        }),
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\Action::make('history')
                        ->label('Lịch sử')
                        ->url(fn ($record) => "/admin/user-loan-amounts/" . $record->id . '/edit')
                        ->icon('heroicon-o-clock'),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
