<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Devises extends Model
{
    use HasFactory;
    protected $primaryKey = 'devise_id';
    public $incrementing = true;
    protected $keyType = 'int';

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
