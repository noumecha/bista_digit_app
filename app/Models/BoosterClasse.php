<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BoosterClasse extends Model
{
    use HasFactory;

    /**
     * @var array
     */

    protected $fillable = [
        'classe_id',
        'annee_scolaire_id'
    ];

    /**
     * Get the effectif for the booster classe
     */
    public function getEffectif($activeYearId)
    {
        $boosterStudentIds = BoosterStudent::where('annee_scolaire_id', $this->annee_scolaire_id)
            ->pluck('user_id');
        return ClasseAnneeScolaireStudent::whereIn('user_id', $boosterStudentIds)
            ->where('annee_scolaire_id', $activeYearId)
            ->where('classe_id', $this->classe_id)->count();
    }

    /**
     * A booster class belongs To a normal class
     */
    function classe():BelongsTo {
        return $this->belongsTo(Classe::class, 'classe_id');
    }

    /**
     * A booster class belongs to year
     */
    function annee_scolaire():BelongsTo {
        return $this->belongsTo(AnneeScolaire::class, 'annee_scolaire_id');
    }
}
