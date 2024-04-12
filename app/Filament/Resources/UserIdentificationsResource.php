<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserIdentificationsResource\Pages;
use App\Filament\Resources\UserIdentificationsResource\RelationManagers;
use App\Models\UserIdentification;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserIdentificationsResource extends Resource
{
    protected static ?string $model = UserIdentification::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $label = 'Thông tin người dùng';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Thông tin người dùng')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Họ và tên')
                            ->required(),
                        Forms\Components\TextInput::make('id_card')
                            ->label('CCCD')
                            ->required(),
                        Forms\Components\Select::make('status')
                            ->label('Trạng thái')
                            ->options([
                                0 => 'Chưa thẩm định',
                                1 => 'Đã thẩm định',
                            ])
                            ->required(),
                        // file upload
                        Forms\Components\FileUpload::make('image_front')
                            ->label('Ảnh CCCD')
                            ->image()
                            ->required(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->label('Họ và tên')
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.phone')
                    ->searchable()
                    ->label('Số điện thoại')
                    ->sortable(),
                Tables\Columns\TextColumn::make('id_card')
                    ->searchable()
                    ->label('CCCD')
                    ->sortable(),
                Tables\Columns\IconColumn::make('status')
                    ->label('Trạng thái')
                    ->icon(function ($record) {
                        return $record->status == 1 ? 'heroicon-s-check-circle' : 'heroicon-s-x-circle';
                    })
                    ->color(function ($record) {
                        return $record->status == 1 ? 'green' : 'red';
                    }),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListUserIdentifications::route('/'),
            'create' => Pages\CreateUserIdentifications::route('/create'),
            'edit' => Pages\EditUserIdentifications::route('/{record}/edit'),
        ];
    }
}
