<?php

namespace App\Traits;

use Illuminate\Support\Str;

/**
 * Trait qui génère automatiquement un slug unique à la création
 * 
 * Un slug c'est un texte URL-friendly généré à partir d'un champ du modèle
 * Ex: "Courses marché Sandaga" → "courses-marche-sandaga"
 * 
 * Équivalent Java : une méthode @PrePersist qui génère un identifiant texte
 */
trait HasSlug
{
    /**
     * boot automatique du trait (Laravel appelle bootHasSlug quand le modèle charge)
     * "creating" = événement déclenché AVANT l'insertion en base
     */
    protected static function bootHasSlug(): void
    {
        static::creating(function ($model) {
            // Str::slug() transforme "Courses marché Sandaga" en "courses-marche-sandaga"
            // slugSource() retourne le nom du champ à utiliser (défini dans chaque modèle)
            $baseSlug = Str::slug($model->{$model->slugSource()});

            // Si le slug est vide (champ source vide), on génère un texte aléatoire
            if (empty($baseSlug)) {
                $baseSlug = Str::random(8);
            }

            // Vérifie l'unicité : si "courses-marche-sandaga" existe déjà,
            // essaie "courses-marche-sandaga-1", puis "-2", etc.
            $slug = $baseSlug;
            $count = 1;
            while (static::withTrashed()->where('slug', $slug)->exists()) {
                $slug = $baseSlug . '-' . $count;
                $count++;
            }

            $model->slug = $slug;
        });
    }

    /**
     * Dit à Laravel d'utiliser le slug au lieu de l'id pour les routes
     * Quand une URL contient {categorie}, Laravel cherchera par slug et non par id
     * Équivalent Java : changer le @PathVariable de Long id à String slug
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Chaque modèle définit quel champ utiliser pour générer le slug
     * Par défaut c'est "nom", mais on peut override dans chaque modèle
     */
    public function slugSource(): string
    {
        return 'nom';
    }
}