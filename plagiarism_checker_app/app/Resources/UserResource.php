<?php

namespace App\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public const JSON_STRUCTURE = [
        'id',
        'full_name',
        'first_name',
        'last_name',
        'email',
        'is_admin',
        'created_at',
        'updated_at',
    ];

    public function __construct(private readonly User $user)
    {
        parent::__construct($user);
    }

    /** @return array<mixed> */
    public function toArray(Request $request): array
    {
        return [
            'type' => 'users',
            'id' => $this->id,
            'full_name' => $this->full_name,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'is_admin' => $this->is_admin,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
