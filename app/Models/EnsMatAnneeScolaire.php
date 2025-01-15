<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EnsMatAnneeScolaire extends Model
{
   /**
     * helps to allow mass assignation
     * @var array
     */
    protected $fillable = [
        'enseignant_matiere_models_id',
        'annee_scolaire_id'
    ];
}
