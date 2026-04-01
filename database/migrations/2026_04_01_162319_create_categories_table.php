<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            // Clé primaire auto-incrémentée
            $table->id();

            // Clé étrangère vers users
            // foreignId() crée une colonne BIGINT UNSIGNED + index
            // constrained() ajoute la contrainte FOREIGN KEY vers users.id
            // Équivalent JPA : @ManyToOne + @JoinColumn(name = "user_id")
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Nom de la catégorie, ex: "Alimentation", "Transport"
            $table->string('nom');

            // Description optionnelle
            $table->text('description')->nullable();

            // Icône optionnelle (nom d'icône ou emoji)
            $table->string('icone')->nullable();

            // Marqueur pour la catégorie "Divers" créée auto à l'inscription
            // false par défaut, seule "Divers" aura true
            // Une catégorie is_default = true ne peut pas être supprimée
            $table->boolean('is_default')->default(false);

            // created_at + updated_at
            $table->timestamps();

            // deleted_at pour le Soft Delete
            // Équivalent Java : un champ @Column deletedAt + filtre global
            // Au lieu de DELETE FROM, Laravel met un timestamp dans deleted_at
            // Les requêtes normales ignorent automatiquement les lignes "supprimées"
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};