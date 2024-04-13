<?php

namespace App\Filament\Resources\UserPhoneReferenceResource\RelationManagers;

use App\Models\UserPhoneReference;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserPhoneReferencesRelationManager extends RelationManager
{
    protected static string $relationship = 'UserPhoneReferences';

    protected static ?string $title = 'Số điện thoại người thân';

    protected static ?string $label = 'Số điện thoại người thân';

    protected static ?string $modelLabel = 'Số điện thoại người thân';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('phone')
                    ->required()
                    ->label('Số điện thoại')
                    ->maxLength(255),
                Forms\Components\Select::make('relationship')
                    ->required()
                    ->options([
                        'Bố' => 'Bố',
                        'Mẹ' => 'Mẹ',
                        'Anh/Em trai' => 'Anh/Em trai',
                        'Chị/Em gái' => 'Chị/Em gái',
                        'Vợ/Chồng' => 'Vợ/Chồng',
                        'Bạn bè' => 'Bạn bè',
                    ])
                    ->label('Quan hệ'),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->label('Họ và tên')
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\TextColumn::make('phone')
                    ->label('Số điện thoại'),
                Tables\Columns\TextColumn::make('relationship')
                    ->label('Quan hệ'),
                Tables\Columns\TextColumn::make('name')
                    ->label('Họ và tên'),
                Tables\Columns\BooleanColumn::make('check')
                    ->label('Trang thái kiểm tra')
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
                        ->action(function (UserPhoneReference $record) {
                            $record->update(['check' => !$record->check]);
                            Notification::make()
                                ->title('Thông báo, kiểm tra số điện thoại thành công')
                                ->success()
                                ->send();
                        })
                        ->modalHeading('Thẩm định')
                        ->modalDescription('Bạn có chắc chắn đã kiểm tra số điện thoại này?')
                        ->icon('heroicon-o-check-circle')
                        : Tables\Actions\Action::make('Bỏ kiểm tra')
                        ->requiresConfirmation()
                        ->action(function (UserPhoneReference $record) {
                            $record->update(['check' => !$record->check]);
                            Notification::make()
                                ->title('Thông báo, bỏ kiểm tra số điện thoại thành công')
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
