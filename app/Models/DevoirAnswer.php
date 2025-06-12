<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DevoirAnswer extends Model
{
    use HasFactory;

    /**
     * @var array
     */
    protected $fillable = [
        'devoir_result_id',
        'question_id',
        'selected_answers',
        'is_correct',
        'points_earned',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'selected_answers' => 'array',
    ];

     /**
     * Un devoir correspond à une question
     */
    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class, 'question_id');
    }
}
