<?php

use App\Models\User;

if (! function_exists('privilege_keys_for_user')) {
    /**
     * Returns the array of privilege key strings for the given user.
     * Called from /api/me so the React front end can drive conditional
     * rendering without ever knowing role names.
     */
    function privilege_keys_for_user(User $user): array
    {
        return $user->privilegeKeys();
    }
}

if (! function_exists('cover_image_url')) {
    /**
     * Resolves a stored cover image path to its public URL.
     * Centralises the storage-disk logic so callers never build paths manually.
     */
    function cover_image_url(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        return asset('storage/' . $path);
    }
}
