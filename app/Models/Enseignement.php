<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enseignement extends Model
{
    use HasFactory;

    protected $fillable = ['classe_id', 'user_id'];

    public function classe() {
        return $this->belongsTo(Classe::class);
    }

    public function enseignant() {
        return $this->belongsTo(User::class);
    }
}
