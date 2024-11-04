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
            'libelle' => 'required|min:3|max:255|unique:annee_scolaire',
        ], [
            'libelle.required' => 'Définissez une année scolaire',
            'libelle.unique' => 'Cette année scolaire existe déjà',
         ]);

        AnneeScolaire::create([
            'libelleAnneeScolaire' => $request->libelle,
        ]);

        return redirect()->route('annee_scolaire.show')->with('success', 'Année scolaire définie avec succès!');
    }

    public function edit(Request $request, $id) {
        $yearToEdit = AnneeScolaire::findOrFail($id);
        $years = AnneeScolaire::all();

        return view('annee_scolaire.show', compact('yearToEdit','years'));
    }

    public function update(Request $request, $id) {
        $request->validate([
            'libelle' => 'required|min:3|max:255|unique:annee_scolaire',
        ], [
            'libelle.required' => 'Définissez une année scolaire',
            'libelle.unique' => 'Cette année scolaire existe déjà',
         ]);

        $year = AnneeScolaire::findOrFail($id);
        $year->update($request->all());

        return redirect()->route('annee_scolaire.show')->with('success', 'Année mise à jour avec succès');
    }

    public function destroy($id) {
        $year = AnneeScolaire::findOrFail($id);
        $year->delete();

        return redirect()->route('annee_scolaire.show')->with('deleteSuccess', 'Année supprimée avec succès');
    }
}
