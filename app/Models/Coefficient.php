<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coefficient extends Model
{
    use HasFactory;

    /**
     * @var array
     */
    protected $fillable = [
        'classe_id',
        'matiere_id',
        'annee_scolaire_id'
    ];

    /**
     *
     */
    public function coefAnneeScolaire() {
        return $this->hasMany(CoefAnneeScolaire::class, 'coefficient_id');
    }

    /**
     *
     */
    public function classe() {
        return $this->belongsTo(Classe::class, 'classe_id');
    }

    /**
     *
     */
    public function matiere() {
        return $this->belongsTo(Matiere::class, 'matiere_id');
    }

    /**
     * get coefficient_value for a specific configuration
     */
    public function getCoefficient() {
        $coefYear = CoefAnneeScolaire::where('annee_scolaire_id', $this->annee_scolaire_id)
        ->where('coefficient_id', $this->id)->first();
        return $coefYear->coefficient_value;
    }

    /**
     * get groupe_matiere value for a specific configuration
     */
    public function getGroupeMatiere() {
        $coefYear = CoefAnneeScolaire::where('annee_scolaire_id', $this->annee_scolaire_id)
        ->where('coefficient_id', $this->id)->first();
        return $coefYear->groupe_matiere;
    }

}
