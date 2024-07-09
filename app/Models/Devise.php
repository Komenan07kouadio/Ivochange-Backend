<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Devise extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'nom',
        'symbole',
    ];

    public function portefeuilles()
    {
        return $this->hasMany(Portefeuille::class);
    }

    public function tauxEchanges()
    {
        return $this->hasMany(TauxEchange::class);
    }
}

