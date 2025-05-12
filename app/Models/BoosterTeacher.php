<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BoosterTeacher extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'user_id',
        'classe_id',
        'booster_matiere_id',
        'annee_scolaire_id'
    ];

    /**
     *
     */
    public function annee_scolaire(): BelongsTo {
        return $this->belongsTo(AnneeScolaire::class, 'annee_scolaire_id');
    }

    /**
     *
     */
    public function teacher():BelongsTo {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     *
     */
    public function classe():BelongsTo {
        return $this->belongsTo(Classe::class, 'classe_id');
    }

    /**
     *
     */
    public function booster_matiere():BelongsTo {
        return $this->belongsTo(BoosterMatiere::class, 'booster_matiere_id');
    }

}
