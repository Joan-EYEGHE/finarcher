<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Table des acteurs (contacts) liés aux revenus
     * Ex: "Employeur", "Client X", "Famille"
     */
    public function up(): void
    {
        Schema::create('acteurs', function (Blueprint $table) {
            // Clé primaire
            $table->id();

            // Propriétaire de l'acteur
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Nom du contact, ex: "Mon employeur", "Client Dupont"
            $table->string('nom');

            // Adresse optionnelle
            $table->string('adresse')->nullable();

            // Numéro de téléphone optionnel
            $table->string('numero')->nullable();

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
        Schema::dropIfExists('acteurs');
    }
};