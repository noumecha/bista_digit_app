<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BoosterNoteHistory extends Model
{
    use HasFactory;

    /**
     * @var array
     */
    protected $fillable = [
        'booster_note_id',
        'booster_teacher_id',
        'old_value',
        'new_value',
        'reason'
    ];

    /**
     * A Booster note history belongs to a booster note
     */
    public function note()
    {
        return $this->belongsTo(BoosterNote::class, 'booster_note_id');
    }

    /**
     * A Booster note is changed by a teacher
     */
    public function teacher()
    {
        return $this->belongsTo(BoosterTeacher::class, 'booster_teacher_id');
    }
}
