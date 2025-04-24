<?php

namespace App\Policies;

use Awcodes\Curator\Models\Media;
use App\Models\User;

class MediaPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, Media $media): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, Media $media): bool
    {
        return $user->isAdmin();
    }

    public function deleteAny(User $user): bool
    {
        return $user->isAdmin();
    }

    public function download(User $user): bool
    {
        return $user->isAdmin();
    }
}