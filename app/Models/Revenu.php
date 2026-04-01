<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Revenu extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'portefeuille_id',
        'acteur_id',
        'date_operation',
        'motif',
        'montant',
    ];

    protected $casts = [
        'montant' => 'decimal:2',
        'date_operation' => 'date',
    ];

    /**
     * Le revenu appartient à un user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Le revenu arrive dans un portefeuille
     * Ex: $revenu->portefeuille->nom retourne "Compte principal"
     */
    public function portefeuille()
    {
        return $this->belongsTo(Portefeuille::class);
    }

    /**
     * Le revenu vient d'un acteur (contact)
     * Ex: $revenu->acteur->nom retourne "Mon employeur"
     */
    public function acteur()
    {
        return $this->belongsTo(Acteur::class);
    }
}