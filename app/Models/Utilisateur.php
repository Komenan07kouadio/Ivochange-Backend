<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Utilisateur extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'email',
        'mot_de_passe',
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

