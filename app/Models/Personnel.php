<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Personnel extends User
{
    use HasFactory;

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
