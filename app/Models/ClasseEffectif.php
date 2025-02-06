<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClasseEffectif extends Model
{
    use HasFactory;

    /**
     * @var array
     */
    protected $fillable = ['annee_scolaire_id','classe_id'];

    /**
     * an effectif belongs to a specific class
     */
    public function classe(): BelongsTo
    {
        return $this->belongsTo(Classe::class, 'classe_id');
    }

    /**
     * an effectif belongs to a specific year
     */
    public function year(): BelongsTo
    {
        return $this->belongsTo(AnneeScolaire::class, 'annee_scolaire_id');
    }

    /**
     * get effectif for a specific class
     */
    public function getEffectif() {
        $studentIds = User::where('typeUser','eleve')->pluck('id');
        return ClasseAnneeScolaireStudent::where('classe_id', $this->classe_id)
        ->where('annee_scolaire_id', $this->annee_scolaire_id)
        ->whereIn('user_id', $studentIds)
        ->count();
    }

}
