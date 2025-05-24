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
    if ($percent >= 95) {
        return 'bg-red-100 dark:bg-red-400 text-black';  // Exact matches
    } elseif ($percent >= 70) {
        return 'bg-orange-100 dark:bg-orange-400 text-black';  // Paraphrased
    } elseif ($percent >= 30) {
        return 'bg-purple-100 dark:bg-purple-400 text-black';  // Minor matches
    } else {
        return 'bg-green-100 dark:bg-green-400 text-black';  // Original
    }
}

function highlight_word_background(float $percent = 0): array
{
    $colors = [
        'exact' => ['bgColor' => 'FFEBEE', 'color' => 'D32F2F'],  // Light red bg with dark red text (95%+)
        'paraphrased' => ['bgColor' => 'FFE0B2', 'color' => 'EF6C00'],  // Light orange bg with dark orange text (70-95%)
        'minor' => ['bgColor' => 'E1BEE7', 'color' => '7B1FA2'],  // Light purple bg with dark purple text (30-70%)
        'original' => ['bgColor' => 'E8F5E9', 'color' => '2E7D32']  // Light green bg with dark green text (<30%)
    ];

    if ($percent >= 95) {
        return $colors['exact'];
    } elseif ($percent >= 70) {
        return $colors['paraphrased'];
    } elseif ($percent >= 30) {
        return $colors['minor'];
    }
    
    return $colors['original'];
}
