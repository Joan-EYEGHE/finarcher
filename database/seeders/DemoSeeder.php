<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Categorie;
use App\Models\Portefeuille;
use App\Models\Acteur;
use App\Models\Revenu;
use App\Models\Depense;
use App\Models\Devise;
use Illuminate\Database\Seeder;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Récupère la devise FCFA
        $xof = Devise::where('code', 'XOF')->first();

        // 2. Crée un utilisateur de démo
        $user = User::create([
            'name' => 'Utilisateur Démo',
            'email' => 'demo@finarcher.com',
            'numero' => '770000000',
            'password' => bcrypt('password'),
        ]);

        // 3. Catégorie par défaut "Divers" (créée automatiquement normalement, mais on s'assure)
        $divers = Categorie::create([
            'user_id' => $user->id,
            'nom' => 'Divers',
            'description' => 'Catégorie par défaut',
            'is_default' => true,
        ]);

        // 4. Catégories supplémentaires
        $alimentation = Categorie::create([
            'user_id' => $user->id,
            'nom' => 'Alimentation',
            'description' => 'Courses et repas',
        ]);

        $transport = Categorie::create([
            'user_id' => $user->id,
            'nom' => 'Transport',
            'description' => 'Taxi, bus, carburant',
        ]);

        $loisirs = Categorie::create([
            'user_id' => $user->id,
            'nom' => 'Loisirs',
            'description' => 'Sorties et divertissement',
        ]);

        // 5. Portefeuilles
        $comptePrincipal = Portefeuille::create([
            'user_id' => $user->id,
            'devise_id' => $xof->id,
            'nom' => 'Compte principal',
            'solde' => 0,
        ]);

        $epargne = Portefeuille::create([
            'user_id' => $user->id,
            'devise_id' => $xof->id,
            'nom' => 'Épargne',
            'solde' => 0,
        ]);

        // 6. Acteurs (contacts)
        $employeur = Acteur::create([
            'user_id' => $user->id,
            'nom' => 'Mon Employeur',
            'adresse' => 'Dakar, Plateau',
            'numero' => '338000000',
        ]);

        $freelance = Acteur::create([
            'user_id' => $user->id,
            'nom' => 'Client Freelance',
            'numero' => '771234567',
        ]);

        // 7. Revenus (le solde est mis à jour manuellement ici)
        Revenu::create([
            'user_id' => $user->id,
            'portefeuille_id' => $comptePrincipal->id,
            'acteur_id' => $employeur->id,
            'date_operation' => '2026-03-01',
            'motif' => 'Salaire mars',
            'montant' => 350000,
        ]);
        $comptePrincipal->solde += 350000;

        Revenu::create([
            'user_id' => $user->id,
            'portefeuille_id' => $comptePrincipal->id,
            'acteur_id' => $freelance->id,
            'date_operation' => '2026-03-15',
            'motif' => 'Mission site web',
            'montant' => 75000,
        ]);
        $comptePrincipal->solde += 75000;

        Revenu::create([
            'user_id' => $user->id,
            'portefeuille_id' => $epargne->id,
            'acteur_id' => $employeur->id,
            'date_operation' => '2026-03-05',
            'motif' => 'Virement épargne',
            'montant' => 50000,
        ]);
        $epargne->solde += 50000;

        // 8. Dépenses
        $depenses = [
            [
                'categorie_id' => $alimentation->id,
                'portefeuille_id' => $comptePrincipal->id,
                'date_operation' => '2026-03-02',
                'designation' => 'Courses marché Sandaga',
                'quantite' => 1,
                'prix' => 15000,
                'montant_total' => 15000,
            ],
            [
                'categorie_id' => $transport->id,
                'portefeuille_id' => $comptePrincipal->id,
                'date_operation' => '2026-03-03',
                'designation' => 'Taxi bureau',
                'quantite' => 2,
                'prix' => 2500,
                'montant_total' => 5000,
            ],
            [
                'categorie_id' => $alimentation->id,
                'portefeuille_id' => $comptePrincipal->id,
                'date_operation' => '2026-03-10',
                'designation' => 'Riz 25kg',
                'quantite' => 1,
                'prix' => 12000,
                'montant_total' => 12000,
            ],
            [
                'categorie_id' => $loisirs->id,
                'portefeuille_id' => $comptePrincipal->id,
                'date_operation' => '2026-03-20',
                'designation' => 'Sortie restaurant',
                'quantite' => 1,
                'prix' => 8500,
                'montant_total' => 8500,
            ],
            [
                'categorie_id' => $divers->id,
                'portefeuille_id' => null,
                'date_operation' => '2026-03-25',
                'designation' => 'Cadeau anniversaire',
                'quantite' => 1,
                'prix' => 20000,
                'montant_total' => 20000,
            ],
        ];

        foreach ($depenses as $depense) {
            Depense::create(array_merge($depense, ['user_id' => $user->id]));

            // Met à jour le solde si un portefeuille est lié
            if ($depense['portefeuille_id']) {
                $comptePrincipal->solde -= $depense['montant_total'];
            }
        }

        // 9. Sauvegarde les soldes finaux
        $comptePrincipal->save();
        $epargne->save();

        // Résultat attendu :
        // Compte principal : 350000 + 75000 - 15000 - 5000 - 12000 - 8500 = 384500 FCFA
        // Épargne : 50000 FCFA
    }
}