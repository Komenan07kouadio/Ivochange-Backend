<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; 

class Utilisateur extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'telephone',
        'mot_de_passe',
        'date_inscription',
        'statut',
    ];

    protected $hidden = [
        'mot_de_passe', 
    ];

    public function profils()
    {
        return $this->hasOne(Profil::class);
    }

    public function portefeuilles()
    {
        return $this->hasMany(Portefeuille::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function avis()
    {
        return $this->hasMany(Avis::class);
    }
}
