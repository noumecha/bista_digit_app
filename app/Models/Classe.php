<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Classe extends Model
{
    use HasFactory;

    /**
     * @var array
     */
    protected $fillable = ['libClasse','cycleClasse','section_id'];

    /**
     * @var array
     */
    protected $casts = [];


    /**
     * Get the effectif for the current academic year.
     */
    public function effectif()
    {
        $activeYear = AnneeScolaire::where('statut', true)->first();

        return $this->hasOne(ClasseEffectif::class)
                    ->where('annee_scolaire_id', $activeYear->id);
    }

    /**
     * a class belongs to a specific section
     */
    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class, 'section_id');
    }

    /**
     * A class could have many disciplines
     */
    public function disciplines():HasMany
    {
        return $this->hasMany(Discipline::class);
    }


    /**
     * a class belongs to many ensMat relations
     */
    public function enseignantMatiere(): BelongsToMany
    {
        return $this->belongsToMany(EnseignantMatiereModel::class, 'enseignement');
    }

    /**
     * A classe have many epreuves
     */
    public function epreuves(): HasMany {
        return $this->hasMany(Epreuve::class);
    }

    /**
     * A classe must have many notes in many subjects
     */
    public function notes(): HasMany {
        return $this->hasMany(Note::class);
    }

    /**
     * A classe must have many bulletins
     */
    public function bulletins(): HasMany {
        return $this->hasMany(Bulletin::class);
    }

    /**
     * A user belongs to one classe in a year.
     * So then a classe have many students in a year
     */
    public function students()
    {
        return $this->belongsToMany(User::class, 'classe_annee_scolaire_students')
            ->withPivot('annee_scolaire_id')
            ->withTimestamps();
    }

    /**
     * get matieres for the classe
     */
    public function getMatieres() {
        $coefIds = CoefAnneeScolaire::where('annee_scolaire_id', getCurrentYear()->id)
            ->pluck('coefficient_id');
        $matiereIds = Coefficient::where('classe_id', $this->id)->whereIn('id', $coefIds)
            ->pluck('matiere_id');
        $matieres = Matiere::whereIn('id', $matiereIds)->get();
        return $matieres;
    }

    /**
     * get teachers of the classe
     */
    public function getTeachers() {
        $enseignementIds = Enseignement::where('classe_id', $this->id)->pluck('enseignant_matiere_id');
        $teacherIds = EnseignantMatiereModel::whereIn('id', $enseignementIds)->pluck('user_id');
        $teacherYearIds = UserAnneeScolaire::where('annee_scolaire_id', getCurrentYear()->id)
            ->whereIn('user_id', $teacherIds)
            ->pluck('user_id');
        $teachers = User::where('typeUser', 'enseignant')->whereIn('id', $teacherYearIds)->get();
        return $teachers;
    }


    /**
     * get student of a current years
     */
    public function getStudents() {
        $studentIds = ClasseAnneeScolaireStudent::where('classe_id', $this->id)
            ->where('annee_scolaire_id', getCurrentYear()->id)
            ->pluck('user_id');
        return User::where('typeUser', 'eleve')->whereIn('id', $studentIds)->get();
    }

    /**
     * A class has many devoirs
     */
    public function devoirs(): HasMany
    {
        return $this->hasMany(Devoir::class);
    }

}
