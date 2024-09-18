<?php

namespace App\Http\Controllers;

use App\Models\Classe;
use App\Models\Enseignement;
use App\Models\EnseignantMatiereModel;
use App\Models\Matiere;
use App\Models\User;
use Illuminate\Http\Request;

class EnseignementController extends Controller
{
    /**
     *
     */
    public function index() {
        $classes = Classe::all();
        $enseignantsMatieres = EnseignantMatiereModel::all();
        $enseignements = Enseignement::all();
        $matieres = Matiere::all();
        $enseignants = User::all()->where('typeUser' , '=', 'enseignant');

        //dd($enseignantsMatieres);
        return view('education.enseignement', compact('classes', 'enseignantsMatieres', 'enseignements', 'matieres', 'enseignants'));
    }

    public function store(Request $request) {
        $request->validate([
            'classe_id' => 'required|exists:classes,id',
            'enseignant_matiere_id' => 'required|exists:enseignant_matiere_models,id',
            //'classes.*' => 'exists:classes,id'
        ], [
            'classe_id.required' => 'Selectionnez la classe',
            'enseignant_matiere_id.required' => 'Selectionnez l\'enseignant',
        ]);

        $exists = Enseignement::where('classe_id', '=', $request->classe_id)->where('enseignant_matiere_id','=',$request->enseignant_matiere_id)->exists();
        //dd($exists);
        if($exists) {
            return redirect()->back()->withErrors(['error'=>'Un enseignant enseigne déja cette matière dans cette classe']);
        }

        Enseignement::create([
            'classe_id' => $request->classe_id,
            'enseignant_matiere_id' => $request->enseignant_matiere_id,
        ]);

        return redirect()->route('education.enseignement')->with('success', 'Classes attribuées avec succès');
    }

    /**
     *
     */
    public function edit($id) {
        $enseignementToEdit = Enseignement::findOrFail($id);
        $enseignements = Enseignement::all();
        $classes = Classe::all();
        $enseignantsMatieres = EnseignantMatiereModel::all();
        $matieres = Matiere::all();
        $enseignants = User::all()->where('typeUser' , '=', 'enseignant');

        return view('education.enseignement', compact('classes','enseignements','enseignantsMatieres','enseignementToEdit', 'matieres','enseignants'));
    }

    public function update(Request $request, $id) {
        $request->validate([
            'classe_id' => 'required|exists:classes,id',
            'enseignant_matiere_id' => 'required|exists:enseignant_matiere_models,id',
        ], [
            'classe_id.required' => 'Selectionnez la classe',
            'enseignant_matiere_id.required' => 'Selectionnez l\'enseignant',
        ]);

        $exists = Enseignement::where('classe_id', '=', $request->classe_id)->where('enseignant_matiere_id','=',$request->enseignant_matiere_id)->exists();
        if($exists)
            return redirect()->back()->withErrors(['error' => 'Un enseignant enseigne déjà cette matière dans cette classe']);

        $enseignement = Enseignement::findOrFail($id);
        $enseignement->update([
            'classe_id' => $request->classe_id,
            'enseignant_matiere_id' => $request->enseignant_matiere_id,
        ]);

        return redirect()->route('education.enseignement')->with('success', 'Attributation de classe mise à jour avec succès');
    }

    public function destroy($id) {
        $enseignement = Enseignement::findOrFail($id);
        $enseignement->delete();

        return redirect()->route('education.enseignement')->with('deleteSuccess', 'Attributation de classe supprimée avec succès');
    }
}
