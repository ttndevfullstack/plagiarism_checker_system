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
    if (! $media = Media::find($mediaId)) { return null; }

    return asset('storage/' . $media->path);
}

function highlight_text_color(float $percent = 0): string
{
    if ($percent > 80) {
        return 'text-danger-600 dark:text-danger-400';
    } elseif ($percent > 60) {
        return 'text-pink-600 dark:text-pink-400';
    } elseif ($percent > 40) {
        return 'text-warning-600 dark:text-warning-400';
    } else {
        return 'text-success-600 dark:text-success-400';
    }
}

function highlight_text_background(float $percent = 0): string
{
    if ($percent > 80) {
        return 'bg-danger-100 dark:bg-danger-400 text-black';
    } elseif ($percent > 60) {
        return 'bg-pink-100 dark:bg-pink-400 text-black';
    } elseif ($percent > 40) {
        return 'bg-warning-100 dark:bg-warning-400 text-black';
    } else {
        return 'bg-success-100 dark:bg-success-400 text-black';
    }
}

function highlight_word_background(float $percent = 0): array
{
    $colors = [
        'danger'  => ['bgColor' => 'FFEBEE', 'color' => 'D32F2F'],  // Light red bg with dark red text
        'pink'    => ['bgColor' => 'FCE4EC', 'color' => 'C2185B'],  // Light pink bg with deep pink text
        'warning' => ['bgColor' => 'FFF8E1', 'color' => 'FF8F00'],  // Light yellow bg with amber text
        'success' => ['bgColor' => 'E8F5E9', 'color' => '2E7D32']   // Light green bg with dark green text
    ];

    if ($percent > 80) {
        return $colors['danger'];
    } elseif ($percent > 60) {
        return $colors['pink'];
    } elseif ($percent > 40) {
        return $colors['warning'];
    }
    
    return $colors['success'];
}
