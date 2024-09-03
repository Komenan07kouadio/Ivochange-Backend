<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory, HasUuids;

    // Définit la table associée au modèle
    protected $table = 'permissions';

    // Définit les attributs pouvant être assignés en masse
    protected $fillable = [
        'name',       // Nom de la permission
        'isActive',   // Indique si la permission est active
        'isDeleted',  // Indique si la permission est supprimée (soft delete)
    ];

    // Relation entre Permission et Autorisation (une permission a plusieurs autorisations)
    public function autorisations() 
    {
        return $this->hasMany(Autorisation::class);
    }
}
