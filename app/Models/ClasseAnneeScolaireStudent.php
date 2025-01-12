<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClasseAnneeScolaireStudent extends Model
{
    /**
     * helps to allow mass assignation
     * @var array
     */
    protected $fillable = [
        'classe_id',
        'user_id',
        'annee_scolaire_id'
    ];
}
