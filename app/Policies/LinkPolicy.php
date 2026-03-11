<?php

namespace App\Policies;

use App\Models\Link;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class LinkPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Link $link): bool
    {
        // Owner can view
        if ($link->user_id === $user->id) {
            return true;
        }

        // Users with read permission can view
        return $link->sharedWith()->where('user_id', $user->id)->where('permission', 'read')->exists() ||
               $link->sharedWith()->where('user_id', $user->id)->where('permission', 'write')->exists();
    }

    public function create(User $user): bool
    {
        // All authenticated users can create links
        return true;
    }

    public function update(User $user, Link $link): bool
    {
        // Owner can update
        if ($link->user_id === $user->id) {
            return true;
        }

        // Users with write permission can update
        return $link->sharedWith()->where('user_id', $user->id)->where('permission', 'write')->exists();
    }

    public function delete(User $user, Link $link): bool
    {
        // Only owner can delete
        return $link->user_id === $user->id;
    }

    public function share(User $user, Link $link): bool
    {
        // Only owner can share
        return $link->user_id === $user->id;
    }

    public function restore(User $user, Link $link): bool
    {
        // Only owner or admin can restore
        return $link->user_id === $user->id || $user->hasRole('admin');
    }

    public function forceDelete(User $user, Link $link): bool
    {
        // Only admin can force delete
        return $user->hasRole('admin');
    }
}
