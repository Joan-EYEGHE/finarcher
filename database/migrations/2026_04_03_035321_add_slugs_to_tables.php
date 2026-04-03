<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ajoute une colonne slug à chaque table qui apparaît dans les URLs
     * Le slug est un texte unique qui remplace l'ID numérique dans l'URL
     * Ex: "courses-marche-sandaga" au lieu de "6"
     */
    public function up(): void
    {
        // On ajoute le slug à chaque table dont les données apparaissent dans les URLs
        $tables = ['categories', 'portefeuilles', 'acteurs', 'revenus', 'depenses'];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                // unique() = deux enregistrements ne peuvent pas avoir le même slug
                // after('id') = place la colonne juste après id
                $table->string('slug')->unique()->after('id');
            });
        }
    }

    public function down(): void
    {
        $tables = ['categories', 'portefeuilles', 'acteurs', 'revenus', 'depenses'];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->dropColumn('slug');
            });
        }
    }
};