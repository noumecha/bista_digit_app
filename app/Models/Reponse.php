<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reponse extends Model
{
    use HasFactory;

    /**
     * @var array
     */
    protected $fillable = [
        'question_id',
        'reponse',
        'status'
    ];

    /**
     * A reponse belongs to a specific Question
     */
    public function question():BelongsTo
    {
        return $this->belongsTo(Question::class, 'question_id');
    }
}
