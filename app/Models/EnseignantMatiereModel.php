<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class EnseignantMatiereModel extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'matiere_id'];

    /**
     *
     */
    public function matieres() {
        return $this->belongsTo(Matiere::class);
    }

    /**
     *
     */
    public function enseignant() {
        return $this->belongsTo(User::class);
    }

    /**
     *
     */
    public function classes() : BelongsToMany
    {
        return $this->belongsToMany(Classe::class, 'enseignement');
    }

}
