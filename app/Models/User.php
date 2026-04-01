<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'numero',
        'password',
    ];

    // Champs cachés quand le modèle est converti en JSON/tableau
    // Équivalent Java : @JsonIgnore sur un champ
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Cast automatique : quand Laravel lit ces champs, il les convertit au bon type PHP
    // Équivalent Java : un converter JPA ou @Temporal
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Un user a plusieurs catégories
     * Équivalent JPA : @OneToMany(mappedBy = "user")
     */
    public function categories()
    {
        return $this->hasMany(Categorie::class);
    }

    /**
     * Un user a plusieurs portefeuilles
     */
    public function portefeuilles()
    {
        return $this->hasMany(Portefeuille::class);
    }

    /**
     * Un user a plusieurs acteurs (contacts)
     */
    public function acteurs()
    {
        return $this->hasMany(Acteur::class);
    }

    /**
     * Un user a plusieurs revenus
     */
    public function revenus()
    {
        return $this->hasMany(Revenu::class);
    }

    /**
     * Un user a plusieurs dépenses
     */
    public function depenses()
    {
        return $this->hasMany(Depense::class);
    }
}