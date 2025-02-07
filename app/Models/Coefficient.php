<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coefficient extends Model
{
    use HasFactory;

    protected $fillable = ['classe_id', 'matiere_id', 'coefficient','groupe_matiere','create_year_id'];

    public function classe() {
        return $this->belongsTo(Classe::class, 'classe_id');
    }

    public function matiere() {
        return $this->belongsTo(Matiere::class, 'matiere_id');
    }

}
