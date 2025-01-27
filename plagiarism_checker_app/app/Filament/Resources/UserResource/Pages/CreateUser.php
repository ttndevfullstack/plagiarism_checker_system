<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function handleRecordCreation(array $data): User
    {
        $user = User::create($data);

        if (isset($data['role'])) {
            $user->syncRoles($data['role']);
            $user->save();
        }

        return $user;
    }
}
