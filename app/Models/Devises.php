<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Devises extends Model
{
    use HasFactory;
    protected $table = 'devises';

    protected $primaryKey = 'devise_id';

    protected $fillable = [
        'nom',
        'symbole',
        'reserve',
    ];

    public function Taux_echange()
    {
        return $this->hasMany(Taux_echange::class);
    }
}
