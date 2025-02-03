<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class ProfileUpdateRequest extends FormRequest
{
    /**
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . auth()->user()->id,
            'current_password' => 'sometimes|required_with:new_password',
            'new_password' => ['sometimes', Password::defaults()],
            'avatar' => 'nullable|url',
        ];
    }
}
