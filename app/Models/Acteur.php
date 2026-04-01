<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Acteur extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'nom',
        'adresse',
        'numero',
    ];

    /**
     * L'acteur appartient à un user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Un acteur est lié à plusieurs revenus
     * Ex: "Mon employeur" a versé 3 salaires = 3 revenus
     */
    public function revenus()
    {
        return $this->hasMany(Revenu::class);
    }
}