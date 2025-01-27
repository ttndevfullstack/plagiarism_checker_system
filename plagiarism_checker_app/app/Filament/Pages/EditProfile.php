<?php

namespace App\Filament\Pages;

use Filament\Forms\Components\Component;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Auth\EditProfile as BaseEditProfile;

class EditProfile extends BaseEditProfile
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
                        $this->getAvatarFormComponent(),
                        $this->getTextFormComponent('first_name', __('First Name'), true),
                        $this->getTextFormComponent('last_name', __('Last Name')),
                        $this->getEmailFormComponent(),
                        $this->getPasswordFormComponent(),
                        $this->getPasswordConfirmationFormComponent(),
                    ])
                    ->operation('edit')
                    ->model($this->getUser())
                    ->statePath('data')
                    ->inlineLabel(! static::isSimple()),
            ),
        ];
    }

    protected function getTextFormComponent(string $id, string $label, bool $autoFocus = false): Component
    {
        return TextInput::make($id)
            ->label($label)
            ->required()
            ->maxLength(255)
            ->autofocus($autoFocus);
    }

    protected function getAvatarFormComponent(): Component
    {
        return FileUpload::make('avatar')
            ->label(__('Profile Picture'))
            ->directory('avatars')
            ->image()
            ->imagePreviewHeight(100)
            ->imageResizeMode('cover')
            ->imageResizeTargetWidth(300)
            ->imageResizeTargetHeight(300);
    }
}
