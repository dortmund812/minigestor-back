<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Solo los admin pueden crear usuarios vía /users.
     */
    public function create(User $user): bool
    {
        return $user->role === User::ROLE_ADMIN;
    }
}
