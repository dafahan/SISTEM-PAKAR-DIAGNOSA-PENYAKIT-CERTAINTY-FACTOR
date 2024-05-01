<?php

namespace App\Filament\Pages\Auth;

use Filament\Forms\Components\Radio;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Auth\EditProfile as BaseEditProfile;

class EditProfile extends BaseEditProfile
{
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                $this->getNameFormComponent(),
                $this->getEmailFormComponent(),
                Radio::make('gender')
                    ->required()
                    ->options([
                        'MALE' => 'Male',
                        'FEMALE' => 'Female',
                    ])
                    ->inline(),
                TextInput::make('age')
                    ->maxValue(200)
                    ->numeric()
                    ->minValue(1)
                    ->required(),
                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
            ]);
    }
}
