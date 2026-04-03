<?php

namespace App\Policies;

use App\Models\Categorie;
use App\Models\User;

class CategoriePolicy
{
    /**
     * Vérifie que la catégorie appartient au user
     * Équivalent Java : @PreAuthorize("@categorieService.isOwner(#id, principal)")
     */
    public function view(User $user, Categorie $categorie): bool
    {
        return $user->id === $categorie->user_id;
    }

    public function update(User $user, Categorie $categorie): bool
    {
        return $user->id === $categorie->user_id;
    }

    public function delete(User $user, Categorie $categorie): bool
    {
        return $user->id === $categorie->user_id;
    }
}