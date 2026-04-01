<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Devise extends Model
{
    use HasFactory;

    // $fillable = les champs qu'on peut remplir en masse
    // Équivalent Java : les champs acceptés dans le DTO
    // Sans ça, Devise::create([...]) refuserait d'écrire ces champs (protection mass assignment)
    protected $fillable = [
        'code',
        'symbole',
        'nom',
    ];

    /**
     * Une devise est utilisée par plusieurs portefeuilles
     * Équivalent JPA : @OneToMany(mappedBy = "devise")
     */
    public function portefeuilles()
    {
        return $this->hasMany(Portefeuille::class);
    }
}