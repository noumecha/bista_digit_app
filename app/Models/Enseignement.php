<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enseignement extends Model
{
    use HasFactory;

    /**
     * @var array
     */
    protected $fillable = ['classe_id', 'enseignant_matiere_id'];

    /**
     *
     */
    public function classe() {
        return $this->belongsTo(Classe::class);
    }

    /**
     *
     */
    public function enseignantmatiere() {
        return $this->belongsTo(EnseignantMatiereModel::class);
    }
}
