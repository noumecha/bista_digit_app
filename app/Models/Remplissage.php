<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Remplissage extends Model
{
    use HasFactory;

    protected $fillable = ['date_debut','date_fin','statut','evaluation_id'];

    /**
     *
     */
    public function evaluation(): BelongsTo
    {
        return $this->belongsTo(Evaluation::class, 'evaluation_id');
    }


    /**
     *
     */
    public function notes(): HasMany {
        return $this->hasMany(Note::class);
    }
}
