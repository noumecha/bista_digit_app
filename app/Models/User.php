<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Sex;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = ['classe_id'];

    /**
     * @var array
     */
    protected $fillable = [
        'surname',
        'numCni',
        'sex',
        'role',
        'profile',
        'dateNaiss',
        'lieuNaiss',
        'diplome1',
        'diplome2',
        'matricule',
        'statutRedoublanc',
        'typeUser',
        'fonction',
        'name',
        'email',
        'password',
        'phone',
        'location',
        'about',
    ];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'sex' => Sex::class,
    ];

    /**
     *
     */
    public function getName() {
        return $this->name;
    }

    /**
     *
     */
    public function anneeScolaire() {
        return $this->belongsToMany(AnneeScolaire::class);
    }

    /**
     *
     */
    public function isEleve() {
        return $this->typeUser === 'eleve';
    }

    /**
     *
     */
    public function isEnseignant() {
        return $this->typeUser === 'enseignant';
    }

    /**
     *
     */
    public function isPersonnel() {
        return $this->typeUser === 'personnel';
    }

    /**
     *
     */
    public function classe() : BelongsTo
    {
        return $this->belongsTo(Classe::class);
    }

    /**
     *
     */
    public function matieres() : BelongsToMany
    {
        return $this->belongsToMany(Matiere::class, 'enseignant_matiere', 'user_id', 'matiere_id');
    }

    /**
     *
     */
    public function actualites(): HasMany {
        return $this->hasMany(Actualite::class);
    }

    /**
     *
     */
    public function epreuves(): HasMany {
        return $this->hasMany(Epreuve::class);
    }
}
