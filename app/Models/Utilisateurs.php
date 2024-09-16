<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable; // Import de la classe Authenticatable
use Laravel\Sanctum\HasApiTokens; // Import du trait HasApiTokens
use Illuminate\Notifications\Notifiable; // Import du trait Notifiable
use Spatie\Permission\Traits\HasRoles;

class Utilisateurs extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'nom',
        'prenoms',
        'telephone',
        'pays',
        'email',
        'mot_de_passe',
        'date_inscription',
        'statut',
    ];

    protected $hidden = [
        'mot_de_passe', 
    ];

    public function transactions()
    {
        return $this->hasMany(Transactions::class);
    }

    public function avis()
    {
        return $this->hasMany(Avis::class);
    }
}
