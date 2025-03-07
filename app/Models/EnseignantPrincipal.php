<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EnseignantPrincipal extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'user_id',
        'classe_id',
        'annee_scolaire_id'
    ];

    /**
     * principal teacher
     */
    public function enseignant():BelongsTo {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Principal in a class
     */
    public function classe():BelongsTo {
        return $this->belongsTo(Classe::class, 'classe_id');
    }

     /**
     * A Principal belongs to a school year
     */
    public function schoolYear():BelongsTo {
        return $this->belongsTo(Classe::class, 'annee_scolaire_id');
    }
}
