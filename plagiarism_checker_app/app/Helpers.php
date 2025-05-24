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
    if ($percent > 85) {
        return 'text-danger-600 dark:text-danger-400';    // High Risk
    } elseif ($percent > 65) {
        return 'text-warning-600 dark:text-warning-400';  // Moderate Risk
    } elseif ($percent > 30) {
        return 'text-yellow-600 dark:text-yellow-400';    // Low Risk
    } else {
        return 'text-success-600 dark:text-success-400';  // Original
    }
}

function highlight_text_background(float $percent = 0): string
{
    if ($percent > 85) {
        return 'bg-exact-match text-black';     // High Risk (red)
    } elseif ($percent > 65) {
        return 'bg-paraphrased text-black';     // Moderate Risk (orange)
    } elseif ($percent > 30) {
        return 'bg-minor-match text-black';     // Low Risk (yellow)
    } else {
        return 'bg-original text-black';        // Original (green)
    }
}

function highlight_word_background(float $percent = 0): array
{
    $colors = [
        'high-risk' => ['bgColor' => 'FFEBEE', 'color' => 'C62828'],    // Red
        'moderate-risk' => ['bgColor' => 'FFF3E0', 'color' => 'EF6C00'], // Orange
        'low-risk' => ['bgColor' => 'FFF9C4', 'color' => 'F9A825'],     // Yellow
        'original' => ['bgColor' => 'E8F5E9', 'color' => '2E7D32']      // Green
    ];

    if ($percent > 85) {
        return $colors['high-risk'];
    } elseif ($percent > 65) {
        return $colors['moderate-risk'];
    } elseif ($percent > 30) {
        return $colors['low-risk'];
    }
    
    return $colors['original'];
}
