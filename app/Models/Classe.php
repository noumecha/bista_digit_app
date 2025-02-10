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
     * a class belongs to many ensMat relations
     */
    public function enseignantMatiere(): BelongsToMany
    {
        return $this->belongsToMany(EnseignantMatiereModel::class, 'enseignement');
    }

    /**
     *
     */
    public function epreuves(): HasMany {
        return $this->hasMany(Epreuve::class);
    }

    /**
     *
     */
    public function notes(): HasMany {
        return $this->hasMany(Note::class);
    }

    /**
     * A user belongs to one classe in a year.
     */
    public function students()
    {
        return $this->belongsToMany(User::class, 'classe_annee_scolaire_students')
            ->withPivot('annee_scolaire_id')
            ->withTimestamps();
    }

    /**
     * A class has many devoirs
     */
    public function devoirs(): HasMany
    {
        return $this->hasMany(Devoir::class);
    }
}
