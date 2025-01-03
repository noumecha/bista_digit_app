<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fonction extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    /**
     * @var array
     */
    protected $fillable = [
        'libelleFonction'
    ];

    /**
     * a user can have different fonction throw different shool year
     */
    public function fonctionAnneeScolaire() {
        return $this->belongsToMany(FonctionAnneeScolaireUser::class);
    }
    /**
     *
     */
    public function personnels()
    {
        return $this->belongsToMany(User::class, 'fonction_annee_scolaire_users')
                    ->withPivot('annee_scolaire_id')
                    ->withTimestamps();
    }
}
