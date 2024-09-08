<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coefficient extends Model
{
    use HasFactory;

    protected $fillable = ['classe_id', 'matiere_id', 'coefficient'];

    public function classe() {
        return $this->belongsTo(Classe::class);
    }

    public function matiere() {
        return $this->belongsTo(Matiere::class);
    }

}
