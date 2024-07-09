<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'utilisateur_id',
        'portefeuille_id',
        'montant',
        'devise_id',
        'type',
    ];

    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class);
    }

    public function portefeuille()
    {
        return $this->belongsTo(Portefeuille::class);
    }

    public function devise()
    {
        return $this->belongsTo(Devise::class);
    }
}
