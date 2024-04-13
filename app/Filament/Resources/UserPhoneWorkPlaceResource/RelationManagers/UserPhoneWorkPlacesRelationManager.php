<?php

namespace App\Filament\Resources\UserPhoneWorkPlaceResource\RelationManagers;

use App\Models\UserPhoneWorkPlace;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserPhoneWorkPlacesRelationManager extends RelationManager
{
    protected static string $relationship = 'userPhoneWorkPlaces';

    protected static ?string $title = 'Số điện thoại nơi làm việc';

    protected static ?string $label = 'Số điện thoại nơi làm việc';

    protected static ?string $modelLabel = 'Số điện thoại nơi làm việc';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('phone')
                    ->label('Số điện thoại')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('relationship')
                    ->required()
                    ->options([
                        'Giám đốc' => 'Giám đốc',
                        'Trưởng phòng' => 'Trưởng phòng',
                        'Phó phòng' => 'Phó phòng',
                        'Nhân viên' => 'Nhân viên',
                        'Kế toán' => 'Kế toán',
                    ])
                    ->label('Chức vụ'),
                Forms\Components\TextInput::make('name')
                    ->label('Họ và tên')
                    ->required()
                    ->maxLength(255),

            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Họ và tên'),
                Tables\Columns\TextColumn::make('phone')
                    ->label('Số điện thoại'),
                Tables\Columns\TextColumn::make('relationship')
                    ->label('Chức vụ'),
                Tables\Columns\BooleanColumn::make('check')
                    ->label('Trạng thái kiểm tra')
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),

                    (
                        $table->getModel()::withoutGlobalScope(SoftDeletingScope::class)
                        ->where('check', 0)
                        ->first()
                        ? Tables\Actions\Action::make('Kiểm tra')
                        ->requiresConfirmation()
                        ->action(function (UserPhoneWorkPlace $record) {
                            $record->update(['check' => !$record->check]);
                            Notification::make()
                                ->title('Kiểm tra số điện thoại')
                                ->success()
                                ->send();
                        })
                        ->modalHeading('Thẩm định')
                        ->modalDescription('Bạn có chắc chắn đã kiểm tra số điện thoại này?')
                        ->icon('heroicon-o-check-circle')
                        : Tables\Actions\Action::make('Bỏ kiểm tra')
                        ->requiresConfirmation()
                        ->action(function (UserPhoneWorkPlace $record) {
                            $record->update(['check' => !$record->check]);
                            Notification::make()
                                ->title('Bỏ kiểm tra số điện thoại thành công')
                                ->success()
                                ->send();
                        })
                        ->modalHeading('Bỏ thẩm định')
                        ->modalDescription('Bạn có chắc chắn muốn bỏ kiểm tra số điện thoại này?')
                        ->icon('heroicon-o-x-circle')
                    )
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
