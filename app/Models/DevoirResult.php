<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DevoirResult extends Model
{
    use HasFactory;

    /**
     * @var array
     */
    protected $fillable = [
        'devoir_id',
        'user_id', // eleve
        'started_at',
        'score',
        'total_questions',
        'percentage',
        'completed_at',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime'
    ];

    /**
     * A devoir result belongs to a Devoir
     */
    public function devoir() {
        return $this->belongsTo(Devoir::class);
    }

    /**
     * A devoir result belongs to a User : eleve
     */
    public function user() {
        return $this->belongsTo(User::class);
    }

    /**
     * get user
     */
    public function getUser() {
        return User::findOrFail($this->user_id);
    }

    /**
     * A devoir result contain some answers
     */
    public function answers() {
        return $this->hasMany(QuestionUserReponse::class, 'user_id', 'user_id')
            ->whereIn('question_id', $this->devoir->questions()->pluck('id'));
    }

    /**
     * calculate score
     */
    public function calculateScore() {
        $totalPoints = 0;
        $earnedPoints = 0;

        foreach ($this->devoir->questions as $question) {
            $totalPoints += $question->points;

            $userAnswers = $this->answers()
                ->where('question_id', $question->id)
                ->pluck('reponse_id')
                ->toArray();

            $correctAnswers = $question->reponses()
                ->where('status', true)
                ->pluck('id')
                ->toArray();

            if (empty(array_diff($correctAnswers, $userAnswers))) {
                $earnedPoints += $question->points;
            }
        }

        $this->update([
            'score' => $earnedPoints,
            'total_questions' => $totalPoints,
            'percentage' => $totalPoints > 0 ? ($earnedPoints / $totalPoints) * 100 : 0
        ]);

        return $this;
    }

}
