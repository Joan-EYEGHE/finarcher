<?php

namespace App\Policies;

use App\Models\Portefeuille;
use App\Models\User;

class PortefeuillePolicy
{
    public function view(User $user, Portefeuille $portefeuille): bool
    {
        return $user->id === $portefeuille->user_id;
    }

    public function update(User $user, Portefeuille $portefeuille): bool
    {
        return $user->id === $portefeuille->user_id;
    }

    public function delete(User $user, Portefeuille $portefeuille): bool
    {
        return $user->id === $portefeuille->user_id;
    }
}