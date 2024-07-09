<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Portefeuille extends Model
{
    use HasFactory;

    protected $fillable = [
        'utilisateur_id',
        'devise_id',
        'solde',
    ];

    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class);
    }

    public function devise()
    {
        return $this->belongsTo(Devise::class);
    }
}

