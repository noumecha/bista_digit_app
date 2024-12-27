<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FonctionAnneeScolaireUser extends Model
{
    /**
     * helps to allow mass assignation
     * @var array
     */
    protected $fillable = [
        'fonction_id',
        'user_id',
        'annee_scolaire_id'
    ];
}
