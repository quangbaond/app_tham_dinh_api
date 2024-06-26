<?php

namespace App\Filament\Resources\UserMovableResoucrceResource\RelationManagers;

use App\Models\UserMovables;
use Filament\Actions\ActionGroup;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserMovableRelationManager extends RelationManager
{
    protected static string $relationship = 'userMovables';

    protected static ?string $title  = 'Động sản';
    protected static ?string $lable  = 'Động sản';
    protected static ?string $inverseRelationship = 'section';
    protected static ?string $name = 'Động sản';

    protected static ?string $modelLabel = 'Động sản';

    // edit no data text
    protected static ?string $noDataMessage = 'Không có dữ liệu';


    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Thông tin tài sản')
                    ->schema([
                        Forms\Components\TextInput::make('loai_tai_san')
                            ->label('Loại tài sản')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\FileUpload::make('hinh_anh')->label('Hình ảnh')
                            ->image()
                            ->multiple()
                            ->previewable()
                            ->downloadable(true)
                            ->reorderable(true)
                            ->imageEditor(true)
                            ->openable(true)
                            ->appendFiles(true)
                            ->directory('images/movables'),

                        Forms\Components\TextInput::make('number_movables')->label('Biển số')
                    ])->heading('Thêm tài sản')
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            // ->heading('Posts')
            ->recordTitle(fn (UserMovables $record): string => "{$record->loai_tai_san} - {$record->number_movables}")
            ->columns([
                Tables\Columns\TextColumn::make('loai_tai_san')->label('Loại tài sản'),
                Tables\Columns\ImageColumn::make('hinh_anh')->label('Hình ảnh')
                    ->square()
                    ->limitedRemainingText(isSeparate: true)
                    ->limit(3)
                    ->stacked(),
                Tables\Columns\TextColumn::make('number_movables')->label('Biển số'),
                Tables\Columns\BooleanColumn::make('check')->label('Trạng thái kiểm tra'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->label('Thêm động sản'),
                // check hoặc uncheck
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
                        ->action(function (UserMovables $record) {
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
                        ->action(function (UserMovables $record) {
                            $record->update(['check' => !$record->check]);
                            Notification::make()
                                ->title('Bỏ kiểm tra tài sản thành công')
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
            ])
            ->emptyStateHeading('Không có dữ liệu')
            ->emptyStateDescription('Hãy thêm thông tin tài sản');
    }

    // edit heading modal

}
