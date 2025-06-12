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
    protected $fillable = [
        'devoir_id',
        'question',
        'points'
    ];

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

    /**
     * Une question possède plusieurs réponses pour un devoir
     */
    public function devoir_answers():HasMany
    {
        return $this->hasMany(DevoirAnswer::class);
    }

    /**
     * get question number in a devoir
     */
    public function getQuestionNumber()
    {
        $questionIds = $this->devoir->questions()
            ->orderBy('id')
            ->pluck('id')
            ->toArray();
        $position = array_search($this->id, $questionIds);
        return $position !== false ? $position + 1 : 1;
    }
}
