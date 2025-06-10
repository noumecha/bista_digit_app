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
        'user_id',
        'score',
        'total_questions',
        'percentage',
        'completed_at',
    ];

    /**
     * A devoir result belongs to a Devoir
     */
    public function devoir() {
        return $this->belongsTo(Devoir::class);
    }

    /**
     * A devoir result belongs to a User
     */
    public function user() {
        return $this->belongsTo(User::class);
    }

    /**
     * A devoir result contain some answers
     */
    public function answers() {
        return $this->hasMany(DevoirAnswer::class);
    }

}
