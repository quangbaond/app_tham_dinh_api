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
                    ->options(SettingPeriod::pluck('title', 'value')->toArray())
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
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
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
}
