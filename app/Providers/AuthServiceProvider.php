<?php

namespace App\Providers;

use App\Models\Categorie;
use App\Models\Portefeuille;
use App\Models\Acteur;
use App\Models\Revenu;
use App\Models\Depense;
use App\Policies\CategoriePolicy;
use App\Policies\PortefeuillePolicy;
use App\Policies\ActeurPolicy;
use App\Policies\RevenuPolicy;
use App\Policies\DepensePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * La correspondance Modèle → Policy
     * Laravel sait maintenant quelle Policy utiliser pour chaque modèle
     */
    protected $policies = [
        Categorie::class => CategoriePolicy::class,
        Portefeuille::class => PortefeuillePolicy::class,
        Acteur::class => ActeurPolicy::class,
        Revenu::class => RevenuPolicy::class,
        Depense::class => DepensePolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}