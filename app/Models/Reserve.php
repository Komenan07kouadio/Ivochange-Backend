<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reserve extends Model
{
    use HasFactory;

    protected $fillable = [
        'devise_id',
        'montant'
    ];

    public function devise()
    {
        return $this->belongsTo(Devise::class);
    }
}
