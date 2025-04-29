<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NoteHistory extends Model
{
    use HasFactory;

    /**
     * @var array
     */
    protected $fillable = [
        'note_id',
        'user_id',
        'old_value',
        'new_value',
        'reason'
    ];

    /**
     * A Note history belongs to a note
     */
    public function note()
    {
        return $this->belongsTo(Note::class);
    }

    /**
     * A note is changed by a user
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}