<?php

namespace App\Filament\Pages;

use Filament\Forms\Components\Component;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Auth\Register as BaseRegister;

class Register extends BaseRegister
{
    /**
     * @return array<int | string, string | Form>
     */
    protected function getForms(): array
    {
        return [
            'form' => $this->form(
                $this->makeForm()
                    ->schema([
                        $this->getTextFormComponent('first_name', __('First Name')),
                        $this->getTextFormComponent('last_name', __('Last Name')),
                        $this->getDatePickerFormComponent('dob', __('Dob')),
                        $this->getEmailFormComponent(),
                        $this->getPasswordFormComponent(),
                        $this->getPasswordConfirmationFormComponent(),
                    ])
                    ->statePath('data'),
            ),
        ];
    }

    protected function getTextFormComponent(string $field, string $label, bool $autoFocus = false): Component
    {
        return TextInput::make($field)
            ->label($label)
            ->required()
            ->maxLength(255)
            ->autofocus($autoFocus);
    }
    
    protected function getDatePickerFormComponent(string $field, string $label, string $format = 'd/m/Y'): Component
    {
        return DatePicker::make($field)
            ->label($label)
            ->format($format);
    }
}