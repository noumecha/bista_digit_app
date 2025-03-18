<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Matiere extends Model
{
    use HasFactory;

    /**
     * @var array
     */
    protected $fillable = [
        'libelleMatiere',
        'codeMatiere',
    ];

    /**
     *
     */
    public function enseignants(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     *
     */
    public function attributions_matieres(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'enseignant_matiere','matiere_id','user_id');
    }

    /**
     *
     */
    public function epreuves(): HasMany {
        return $this->hasMany(Epreuve::class);
    }

    /**
     * A subject has many notes
     */
    public function notes(): HasMany {
        return $this->hasMany(Note::class);
    }

     /**
     * A matiere can believe to more than one devoir
     */
    public function devoirs():HasMany
    {
        return $this->hasMany(Devoir::class);
    }

    /**
     * gettting current matiere year classe coefficient value
     */
    public function getCoef($classeId) {
        $activeYear = AnneeScolaire::all()->where('statut','=', true)->first();
        $coef = Coefficient::where('matiere_id', $this->id)
            ->where('classe_id', $classeId)
            ->where('annee_scolaire_id', $activeYear->id)->first();
        $yearCoef = CoefAnneeScolaire::where('annee_scolaire_id', $activeYear->id)
        ->where('coefficient_id', $coef->id)->first();
        return $yearCoef->coefficient_value;
    }

    /**
     * getting the teacher who teach the matiere in the specified classe for the current year
     */
    public function getTeacher($classeId) {
        $activeYear = AnneeScolaire::all()->where('statut', true)->first();
        $teacherClasseIds = Enseignement::where('classe_id', $classeId)->pluck('enseignant_matiere_id');
        $teacherClassesIds = Enseignement::where('classe_id', $classeId)->pluck('id');
        $teacherClassesYearIds = EnseignementAnneeScolaire::where('annee_scolaire_id', $activeYear->id)
            ->whereIn('enseignement_id', $teacherClassesIds);
        $teacherMatiere = EnseignantMatiereModel::all()->where('matiere_id', $this->id)
            ->whereIn('id', $teacherClasseIds)
            ->whereIn('id', $teacherClassesYearIds);
        dd($teacherMatiere);
        return $teacherMatiere;
    }
}
