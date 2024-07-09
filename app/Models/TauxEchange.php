<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TauxEchange extends Model
{
    use HasFactory;

    protected $fillable = [
        'devise_source',
        'devise_cible',
        'taux',
    ];

    public function deviseSource()
    {
        return $this->belongsTo(Devise::class, 'devise_source');
    }

    public function deviseCible()
    {
        return $this->belongsTo(Devise::class, 'devise_cible');
    }
}

