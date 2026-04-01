<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Table des dépenses (sorties d'argent)
     * portefeuille_id est nullable (une dépense peut ne pas être liée à un compte)
     * montant_total = quantite × prix (calculé automatiquement côté PHP)
     */
    public function up(): void
    {
        Schema::create('depenses', function (Blueprint $table) {
            // Clé primaire
            $table->id();

            // Propriétaire de la dépense
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Catégorie obligatoire (default "Divers" géré côté PHP, pas en BDD)
            $table->foreignId('categorie_id')->constrained()->onDelete('cascade');

            // Portefeuille OPTIONNEL — une dépense peut exister sans compte
            // nullable() sur foreignId = la colonne accepte NULL
            // nullOnDelete() = si le portefeuille est supprimé, met NULL ici au lieu de supprimer la dépense
            // Équivalent JPA : @ManyToOne(optional = true) + @JoinColumn(nullable = true)
            $table->foreignId('portefeuille_id')->nullable()->constrained()->nullOnDelete();

            // Date de l'opération
            $table->date('date_operation');

            // Ce qui a été acheté, ex: "Riz 5kg", "Taxi aéroport"
            $table->string('designation');

            // Quantité achetée (default 1)
            // decimal(10,2) pour supporter les demi-unités (ex: 0.5 kg)
            $table->decimal('quantite', 10, 2)->default(1);

            // Prix unitaire
            $table->decimal('prix', 15, 2);

            // Montant total = quantite × prix
            // Calculé automatiquement côté PHP (dans le modèle/controller)
            // Stocké en BDD pour faciliter les requêtes de totaux
            $table->decimal('montant_total', 15, 2);

            // created_at + updated_at
            $table->timestamps();

            // Soft delete (deleted_at)
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('depenses');
    }
};