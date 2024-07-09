<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profil extends Model
{
    use HasFactory;

    protected $fillable = [
        'utilisateur_id',
        'nom',
        'prenom',
        'date_naissance',
        'adresse',
        'telephone',
    ];

    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class);
    }
}

