<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Table des revenus (entrées d'argent)
     * Chaque revenu est lié à un portefeuille (obligatoire) et un acteur (obligatoire)
     * Le solde du portefeuille sera mis à jour automatiquement (+montant)
     */
    public function up(): void
    {
        Schema::create('revenus', function (Blueprint $table) {
            // Clé primaire
            $table->id();

            // Propriétaire du revenu
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Dans quel portefeuille l'argent arrive (obligatoire)
            $table->foreignId('portefeuille_id')->constrained()->onDelete('cascade');

            // De qui vient l'argent (obligatoire)
            $table->foreignId('acteur_id')->constrained()->onDelete('cascade');

            // Date de l'opération (pas forcément aujourd'hui)
            // date() = type DATE en MySQL (sans heure), ex: "2026-04-01"
            // Équivalent Java : LocalDate
            $table->date('date_operation');

            // Motif optionnel, ex: "Salaire mars", "Remboursement"
            $table->string('motif')->nullable();

            // Montant reçu
            $table->decimal('montant', 15, 2);

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
        Schema::dropIfExists('revenus');
    }
};