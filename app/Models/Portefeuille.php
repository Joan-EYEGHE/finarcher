<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Portefeuille extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'devise_id',
        'nom',
        'solde',
        'icone',
    ];

    // Cast : solde sera toujours un float PHP, pas une string
    // Important pour les calculs de solde (revenu +, dépense -)
    protected $casts = [
        'solde' => 'decimal:2',
    ];

    /**
     * Le portefeuille appartient à un user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Le portefeuille a une devise
     * Équivalent JPA : @ManyToOne + @JoinColumn(name = "devise_id")
     * Ex: $portefeuille->devise->symbole retourne "FCFA"
     */
    public function devise()
    {
        return $this->belongsTo(Devise::class);
    }

    /**
     * Un portefeuille a plusieurs revenus
     */
    public function revenus()
    {
        return $this->hasMany(Revenu::class);
    }

    /**
     * Un portefeuille a plusieurs dépenses
     */
    public function depenses()
    {
        return $this->hasMany(Depense::class);
    }
}