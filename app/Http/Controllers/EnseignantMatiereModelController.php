<?php

namespace App\Http\Controllers;

use App\Models\EnseignantMatiereModel;
use App\Models\Matiere;
use App\Models\User;
use Illuminate\Http\Request;

class EnseignantMatiereModelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $matieres = Matiere::all();
        $enseignants = User::all()->where('typeUser','=','enseignant');
        $enseignantsMatieres = EnseignantMatiereModel::all();

        return view('education.enseignantMatiere', compact('matieres', 'enseignants', 'enseignantsMatieres'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'matiere_id' => 'required|exists:matieres,id',
            'user_id' => 'required|exists:users,id',
        ], [
            'matiere_id.required' => 'Selectionnez la classe',
            'user_id.required' => 'Selectionnez l\'enseignant',
        ]);

        $exists = EnseignantMatiereModel::where('matiere_id', '=', $request->matiere_id)->where('user_id','=',$request->user_id)->exists();
        //dd($exists);
        if($exists) {
            return redirect()->back()->withErrors(['error'=>'Cet enseignant peut déja enseigner cette matière']);
        }

        EnseignantMatiereModel::create([
            'user_id' => $request->user_id,
            'matiere_id' => $request->matiere_id
        ]);

        return redirect()->route('education.enseignantMatiere')->with('success', 'Matiere attribuer à l\'enseignant avec succès');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EnseignantMatiereModel $enseignantMatiereModel, $id)
    {
        $enseignantMatiereToEdit = $enseignantMatiereModel::findOrFail($id);
        $enseignantsMatieres = $enseignantMatiereModel::all();
        $matieres = Matiere::all();
        $enseignants = User::all();

        return view('education.enseignantMatiere', compact('matieres','enseignantsMatieres','enseignants','enseignantMatiereToEdit'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, EnseignantMatiereModel $enseignantMatiereModel, $id)
    {
        $request->validate([
            'matiere_id' => 'required|exists:matieres,id',
            'user_id' => 'required|exists:users,id',
        ], [
            'matiere_id.required' => 'Selectionnez la matiere',
            'user_id.required' => 'Selectionnez l\'enseignant',
        ]);

        $exists = EnseignantMatiereModel::where('matiere_id', '=', $request->matiere_id)->where('user_id','=',$request->user_id)->exists();

        if($exists) {
            return redirect()->back()->withErrors(['error'=>'Cet enseignant enseigne déja cette matière']);
        }

        $enseignantMatiere = $enseignantMatiereModel::findOrFail($id);
        $enseignantMatiere->update([
            'user_id' => $request->user_id,
            'matiere_id' => $request->matiere_id,
        ]);

        return redirect()->route('education.enseignantMatiere')->with('success', 'Attributation de matière mis à jour avec succès');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EnseignantMatiereModel $enseignantMatiereModel, $id)
    {
        $enseignantMatiere = $enseignantMatiereModel::findOrFail($id);
        $enseignantMatiere->delete();

        return redirect()->route('education.enseignantMatiere')->with('deleteSuccess', 'Attributation de matière supprimée avec succès');
    }
}
