<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserFinanceResource\RelationManagers\UserFinancesRelationManager;
use App\Filament\Resources\UserLoanAmountResource\RelationManagers\UserLoanAmountsRelationManager;
use App\Filament\Resources\UserMovableResoucrceResource\RelationManagers\UserMovableRelationManager;
use App\Filament\Resources\UserPhoneReferenceResource\RelationManagers\UserPhoneReferencesRelationManager;
use App\Filament\Resources\UserPhoneWorkPlaceResource\RelationManagers\UserPhoneWorkPlacesRelationManager;
use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Actions;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use http\Url;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $label = 'Người dùng';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Thông tin pháp lý')
                    ->relationship('userIdentifications')
                    ->headerActions([
                        (
                            $form->getRecord()?->userIdentifications?->check == 1
                            ? Actions\Action::make('check')
                            ->label('Huỷ thẩm định')
                            ->icon('heroicon-o-x-circle')
                            ->requiresConfirmation()
                            ->modalHeading('Huỷ thẩm định')
                            ->modalDescription('Bạn có chắc chắn muốn huỷ thẩm định thông tin người dùng này? Sau khi huỷ, thông tin người dùng sẽ không được thẩm định ')
                            ->modalSubmitActionLabel('Huỷ thẩm định')
                            ->icon('heroicon-o-x-circle')
                            ->action(function (User $user) {
                                $user->userIdentifications->update(['check' => 0]);
                                Notification::make()
                                    ->title('Thông báo, thông tin người dùng đã được huỷ thẩm định')
                                    ->success()
                                    ->send();
                            })
                            : Actions\Action::make('check')
                            ->label('Thẩm định')
                            ->icon('heroicon-o-check-circle')
                            ->requiresConfirmation()
                            ->modalHeading('Thẩm định')
                            ->modalDescription('Bạn có chắc chắn đã kiểm tra thông tin người dùng này? Sau khi thẩm định, thông tin người dùng sẽ được đánh dấu là đã kiểm tra')
                            ->modalSubmitActionLabel('Đã kiểm tra')
                            ->icon('heroicon-o-check-circle')
                            ->action(function (User $user) {
                                $user->userIdentifications->update(['check' => 1]);
                                Notification::make()
                                    ->title('Thông báo, thông tin người dùng đã được kiểm tra')
                                    ->success()
                                    ->send();
                            })
                        )
                    ])

                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Họ và tên')
                            ->required(),
                        Forms\Components\TextInput::make('id_card')
                            ->label('CCCD')
                            ->required(),
                        Forms\Components\Textarea::make('address')
                            ->label('Địa chỉ')
                            ->columnSpanFull()
                            ->required(),
                        Forms\Components\Textarea::make('address_now')
                            ->label('Địa chỉ hiện tại')
                            ->columnSpanFull()
                            ->required(),
                        Forms\Components\FileUpload::make('image_front')
                            ->directory('images/cccd')
                            ->label('Ảnh mặt trước CCCD')
                            ->required()
                            ->previewable()
                            ->downloadable(true)
                            ->openable(true),
                        Forms\Components\FileUpload::make('image_back')
                            ->previewable()
                            ->downloadable(true)
                            ->openable(true)
                            ->directory('images/cccd')
                            ->label('Ảnh mặt sau CCCD')
                            ->required(),

                    ])->columns(2),
                Forms\Components\Section::make('Thông tin đăng nhập')
                    ->schema([
                        Forms\Components\TextInput::make('phone')
                            ->label('Số điện thoại')
                            ->required(),
                        Forms\Components\TextInput::make('password')
                            ->label('Mật khẩu')
                            ->password()
                            ->required(
                                fn (User $user) => is_null($user->id)
                            ),
                        Forms\Components\TextInput::make('longitude')
                            ->label('Kinh độ')
                            ->required(),

                        Forms\Components\TextInput::make('latitude')
                            ->label('Vĩ độ')
                            ->required(),

                    ])->columns(2),

                Forms\Components\Section::make('Thông tin bằng lái xe')
                    ->relationship('userLicenses')
                    ->headerActions([
                        (
                            $form->getRecord()?->userLicenses?->check == 1
                            ? Actions\Action::make('check')
                            ->label('Huỷ thẩm định')
                            ->icon('heroicon-o-x-circle')
                            ->requiresConfirmation()
                            ->modalHeading('Huỷ thẩm định')
                            ->modalDescription('Bạn có chắc chắn muốn huỷ thẩm định bằng lái xe của người dùng này? Sau khi huỷ, bằng lái xe sẽ không được thẩm định ')
                            ->modalSubmitActionLabel('Huỷ thẩm định')
                            ->icon('heroicon-o-x-circle')
                            ->action(function (User $user) {
                                $user->userLicenses->update(['check' => 0]);
                                Notification::make()
                                    ->title('Thông báo, bằng lái xe của người dùng đã được huỷ thẩm định')
                                    ->success()
                                    ->send();
                            })
                            : Actions\Action::make('check')
                            ->label('Thẩm định')
                            ->icon('heroicon-o-check-circle')
                            ->requiresConfirmation()
                            ->modalHeading('Thẩm định')
                            ->modalDescription('Bạn có chắc chắn đã kiểm tra bằng lái xe của người dùng này? Sau khi thẩm định, bằng lái xe sẽ được đánh dấu là đã kiểm tra')
                            ->modalSubmitActionLabel('Đã kiểm tra')
                            ->icon('heroicon-o-check-circle')
                            ->action(function (User $user) {
                                $user->userLicenses->update(['check' => 1]);
                                Notification::make()
                                    ->title('Thông báo, bằng lái xe của người dùng đã được kiểm tra')
                                    ->success()
                                    ->send();
                            })
                        )
                    ])
                    ->schema([
                        Forms\Components\TextInput::make('id_card')
                            ->label('Số bằng lái xe')
                            ->required(),
                        Forms\Components\TextInput::make('code')
                            ->label('Mã số')
                            ->required(),
                        Forms\Components\TextInput::make('type')
                            ->label('Loại')
                            ->required(),
                        Forms\Components\TextInput::make('class')
                            ->label('Hạng')
                            ->required(),

                        Forms\Components\TextInput::make('address')
                            ->label('Địa chỉ')
                            ->required(),
                        Forms\Components\TextInput::make('dob')
                            ->label('Ngày sinh')
                            ->required(),
                        Forms\Components\TextInput::make('name')
                            ->label('Họ và tên')
                            ->required(),
                        Forms\Components\TextInput::make('date')
                            ->label('Ngày cấp')
                            ->required(),
                        Forms\Components\TextInput::make('place_issue')
                            ->label('Nơi cấp')
                            ->required(),
                        Forms\Components\FileUpload::make('image_front')
                            ->label('Ảnh mặt trước')
                            ->columnSpanFull()
                            ->directory('images/license')
                            ->required()
                            ->previewable()
                            ->downloadable(true)
                            ->openable(true),
                        Forms\Components\FileUpload::make('image_back')
                            ->label('Ảnh mặt sau')
                            ->columnSpanFull()
                            ->directory('images/license')
                            ->required()
                            ->previewable()
                            ->downloadable(true)
                            ->openable(true),
                    ])->columns(2),

                Forms\Components\Section::make('Thông tin công việc (Tài chính)')
                    ->relationship('userFinances')
                    ->headerActions([
                        (
                            $form->getRecord()?->userFinances?->check == 1
                            ? Actions\Action::make('check')
                            ->label('Huỷ thẩm định')
                            ->icon('heroicon-o-x-circle')
                            ->requiresConfirmation()
                            ->modalHeading('Huỷ thẩm định')
                            ->modalDescription('Bạn có chắc chắn muốn huỷ thẩm định thông tin tài chính của người dùng này? Sau khi huỷ, thông tin tài chính sẽ không được thẩm định ')
                            ->modalSubmitActionLabel('Huỷ thẩm định')
                            ->icon('heroicon-o-x-circle')
                            ->action(function (User $user) {
                                $user->userFinances->update(['check' => 0]);
                                Notification::make()
                                    ->title('Thông báo, thông tin tài chính của người dùng đã được huỷ thẩm định')
                                    ->success()
                                    ->send();
                            })
                            : Actions\Action::make('check')
                            ->label('Thẩm định')
                            ->icon('heroicon-o-check-circle')
                            ->requiresConfirmation()
                            ->modalHeading('Thẩm định')
                            ->modalDescription('Bạn có chắc chắn đã kiểm tra thông tin tài chính của người dùng này? Sau khi thẩm định, thông tin tài chính sẽ được đánh dấu là đã kiểm tra')
                            ->modalSubmitActionLabel('Đã kiểm tra')
                            ->icon('heroicon-o-check-circle')
                            ->action(function (User $user) {
                                $user->userFinances->update(['check' => 1]);
                                Notification::make()
                                    ->title('Thông báo, thông tin tài chính của người dùng đã được kiểm tra')
                                    ->success()
                                    ->send();
                            })
                        )
                    ])
                    ->schema([
                        Forms\Components\TextInput::make('thu_nhap_hang_thang')
                            ->label('Thu nhập hàng tháng')
                            ->required(),
                        Forms\Components\TextInput::make('ten_cong_ty')
                            ->label('Tên công ty')
                            ->required(),
                        Forms\Components\TextInput::make('dia_chi_cong_ty')
                            ->label('Địa chỉ công ty')
                            ->required(),
                        Forms\Components\TextInput::make('so_dien_thoai_cong_ty')
                            ->label('Số điện thoại công ty')
                            ->required(),
                        Forms\Components\FileUpload::make('hinh_anh_sao_ke')
                            ->directory('images/salary_statements')
                            ->columnSpanFull()
                            ->image()
                            ->multiple()
                            ->previewable()
                            ->downloadable(true)
                            ->reorderable(true)
                            ->imageEditor(true)
                            ->openable(true)
                            ->appendFiles(true)
                            ->label('Hình ảnh sao kê')
                            ->required(),
                    ])->columns(2),


            ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('userIdentifications.name')
                    ->searchable()
                    ->label('Họ và tên')
                    ->sortable(),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('userIdentifications.id_card')
                    ->label('CCCD')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\SelectColumn::make('status_1')
                    ->label('Trang thái')
                    ->options([
                        0 => 'Chưa thẩm định',
                        1 => 'Đã thẩm định',
                    ])
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status_1')
                    ->options([
                        0 => 'Chưa thẩm định',
                        1 => 'Đã thẩm định',
                    ])
                    ->label('Trạng thái'),
            ], layout: Tables\Enums\FiltersLayout::AboveContent)->filtersFormColumns(2)
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\Action::make('check')
                    ->label('Thẩm định')
                    ->icon('heroicon-o-check-circle')
                    ->url(fn (User $user) => "/admin/users/{$user->id}/check"),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            UserPhoneReferencesRelationManager::class,
            UserPhoneWorkPlacesRelationManager::class,
            UserMovableRelationManager::class,
            UserSanEstateResource\RelationManagers\UserSanEstateRelationManager::class,
            // UserFinancesRelationManager::class,
            UserLoanAmountsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
