<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Depense extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'categorie_id',
        'portefeuille_id',
        'date_operation',
        'designation',
        'quantite',
        'prix',
        'montant_total',
    ];

    protected $casts = [
        'quantite' => 'decimal:2',
        'prix' => 'decimal:2',
        'montant_total' => 'decimal:2',
        'date_operation' => 'date',
    ];

    /**
     * Calcul automatique du montant_total avant chaque sauvegarde
     * boot() est appelé une seule fois quand Laravel charge le modèle
     * Équivalent JPA : @PrePersist + @PreUpdate
     */
    protected static function boot()
    {
        parent::boot();

        // "saving" se déclenche AVANT chaque create() et update()
        static::saving(function ($depense) {
            $depense->montant_total = $depense->quantite * $depense->prix;
        });
    }

    /**
     * La dépense appartient à un user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * La dépense a une catégorie (obligatoire)
     */
    public function categorie()
    {
        return $this->belongsTo(Categorie::class);
    }

    /**
     * La dépense est liée à un portefeuille (optionnel)
     * nullable = true côté migration, donc on peut avoir $depense->portefeuille === null
     */
    public function portefeuille()
    {
        return $this->belongsTo(Portefeuille::class);
    }
}