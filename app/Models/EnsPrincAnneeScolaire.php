<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EnsPrincAnneeScolaire extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'annee_scolaire_id',
        'enseignant_principal_id'
    ];
}
