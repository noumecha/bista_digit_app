<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Enseignant extends User
{
    use HasFactory;

    /**
     * Define default value for user type
     */
    protected static function boot() {
        parent::boot();

        static::creating(function ($model) {
            $model->typeUser = 'enseignant';
        });
    }

    /**
     *
     */
    public function getDateNaissEnseignant() {
        return $this->attributes['dateNaiss'];
    }

    /**
     *
     */
    public function getLieuNaissEnseignant() {
        return $this->attributes['lieuNaiss'];
    }

    /**
     *
     */
    public function getDiplome1Enseignant() {
        return $this->attributes['diplome1'];
    }

    /**
     *
     */
    public function getDiplome2Enseignant() {
        return $this->attributes['diplome2'];
    }

    /**
     *
     */
    public function getFonctionEnseignant() {
        return $this->attributes['fonction'];
    }

}
