<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DevoirAnswer extends Model
{
    use HasFactory;

    /**
     * @var array
     */
    protected $fillable = [
        'result_id',
        'question_id',
        'selected_answers',
        'is_correct',
        'points_earned',
    ];
}
