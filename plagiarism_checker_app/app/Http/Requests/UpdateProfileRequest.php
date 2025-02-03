<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rules\Password;

/**
 * @property-read string|null $current_password
 * @property-read string|null $new_password
 * @property-read string $name
 * @property-read string $email
 * @property-read string|null $avatar
 */
class UpdateProfileRequest extends Request
{
    /** @return array<mixed> */
    public function rules(): array
    {
        return [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users,email,' . auth()->user()->id,
            'current_password' => 'sometimes|required_with:new_password',
            'new_password' => ['sometimes', Password::defaults()],
        ];
    }
}
