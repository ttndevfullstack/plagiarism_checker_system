<?php

/**
 * Convert data to lowercase dynamically.
 *
 * @param mixed $data
 * @return mixed
 */
function dynamic_lowercase($data)
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
 * @return string
 */
function generate_permission_name(string $module, string $resource, string $action): string
{
    return dynamic_lowercase("{$module}:{$resource}:{$action}");
}
