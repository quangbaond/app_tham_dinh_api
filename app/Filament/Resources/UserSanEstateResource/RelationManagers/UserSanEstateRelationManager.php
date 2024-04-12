<?php

namespace App\Filament\Resources\UserSanEstateResource\RelationManagers;

use App\Models\UserSanEstate;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
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
                        Forms\Components\FileUpload::make('imageMovables.image')->label('Hình ảnh')
                            ->directory('images/san_estates')
                            ->multiple()
                            ->previewable()
                            ->downloadable(true)
                            ->openable(true)
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
                    Tables\Actions\Action::make('kiểm tra')
                    ->requiresConfirmation()
                    ->action(fn(UserSanEstate $record) => $record->update(['check' => !$record['check']]))
                    ->modalHeading('Thẩm định')
                    ->modalDescription('Bạn có chắc chắn đã kiểm tra tài sản này?')
                    ->modalSubmitActionLabel('Đã kiểm tra')
                    ->icon('heroicon-o-check-circle')
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
