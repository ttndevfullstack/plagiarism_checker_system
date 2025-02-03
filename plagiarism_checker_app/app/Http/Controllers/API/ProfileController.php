<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use App\Resources\UserResource;
use App\Services\UserService;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Hashing\Hasher;

class ProfileController extends Controller
{
    /** @param User $user */
    public function __construct(
        private readonly Hasher $hash,
        private readonly UserService $userService,
        // private readonly TokenManager $tokenManager,
        private readonly ?Authenticatable $user
    ) {}

    public function show(): UserResource
    {
        return UserResource::make($this->user);
    }

    public function update(ProfileUpdateRequest $request): UserResource
    {
        return UserResource::make(
            $this->userService->updateUser(
                user: $this->user,
                firstName: $request->firstName,
                lastName: $request->lastName,
                email: $request->email,
                password: $request->password,
                isAdmin: $request->isAdmin,
                avatar: $request->avatar,
            ),
        );
    }
}
