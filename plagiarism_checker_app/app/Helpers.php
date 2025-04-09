<?php

use App\Models\User;
use Awcodes\Curator\Models\Media;

/**
 * Convert data to lowercase dynamically.
 *
 * @param mixed $data
 */
function dynamic_lowercase($data): mixed
{
    if (is_string($data)) {
        return mb_strtolower($data);
    }

    if (is_array($data)) {
        return array_map('dynamicLowercase', $data);
    }

    if (is_object($data)) {
        foreach ($data as $key => $value) {
            $data->{$key} = dynamicLowercase($value);
        }

        return $data;
    }

    return $data;
}

/**
 * Generate a permission name based on module, resource, and action.
 *
 * @param string $module
 * @param string $resource
 * @param string $action
 */
function generate_permission_name(string $module, string $resource, string $action): string
{
    return dynamic_lowercase("{$module}:{$resource}:{$action}");
}

function user_avatar_dir(): ?string
{
    return config('plagiarism-checker.user_avatar_dir');
}

function user_avatar_file(): ?string
{
    return sprintf('avatar_%s_%s.webp', sha1(Str::uuid()), sha1(time()));
}

function user_avatar_path(User $user): ?string
{
    return $user->avatar ? url('public/storage/' . $user->avatar) : null;
}

function media_path(string $mediaPath): string
{
    return asset("storage/$mediaPath");
}

function media_path_by_id(int|string $mediaId): ?string
{
    $media = Media::find($mediaId);
    if (!$media) {
        return null;
    }

    return asset('storage/' . Media::find($mediaId)->path);
}
