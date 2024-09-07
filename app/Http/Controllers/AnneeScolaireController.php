<?php

namespace App\Http\Controllers;

use App\Models\AnneeScolaire;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class AnneeScolaireController extends Controller
{
    /**
     *
     */
    public function show() {
        $user = User::find(Auth::id());
        $years = AnneeScolaire::all();
        return view('annee_scolaire.show',['years'=> $years] ,compact('user'));
    }

     /**
     * saving administrators members
     * @param  \Illuminate\Http\Request  $request
     */
    public function store(Request $request) {
        $request->validate([
            'libelle' => 'required|min:3|max:255',
        ], [
            'libelle.required' => 'Définissez une année scolaire',
         ]);

        AnneeScolaire::create([
            'libelleAnneeScolaire' => $request->libelle,
        ]);


        $years = AnneeScolaire::all();

        return view('annee_scolaire.show', ['years'=> $years]);
    }

    /**
     *
     */
    public function edit() {

    }

    /**
     *
     */
    public function delete() {

    }
}
