<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DevoirAnneeScolaire extends Model
{
    use HasFactory;

    /**
     * @var array
     */
    protected $fillable = ['devoir_id','annee_scolaire_id'];

    /**
     *
     */
    public function devoir():BelongsTo
    {
        return $this->belongsTo(Devoir::class, 'devoir_id');
    }

    /**
     *
     */
    public function anneeScolaire():BelongsTo
    {
        return $this->belongsTo(AnneeScolaire::class, 'annee_scolaire_id');
    }
}
