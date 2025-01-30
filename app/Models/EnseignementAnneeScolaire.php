<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EnseignementAnneeScolaire extends Model
{
    use HasFactory;

    protected $fillable = [
        'enseignement_id',
        'annee_scolaire_id'
    ];
}
