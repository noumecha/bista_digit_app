<?php

namespace App\Http\Controllers;

use App\Models\Actualite;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * index function
     */
    public function index () {
        $students = User::all()->where('typeUser','=','eleve')->count();
        $teachers = User::all()->where('typeUser','=','enseignant')->count();
        return view('front.home', compact('students', 'teachers'));
    }

    /**
     * index function
     */
    public function programmes () {
        return view('front.programmes');
    }

    /**
     * index function
     */
    public function clubs () {
        return view('front.clubs');
    }

    /**
     * index function
     */
    public function epreuves () {
        return view('front.epreuves');
    }

    /**
     * index function
     */
    public function actualites () {
        $actualites = Actualite::all();
        return view('front.actus', compact('actualites'));
    }

    /**
     *
     */
    public function showActualite($id) {
        $actualite = Actualite::findOrFail($id);
        return view('actualites.show')->with('actualite', $actualite);
    }
}
