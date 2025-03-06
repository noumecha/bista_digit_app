<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CoefAnneeScolaire extends Model
{
    use HasFactory;

    /**
     * @var array
     */
    protected $fillable = [
        'annee_scolaire_id',
        'coefficient_id',
        'groupe_matiere',
        'coefficient_value'
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
    public function coefficient(): BelongsTo {
        return $this->belongsTo(Coefficient::class, 'coefficient_id');
    }
}
