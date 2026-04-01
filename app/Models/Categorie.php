<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Categorie extends Model
{
    // SoftDeletes = au lieu de DELETE, Laravel met un timestamp dans deleted_at
    // Équivalent Java : un filtre @Where("deleted_at IS NULL") global sur l'entité
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'nom',
        'description',
        'icone',
        'is_default',
    ];

    // Cast : Laravel convertira automatiquement is_default en true/false PHP
    // Sans ça, tu recevrais "0" ou "1" (string) depuis MySQL
    protected $casts = [
        'is_default' => 'boolean',
    ];

    /**
     * La catégorie appartient à un user
     * Équivalent JPA : @ManyToOne + @JoinColumn(name = "user_id")
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Une catégorie a plusieurs dépenses
     * Équivalent JPA : @OneToMany(mappedBy = "categorie")
     */
    public function depenses()
    {
        return $this->hasMany(Depense::class);
    }
}