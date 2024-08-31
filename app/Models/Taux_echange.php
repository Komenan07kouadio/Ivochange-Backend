<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Taux_echange extends Model
{
    use HasFactory;

    // Définir la table associée (si le nom de la table n'est pas standard)
    protected $table = 'taux_echanges';

    // Définir la clé primaire (si elle n'est pas "id")
    protected $primaryKey = 'id_taux';

    // Les attributs qui sont assignables en masse
    protected $fillable = [
        'devise_id',
        'date_taux',
        'taux',
    ];

    public function devises()
    {
        return $this->belongsTo(Devises::class, 'devise_id');
    }
}
