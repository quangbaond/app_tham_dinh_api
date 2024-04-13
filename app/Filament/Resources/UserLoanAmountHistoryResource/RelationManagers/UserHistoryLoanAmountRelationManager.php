<?php

namespace App\Filament\Resources\UserLoanAmountHistoryResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserHistoryLoanAmountRelationManager extends RelationManager
{
    protected static string $relationship = 'UserHistoryLoanAmounts';

    protected static ?string $title = 'Lịch trả nợ';

    protected static ?string $modelLabel = 'Lịch trả nợ';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DateTimePicker::make('ngay_tra')
                    ->required()
                    ->label('Ngày trả'),
                Forms\Components\TextInput::make('so_goc_con_no')
                    ->required()
                    ->label('Số gốc còn nợ')
                    ->numeric(),
                Forms\Components\TextInput::make('so_tien_lai')
                    ->required()
                    ->label('Số tiền lãi')
                    ->numeric(),
                Forms\Components\TextInput::make('so_tien_tra')
                    ->required()
                    ->label('Số tiền đã trả')
                    ->numeric(),
                Forms\Components\TextInput::make('tong_goc_lai')
                    ->required()
                    ->label('Tổng gốc lãi')
                    ->numeric(),
                Forms\Components\Select::make('status_1')
                    ->required()
                    ->options([
                        0 => 'Trả đúng ngày',
                        1 => 'Trả chậm',
                    ])
                    ->label('Trạng thái'),
                Forms\Components\Select::make('status_2')
                    ->required()
                    ->options([
                        0 => 'Trả Đúng số tiền',
                        1 => 'Trả thiếu',
                        2 => 'Trả thừa',
                    ])
                    ->label('Trạng thái'),
                Forms\Components\Select::make('status')
                    ->required()
                    ->options([
                        0 => 'Chưa trả',
                        1 => 'Đã trả',
                    ])
                    ->label('Trạng thái'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\TextColumn::make('ngay_tra')
                    ->label('Ngày trả'),
                Tables\Columns\TextColumn::make('so_goc_con_no')
                    ->label('Số gốc còn nợ')
                    ->formatStateUsing(function ($state) {
                        return number_format($state);
                    }),
                Tables\Columns\TextColumn::make('so_tien_lai')
                    ->label('Số tiền lãi')
                    ->formatStateUsing(function ($state) {
                        return number_format($state);
                    }),
                Tables\Columns\TextColumn::make('so_tien_tra')
                    ->label('Số tiền đã trả')
                    ->formatStateUsing(function ($state) {
                        return number_format($state);
                    }),
                Tables\Columns\TextColumn::make('tong_goc_lai')
                    ->label('Tổng gốc lãi')
                    ->formatStateUsing(function ($state) {
                        return number_format($state);
                    }),

                Tables\Columns\TextColumn::make('status_1')
                    ->label('Trạng thái')
                    ->formatStateUsing(function ($state) {
                        switch ($state) {
                            case 0:
                                return 'Trả đúng ngày';
                            case 1:
                                return 'Trả chậm';
                            default:
                                return 'Không xác định';
                        }
                    }),
                Tables\Columns\TextColumn::make('status_2')
                    ->label('Trạng thái')
                    ->formatStateUsing(function ($state) {
                        switch ($state) {
                            case 0:
                                return 'Trả đúng ngày';
                            case 1:
                                return 'Trả chậm';
                            default:
                                return 'Không xác định';
                        }
                    }),
                Tables\Columns\TextColumn::make('status_2')
                    ->label('Trạng thái')
                    ->formatStateUsing(function ($state) {
                        switch ($state) {
                            case 0:
                                return 'Trả Đúng số tiền';
                            case 1:
                                return 'Trả thiếu';
                            default:
                                return 'Trả thừa';
                        }
                    }),
                Tables\Columns\TextColumn::make('status')
                    ->label('Trạng thái')
                    ->formatStateUsing(function ($state) {
                        switch ($state) {
                            case 0:
                                return 'Chưa trả';
                            case 1:
                                return 'Đã trả';
                            default:
                                return 'Không xác định';
                        }
                    }),
                Tables\Columns\BooleanColumn::make('status_3')
                    ->label('Trạng thái hoàn thành')
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
                    (
                        $table->getModel()::withoutGlobalScope(SoftDeletingScope::class)
                        ->where('status_3', 0)
                        ->first()
                        ? Tables\Actions\Action::make('Hoàn thành')
                        ->requiresConfirmation()
                        ->action(function ($record) {
                            $record->update(['status_3' => !$record->status_3]);
                        })
                        ->modalHeading('Hoàn thành')
                        ->modalDescription('Bạn có chắc chắn đã hoàn thành lịch trả nợ này?')
                        ->icon('heroicon-o-check-circle')
                        : Tables\Actions\Action::make('Bỏ hoàn thành')
                        ->requiresConfirmation()
                        ->action(function ($record) {
                            $record->update(['status_3' => !$record->status_3]);
                        })
                        ->modalHeading('Bỏ hoàn thành')
                        ->modalDescription('Bạn có chắc chắn muốn bỏ hoàn thành lịch trả nợ này?')
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
