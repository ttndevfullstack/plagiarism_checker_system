<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class UserService
{
    public function __construct(private readonly FileManager $fileManager) {}

    public function updateUser(
        User $user,
        ?string $firstName,
        ?string $lastName,
        ?string $email,
        ?string $password,
        ?bool $isAdmin,
        ?TemporaryUploadedFile $avatar,
    ): User {
        $updateData = [
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $email,
            'is_admin' => $isAdmin ?? $user->is_admin,
        ];

        if ($password) {
            $updateData['password'] = Hash::make($password);
        }

        if ($avatar) {
            $updateData['avatar'] = $this->createNewAvatar($avatar, $user);
        }

        $user->update($updateData);

        return $user;
    }

    private function createNewAvatar(TemporaryUploadedFile $avatar, User $user): string
    {
        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        if (Str::startsWith($avatar->getFilename(), ['http://', 'https://'])) {
            return $avatar->getFilename();
        }

        $path = $this->fileManager->upload(
            $avatar, 
            'public/' . user_avatar_dir(), 
            user_avatar_file()
        );

        return str_replace('public/', '', $path);
    }
}
