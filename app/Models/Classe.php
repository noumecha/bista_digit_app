<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Classe extends Model
{
    use HasFactory;

    /**
     * @var array
     */
    protected $fillable = ['libClasse','effectifClasse','cycleClasse'];

    /**
     * @var array
     */
    protected $casts = [];

    /**
     *
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
}
