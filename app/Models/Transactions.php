<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transactions extends Model
{
    use HasFactory;

    protected $table = 'transactions';

    protected $primaryKey = 'id_transaction';

    protected $fillable = [
        'utilisateur_id',
        'montant_envoye',
        'numero_compte_envoye',
        'montant_reçu',
        'numero_compte_reçu',
        'devise_envoyee_id',
        'devise_recue_id',
        'montant_frais_inclus_envoye',
        'montant_frais_inclus_reçu',
        'statut',
    ];

    public function utilisateur()
    {
        return $this->belongsTo(Utilisateurs::class);
    }

    public function devise()
    {
        return $this->belongsTo(Devises::class);
    }
}
