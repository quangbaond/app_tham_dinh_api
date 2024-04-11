<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\User;
use Filament\Forms\Contracts\HasForms;
use Filament\Resources\Pages\Page;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Form;
use Filament\Forms;

class EditUsers extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = UserResource::class;

    protected static string $view = 'filament.resources.user-resource.pages.edit-users';

    protected static ?string $title = 'Thầm định';

    public $record = [];

    public function mount(): void
    {
        $id = request()->route('record');
        $user = User::find($id)->load('userIdentifications')->toArray();
        $this->record = $user;
        $this->form->fill($user);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Thông tin người dùng')
                    ->schema([
                        Forms\Components\TextInput::make('userIdentifications.name')
                            ->label('Họ và tên')
                            ->required(),
                        Forms\Components\TextInput::make('phone')
                            ->label('Số điện thoại1')
                            ->required(),
                        Forms\Components\TextInput::make('userIdentifications.id_card')
                            ->label('CCCD')
                            ->required(),
                        Forms\Components\Select::make('status_1')
                            ->label('Trạng thái')
                            ->options([
                                0 => 'Chưa thẩm định',
                                1 => 'Đã thẩm định',
                            ])
                            ->required(),
                    ]),
            ]);
    }


    // edit page

}
