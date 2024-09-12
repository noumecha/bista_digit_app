<?php

namespace App\Http\Controllers;

use App\Models\Matiere;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MatiereController extends Controller
{
    /**
     * Matiere controller implementation
     */
    public function index()
    {
        $user = User::find(Auth::id());
        $matieres = Matiere::all();

        return view('education.matiere',['matieres' => $matieres], compact('user'));
    }

    /**
     *
     */
    public function store(Request $request) {
        $request->validate([
            'libelleMatiere' => 'required|min:3|max:255|unique:matieres',
            'codeMatiere' => 'required|max:255|unique:matieres',
        ], [
                'libelleMatiere.required' => 'Entrez le libellé de la matière',
                'codeMatiere.required' => 'Entrez le code de la matière',
                'libelleMatiere.unique' => 'Ce libellé de matière existe déja',
                'codeMatiere.unique' => 'Ce code de matiere existe déja',
         ]);

        Matiere::create([
            'libelleMatiere' => $request->libelleMatiere,
            'codeMatiere' => $request->codeMatiere,
        ]);

        return redirect()->route('education.matiere')->with('success', 'Matière ajoutée avec succès !');
    }

    /**
     *
     */
    public function edit($id) {
        $matiere = Matiere::findOrFail($id);
        $matieres = Matiere::all();

        return view('education.matiere', compact('matieres', 'matiere'));
    }

    /**
     *
     */
    public function update(Request $request, $id) {
        $request->validate([
            'libelleMatiere' => 'required|min:3|max:255',
            'codeMatiere' => 'required|max:255|unique:matieres',
        ], [
                'libelleMatiere.required' => 'Entrez le libellé de la matière',
                'codeMatiere.required' => 'Entrez le code de la matière',
         ]);

        $matiere = Matiere::findOrFail($id);
        $matiere->update($request->all());

        return redirect()->route('education.matiere')->with('success', 'Matière mise à jour avec succès');
    }

    /**
     *
     */
    public function destroy($id) {
        $matiere = Matiere::findOrFail($id);
        $matiere->delete();

        return redirect()->route('education.matiere')->with('deleteSuccess', 'Matière supprimée avec succès');
    }
}
