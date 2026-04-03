<?php

namespace App\Policies;

use App\Models\Acteur;
use App\Models\User;

class ActeurPolicy
{
    public function view(User $user, Acteur $acteur): bool
    {
        return $user->id === $acteur->user_id;
    }

    public function update(User $user, Acteur $acteur): bool
    {
        return $user->id === $acteur->user_id;
    }

    public function delete(User $user, Acteur $acteur): bool
    {
        return $user->id === $acteur->user_id;
    }
}