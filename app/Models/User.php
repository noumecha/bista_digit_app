<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Sex;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
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
    protected $guarded = [];

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
        'matiere_id',
        'classe_id',
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
    public function newPersonnelBuilder($attributes = [], $connection = null) {
        $instance = parent::newPersonnelBuilder($attributes, $connection);
        if ($this->typeUser == 'administration') {
            $instance = $instance->replicate([], Personnel::class);
        }
        return $instance;
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
    public function isPersonnel() {
        return $this->typeUser === 'personnel';
    }

     /**
     *
     */
    public function classes() : BelongsToMany
    {
        return $this->belongsToMany(Classe::class, 'enseignement')->withPivot('matiere_id');
    }

    /**
     *
     */
    public function matiere() : BelongsTo
    {
        return $this->belongsTo(Matiere::class);
    }

    /**
     *
     */
    public function classe() : BelongsTo
    {
        return $this->belongsTo(Classe::class);
    }
}
