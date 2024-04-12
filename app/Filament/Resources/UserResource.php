<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserMovableResoucrceResource\RelationManagers\UserMovableRelationManager;
use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
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
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Họ và tên')
                            ->required(),
                        Forms\Components\TextInput::make('id_card')
                            ->label('CCCD')
                            ->required(),
                        // file upload
                        Forms\Components\FileUpload::make('image_front')
                            ->directory('images/cccd')
                            ->label('Ảnh mặt trước CCCD')
                            ->required(),
                        Forms\Components\FileUpload::make('image_back')
                            ->directory('images/cccd')
                            ->label('Ảnh mặt sau CCCD')
                    ])->columns(2),
                    
                    // userMovables is hasMany relation
                // Forms\Components\Section::make('Thông tin tải sản')
                //     ->relationship('userMovables')
                //     ->schema([
                //         Forms\Components\TextInput::make('dia_chi')
                //             ->label('Họ và tên')
                //             ->required(),
                //     ])->columns(2),
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
            UserMovableRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
            'check' => Pages\EditUsers::route('/{record}/check'),
        ];
    }
}
