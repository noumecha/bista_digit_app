<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAnneeScolaire extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'annee_scolaire_id'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function anneeScolaire() {
        return $this->belongsTo(AnneeScolaire::class);
    }
}
