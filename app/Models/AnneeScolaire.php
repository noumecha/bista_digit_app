<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AnneeScolaire extends Model
{
    use HasFactory;

    /**
     * @var array
     */
    protected $fillable = [
        'libelleAnneeScolaire',
        'statut',
        'dateDeDebut',
        'dateDeFin',
    ];

    /**
     *
     */
    public function trimestres(): HasMany {
        return $this->hasMany(Trimestre::class);
    }

    /**
     * A year can have more than one user
     */
    public function users() {
        return $this->belongsToMany(User::class);
    }

    /**
     * a user can have different fonction throw different shool year
     */
    public function fonctionAnneeScolaire() {
        return $this->belongsToMany(FonctionAnneeScolaireUser::class);
    }

    /**
     * In a year, A teacher can teach many subjects.
     */
    public function anneeScolaires()
    {
        return $this->belongsToMany(AnneeScolaire::class, 'ens_mat_annee_scolaires')
            ->withPivot('')
            ->withTimestamps();
    }
}
