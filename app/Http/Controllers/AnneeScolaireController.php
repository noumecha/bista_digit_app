<?php

namespace App\Http\Controllers;

use App\Models\AnneeScolaire;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class AnneeScolaireController extends Controller
{
    /**
     * first function to show the datas
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
            'libelleAnneeScolaire' => 'required|min:3|max:255|unique:annee_scolaires',
        ], [
            'libelleAnneeScolaire.required' => 'Définissez une année scolaire',
            'libelleAnneeScolaire.unique' => 'Cette année scolaire existe déjà',
         ]);

        AnneeScolaire::create([
            'libelleAnneeScolaire' => $request->libelleAnneeScolaire,
            'statut' => false,
        ]);

        return redirect()->route('annee_scolaire.show')->with('success', 'Année scolaire définie avec succès!');
    }

    /**
     * function to edit year
     */
    public function edit(Request $request, $id) {
        $yearToEdit = AnneeScolaire::findOrFail($id);
        $years = AnneeScolaire::all();

        return view('annee_scolaire.show', compact('yearToEdit','years'));
    }

    /**
     * this function helps to activate a year for using his datas
     */
    public function activate($id) {

        AnneeScolaire::where('statut', '=', true)->update(['statut' => false]);

        $year = AnneeScolaire::findOrFail($id);
        //$year->statut = true;
        //dd($year);
        $year->update([
            'statut' => true,
        ]);

        return redirect()->route('annee_scolaire.show')->with('listSuccess', 'Année scolaire activé avec succès!');
    }

    /**
     * this function helps to deactivate a year.
     */
    public function desactivate($id) {
        $year = AnneeScolaire::findOrFail($id);
        $year->update([
            'statut' => false,
        ]);

        return redirect()->route('annee_scolaire.show')->with('listSuccess', 'Année scolaire désactivé avec succès!');
    }

    /**
     * function to update a year.
     */
    public function update(Request $request, $id) {
        $request->validate([
            'libelleAnneeScolaire' => 'required|min:3|max:255',
        ], [
            'libelleAnneeScolaire.required' => 'Définissez une année scolaire',
         ]);

        $year = AnneeScolaire::findOrFail($id);
        $year->update($request->all());

        return redirect()->route('annee_scolaire.show')->with('success', 'Année mise à jour avec succès');
    }

    /**
     * function to delete a year
     */
    public function destroy($id) {
        $year = AnneeScolaire::findOrFail($id);
        $year->delete();

        return redirect()->route('annee_scolaire.show')->with('listSuccess', 'Année supprimée avec succès');
    }
}
