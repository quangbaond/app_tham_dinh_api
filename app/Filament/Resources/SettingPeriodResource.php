<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SettingPeriodResource\Pages;
use App\Filament\Resources\SettingPeriodResource\RelationManagers;
use App\Models\SettingPeriod;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SettingPeriodResource extends Resource
{
    protected static ?string $model = SettingPeriod::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $label = 'Cài đặt thời hạn vay';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make([
                    Forms\Components\TextInput::make('title')
                        ->label('Thời hạn')
                        ->required(),
                    Forms\Components\TextInput::make('value')
                        ->label('Giá trị')
                        ->numeric()
                        ->suffixIcon('heroicon-o-receipt-percent')
                        ->hint('Giá trị này sẽ được tính theo phần trăm/năm')
                        ->required(),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Thời hạn'),
                Tables\Columns\TextColumn::make('value')
                    ->label('Giá trị')
                    ->formatStateUsing(function ($state) {
                        return $state . '%';
                    }),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSettingPeriods::route('/'),
            'create' => Pages\CreateSettingPeriod::route('/create'),
            'edit' => Pages\EditSettingPeriod::route('/{record}/edit'),
        ];
    }
}
