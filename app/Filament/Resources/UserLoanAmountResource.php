<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserLoanAmountHistoryResource\RelationManagers\UserHistoryLoanAmountRelationManager;
use App\Filament\Resources\UserLoanAmountResource\Pages;
use App\Filament\Resources\UserLoanAmountResource\RelationManagers;
use App\Models\SettingPeriod;
use App\Models\User;
use App\Models\UserLoanAmount;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserLoanAmountResource extends Resource
{
    protected static ?string $model = UserLoanAmount::class;

    protected static ?string $label = 'Khoản vay';

    protected static ?string $title = 'Khoản vay';

    // icon
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make([
                    Forms\Components\Select::make('user_id')
                        ->label('Người dùng')
                        ->relationship('userIdentifications', 'name')
                        ->required(),
                    Forms\Components\TextInput::make('khoan_vay')
                        ->label('Khoản vay')
                        ->numeric()
                        ->required(),
                    Forms\Components\Select::make('thoi_han_vay')
                        ->label('Thời hạn vay')
                        ->options(SettingPeriod::pluck('title', 'id')->toArray())
                        ->required(),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('userIdentifications.name')
                    ->label('Người dùng'),
                Tables\Columns\TextColumn::make('khoan_vay')
                    ->label('Khoản vay'),
                Tables\Columns\TextColumn::make('thoi_han_vay')
                    ->label('Thời hạn vay')
                    ->formatStateUsing(fn ($state) => SettingPeriod::where('value', $state)->first()->title ?? $state),
                Tables\Columns\TextColumn::make('status')
                    ->label('Trạng thái')
                    ->formatStateUsing(fn ($state) => match ($state) {
                        0 => 'Chờ duyệt',
                        1 => 'Đã duyệt',
                        2 => 'Từ chối',
                        3 => 'Đã trả',
                        default => $state,
                    }),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ViewAction::make(),
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
            UserHistoryLoanAmountRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUserLoanAmounts::route('/'),
            'create' => Pages\CreateUserLoanAmount::route('/create'),
            'edit' => Pages\EditUserLoanAmount::route('/{record}/edit'),
        ];
    }
}
