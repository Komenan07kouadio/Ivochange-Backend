<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory, HasUuids;

    // Définit la table associée au modèle
    protected $table = 'roles';

    // Définit les attributs pouvant être assignés en masse
    protected $fillable = [
        'name',
        'isActive',
        'isDeleted',
    ];

    // Relation entre Role et Autorisation (un rôle a plusieurs autorisations)
    public function autorisations()
    {
        return $this->hasMany(Autorisation::class);
    }
}
