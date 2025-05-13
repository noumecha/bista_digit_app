<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BoosterStudent extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'user_id',
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
    public function student():BelongsTo {
        return $this->belongsTo(User::class, 'user_id');
    }

}
