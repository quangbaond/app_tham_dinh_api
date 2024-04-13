<?php

namespace App\Filament\Resources\UserSanEstateResource\RelationManagers;

use App\Models\UserSanEstate;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserSanEstateRelationManager extends RelationManager
{
    protected static string $relationship = 'userSanEstates';

    protected static ?string $title  = 'Bất Động sản';

    protected static ?string $inverseRelationship = 'section';

    protected static ?string $name = 'Bất động sản';

    protected static ?string $modelLabel = 'Bất Động sản';



    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Bất động sản')
                    ->schema([
                        Forms\Components\TextInput::make('dia_chi')
                            ->label('Địa chỉ')
                            ->required(),
                        Forms\Components\FileUpload::make('hinh_anh')->label('Hình ảnh')
                            ->image()
                            ->multiple()
                            ->previewable()
                            ->downloadable(true)
                            ->reorderable(true)
                            ->imageEditor(true)
                            ->openable(true)
                            ->appendFiles(true)
                            ->directory('images/san_estates')
                    ])
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\TextColumn::make('dia_chi')->label('Địa chỉ'),
                Tables\Columns\ImageColumn::make('hinh_anh')
                    ->stacked()
                    ->limit(3)
                    ->limitedRemainingText(isSeparate: true)
                    ->square()
                    ->label('Hình ảnh'),
                Tables\Columns\BooleanColumn::make('check')->label('Kiểm tra')
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->label('Thêm bất động sản'),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\ViewAction::make(),
                    (
                        $table->getModel()::withoutGlobalScope(SoftDeletingScope::class)
                        ->where('check', 0)
                        ->first()
                        ? Tables\Actions\Action::make('Kiểm tra')
                        ->requiresConfirmation()
                        ->action(function (UserSanEstate $record) {
                            $record->update(['check' => !$record->check]);
                            Notification::make()
                                ->title('Kiểm tra tài sản')
                                ->success()
                                ->send();
                        })
                        ->modalHeading('Thẩm định')
                        ->modalDescription('Bạn có chắc chắn đã kiểm tra tài sản này?')
                        ->icon('heroicon-o-check-circle')
                        : Tables\Actions\Action::make('Bỏ kiểm tra')
                        ->requiresConfirmation()
                        ->icon('heroicon-o-x-circle')
                        ->modalHeading('Bỏ thẩm định')
                        ->modalDescription('Bạn có chắc chắn muốn bỏ kiểm tra tài sản này?')
                        ->action(function (UserSanEstate $record) {
                            $record->update(['check' => !$record->check]);
                            Notification::make()
                                ->title('Bỏ kiểm tra tài sản')
                                ->success()
                                ->send();
                        })
                    )
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
