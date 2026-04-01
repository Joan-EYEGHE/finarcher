<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Table des portefeuilles (comptes) de l'utilisateur
     * Chaque portefeuille a une devise et un solde auto-calculé
     */
    public function up(): void
    {
        Schema::create('portefeuilles', function (Blueprint $table) {
            // Clé primaire
            $table->id();

            // Propriétaire du portefeuille
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Devise du portefeuille (ex: XOF, EUR)
            // Pointe vers la table devises
            $table->foreignId('devise_id')->constrained()->onDelete('cascade');

            // Nom du portefeuille, ex: "Compte principal", "Épargne"
            $table->string('nom');

            // Solde actuel, mis à jour automatiquement :
            // +montant quand un revenu est ajouté
            // -montant_total quand une dépense est ajoutée
            // decimal(15,2) = jusqu'à 15 chiffres dont 2 décimales
            // Équivalent Java : BigDecimal avec @Column(precision = 15, scale = 2)
            $table->decimal('solde', 15, 2)->default(0);

            // Icône optionnelle
            $table->string('icone')->nullable();

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
        Schema::dropIfExists('portefeuilles');
    }
};