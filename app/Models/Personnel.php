<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Personnel extends User
{
    use HasFactory;

    /**
     * Define default value for user type
     */
    protected static function boot() {
        parent::boot();

        static::creating(function ($model) {
            $model->typeUser = 'administration';
        });
    }

    /**
     *
     */
    public function getDateNaissAttribute() {
        return $this->attributes['dateNaiss'];
    }

    /**
     *
     */
    public function getLieuNaissAttribute() {
        return $this->attributes['lieuNaiss'];
    }

    /**
     *
     */
    public function getDiplome1Attribute() {
        return $this->attributes['diplome1'];
    }

    /**
     *
     */
    public function getDiplome2Attribute() {
        return $this->attributes['diplome2'];
    }

    /**
     *
     */
    public function getFonctionAttribute() {
        return $this->attributes['fonction'];
    }

}
