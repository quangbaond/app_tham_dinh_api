<?php

namespace App\Filament\Resources\UserFinanceResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserFinancesRelationManager extends RelationManager
{
    protected static string $relationship = 'userFinances';

    protected static ?string $title = 'Quản lý tài chính';

    protected static ?string $lable = 'Tài chính';

    protected static ?string $modelLabel = 'Tài chính';


    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('thu_nhap_hang_thang')
                    ->label('Thu nhập hàng tháng')
                    ->numeric()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('ten_cong_ty')
                    ->label('Tên công ty')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('dia_chi_cong_ty')
                    ->label('Địa chỉ công ty')
                    ->columnSpanFull()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('so_dien_thoai_cong_ty')
                    ->label('Số điện thoại công ty')
                    ->required()
                    ->maxLength(255),
                Forms\Components\FileUpload::make('hinh_anh_sao_ke')
                    ->label('Hình ảnh sao kê')
                    ->columnSpanFull()
                    ->image()
                    ->multiple()
                    ->previewable()
                    ->downloadable(true)
                    ->reorderable(true)
                    ->imageEditor(true)
                    ->openable(true)
                    ->appendFiles(true)
                    ->directory('images/user_finances'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitle('Thông tim tài chính')
            ->columns([
                Tables\Columns\TextColumn::make('thu_nhap_hang_thang')->label('Thu nhập hàng tháng'),
                Tables\Columns\TextColumn::make('ten_cong_ty')->label('Tên công ty'),
                Tables\Columns\TextColumn::make('dia_chi_cong_ty')->label('Địa chỉ công ty'),
                Tables\Columns\TextColumn::make('so_dien_thoai_cong_ty')->label('Số điện thoại công ty'),
                Tables\Columns\ImageColumn::make('hinh_anh_sao_ke')
                    ->label('Hình ảnh sao kê')->square()
                    ->limitedRemainingText(isSeparate: true)
                    ->limit(3)
                    ->stacked(),
                Tables\Columns\BooleanColumn::make('check')->label('Trạng thái kiểm tra'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->label('Thêm mới'),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\Action::make('Kiểm tra')
                        ->action(fn ($record) => $record->update(['check' => !$record->check]))
                        ->modalHeading('Thẩm định')
                        ->modalDescription('Bạn có chắc chắn đã kiểm tra tài sản này?')
                        ->modalSubmitActionLabel('Đã kiểm tra')
                        ->icon('heroicon-o-check-circle'),
                ]),

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
