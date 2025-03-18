<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class EnseignantMatiereModel extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'matiere_id',
        'create_year_id'
    ];

    /**
     * corresponding specific matiere for teacher
     */
    public function matiere() {
        return $this->belongsTo(Matiere::class, 'matiere_id');
    }

    /**
     *
     */
    public function enseignant() {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     *
     */
    public function classes() : BelongsToMany
    {
        return $this->belongsToMany(Classe::class, 'enseignement');
    }

    /**
     * A teacher can teach many subjects in a year.
     */
    public function enseignantMatieres()
    {
        return $this->belongsToMany(EnseignantMatiereModel::class, 'ens_mat_annee_scolaires')
            ->withPivot('enseignant_matiere_models_id')
            ->withTimestamps();
    }
}
