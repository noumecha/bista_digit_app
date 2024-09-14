<?php

namespace App\Http\Controllers;

use App\Models\Classe;
use App\Models\Enseignant;
use App\Models\Enseignement;
use App\Models\User;
use Illuminate\Http\Request;

class EnseignementController extends Controller
{
    /**
     *
     */
    public function index() {
        $classes = Classe::all();
        $enseignants = User::all()->where('typeUser','=','enseignant');
        $enseignements = Enseignement::all();

        return view('education.enseignement', compact('classes', 'enseignants', 'enseignements'));
    }

    public function store(Request $request) {
        $request->validate([
            'classe_id' => 'required|exists:classes,id',
            'user_id' => 'required|exists:users,id',
            //'classes.*' => 'exists:classes,id'
        ], [
            'classe_id.required' => 'Selectionnez la classe',
            'user_id.required' => 'Selectionnez l\'enseignant',
        ]);

        $enseignant = User::find($request->user_id);
        $exists = Enseignement::where('classe_id', '=', $request->classe_id)->where('matiere_id','=',$enseignant->matiere_id)->exists();
        //dd($exists);
        if($exists) {
            return redirect()->back()->withErrors(['error'=>'Un enseignant enseigne déja cette matière dans cette classe']);
        }

        Enseignement::create([
            'classe_id' => $request->classe_id,
            'user_id' => $request->user_id,
            'matiere_id' => $enseignant->matiere_id
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
        $enseignants = User::all()->where('matiere_id', '!=', '');

        return view('education.enseignement', compact('classes','enseignements','enseignants','enseignementToEdit'));
    }

    public function update(Request $request, $id) {
        $request->validate([
            'classe_id' => 'required|exists:classes,id',
            'user_id' => 'required|exists:users,id',
        ], [
            'classe_id.required' => 'Selectionnez la classe',
            'user_id.required' => 'Selectionnez l\'enseignant',
        ]);


        $enseignant = User::find($request->user_id);
        $exists = Enseignement::where('classe_id', '=', $request->classe_id)->where('matiere_id', '=', $enseignant->matiere_id)->where('id', '!=', $id)->exists();

        if($exists)
            return redirect()->back()->withErrors(['error' => 'Un enseignant enseigne déjà cette matière dans cette classe']);

        $enseignement = Enseignement::findOrFail($id);
        $enseignement->update([
            'classe_id' => $request->classe_id,
            'user_id' => $request->user_id,
            'matiere_id' => $enseignant->matiere_id,
        ]);

        return redirect()->route('education.enseignement')->with('success', 'Attributation de classe mise à jour avec succès');
    }

    public function destroy($id) {
        $enseignement = Enseignement::findOrFail($id);
        $enseignement->delete();

        return redirect()->route('education.enseignement')->with('deleteSuccess', 'Attributation de classe supprimée avec succès');
    }
}
