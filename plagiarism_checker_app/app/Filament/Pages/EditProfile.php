<?php

namespace App\Filament\Pages;

use App\Services\UserService;
use Filament\Forms\Get;
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
                        TextInput::make('role_name')
                            ->label(__('Role Name'))
                            ->disabled()
                            ->dehydrated(false)
                            ->afterStateHydrated(function (TextInput $component, $state) {
                                $component->state(auth()->user()->getRoleNames()->first());
                            }),
                        $this->getPasswordFormComponent()
                            ->make('current_password')
                            ->label(__('Current password')),
                        $this->getPasswordFormComponent()
                            ->make('new_password')
                            ->label(__('New password')),
                        $this->getPasswordConfirmationFormComponent()
                            ->visible(static fn (Get $get): bool => filled($get('new_password'))),
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
            ->label('')
            ->image()
            ->avatar()
            ->columns(2)
            ->openable()
            ->alignCenter()
            ->imageEditor()
            ->downloadable()
            ->storeFiles(false)
            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/gif'])
            ->default(static fn () => asset('storage/' . auth()->user()->avatar));
    }

    /**
     * @return array<string, mixed>
     * */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (isset($data['avatar'])) {
            $avatarFile = $data['avatar'];

            $avatarPath = app(UserService::class)->updateUser(
                user: auth()->user(),
                firstName: $data['first_name'],
                lastName: $data['last_name'],
                email: $data['email'],
                password: $data['new_password'],
                isAdmin: $data['is_admin'] ?? null,
                avatar: $avatarFile
            )->avatar;

            $data['avatar'] = $avatarPath;
        }

        return $data;
    }
}
