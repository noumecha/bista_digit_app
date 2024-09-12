<?php

namespace App\Http\Controllers;

use App\Models\Classe;
use App\Models\Enseignant;
use App\Models\Enseignement;
use App\Models\User;
use Illuminate\Http\Request;

class EnseignementController extends Controller
{
    public function index() {
        $classes = Classe::all();
        $enseignants = User::all()->where('typeUser','=','enseignant');

        return view('education.enseignement', compact('classes', 'enseignants'));
    }

    public function store(Request $request) {
        $request->validate([
            'classes' => 'required|array',
            'enseignant_id' => 'required|exists:users,id',
            'classes.*' => 'exists:classes,id'
        ], [
            'classes.required' => 'Selectionnez au moins une classe',
            'enseignant_id.required' => 'Selectionnez l\'enseignant',
        ]);

        $enseignant = User::findOrFail($request->user_id)->where('typeUser','=','enseignant');
        foreach($request->classes as $classe_id) {
            $alreadyAssigned = Enseignement::where('classe_id', $classe_id)->where('matiere_id',$enseignant->matiere_id)->exists();
            if ($alreadyAssigned) {
                return redirect()->back()->withErrors(['error' => 'La classe ID $clase_id est déja attribué' ]);
            }

            Enseignement::create($request->all());
        }

        return redirect()->route('education.enseignement')->with('success', 'Classes attribuées avec succès');
    }

    public function edit($id) {
        $classes = Classe::all();
        $enseignants = Enseignant::all();

        return view('education.enseignement', compact('classes', 'enseignants'));
    }

    public function update(Request $request, $id) {
        $request->validate([
            'classe_id' => 'required|exists:classes,id',
            'enseignant_id' => 'required|exists:users,id',
        ], [
            'classe_id.required' => 'Selectionnez la classe',
            'enseignant_id' => 'Selectionnez l\'enseignant',
        ]);

        $enseignement = Enseignement::findOrFail($id);
        $enseignement->update($request->all());

        return redirect()->route('education.enseignement')->with('success', 'Attributation de classe mise à jour avec succès');
    }

    public function destroy($id) {
        $enseignement = Enseignement::findOrFail($id);
        $enseignement->delete();

        return redirect()->route('education.enseignement')->with('deleteSuccess', 'Attributation de classe supprimée avec succès');
    }
}
