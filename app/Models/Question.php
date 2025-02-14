<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    use HasFactory;

    /**
     * @var array
     */
    protected $fillable = ['devoir_id', 'question'];

    /**
     * A question belongs to a specific devoir
     */
    public function devoir():BelongsTo
    {
        return $this->belongsTo(Devoir::class, 'devoir_id');
    }

    /**
     * A question haves many responses
     */
    public function reponses():HasMany
    {
        return $this->hasMany(Reponse::class);
    }
}
