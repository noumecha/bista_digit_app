<?php

namespace App\Http\Controllers;

use App\Models\EnseignantMatiereModel;
use App\Models\Enseignement;
use App\Models\EnseignementAnneeScolaire;
use App\Models\EnsMatAnneeScolaire;
use App\Models\Epreuve;
use App\Models\Matiere;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class MatiereController extends Controller
{
    /**
     * Matiere controller implementation
     */
    public function index(Request $request)
    {
        $user = User::find(Auth::id());
        $matieres = Matiere::all();
        $searchMatiere = $request->input('searchMatiere');

        // querying
        $query = Matiere::query();

        // filtering
        if(!empty($searchMatiere)) {
            $query->where('libelleMatiere', 'LIKE', "%{$searchMatiere}%")
            ->orWhere('codeMatiere', 'LIKE', "%{$searchMatiere}%");
        }

        //dd($query);
        $query ?  $matieres = $query->paginate(10) : $matieres = [];

        if($request->ajax()) {
            return view('partials._matieres_table', compact('matieres', 'user'));
        } else {
            return view('education.matiere', compact('matieres', 'user'));
        }
    }

    /**
     *
     */
    public function store(Request $request) {
        $request->validate([
            'libelleMatiere' => 'required|max:255|unique:matieres',
            'codeMatiere' => 'required|max:255|unique:matieres',
        ], [
                'libelleMatiere.required' => 'Entrez le libellé de la matière',
                'codeMatiere.required' => 'Entrez le code de la matière',
                'libelleMatiere.unique' => 'Ce libellé de matière existe déja',
                'codeMatiere.unique' => 'Ce code de matiere existe déja',
         ]);

        $matiere = Matiere::create([
            'libelleMatiere' => $request->libelleMatiere,
            'codeMatiere' => $request->codeMatiere,
        ]);

        if($matiere) {
            return response()->json(['success' => 'Matière ajoutée avec succès !']);
        } else {
            return response()->json(['error' => 'Erreur inconue lors de la création de la matiere']);
        }
    }

    /**
     *
     */
    public function edit($id) {
        $matiereToEdit = Matiere::findOrFail($id);
        return response()->json(['matiereToEdit' => $matiereToEdit]);
    }

    /**
     *
     */
    public function update(Request $request, $id) {
        $request->validate([
            'libelleMatiere' => ['required','min:3','max:255',Rule::unique('matieres')->ignore($id)],
            'codeMatiere' => ['required','max:255',Rule::unique('matieres')->ignore($id)],
        ], [
                'libelleMatiere.required' => 'Entrez le libellé de la matière',
                'codeMatiere.required' => 'Entrez le code de la matière',
                'codeMatiere.unique' => 'Ce code existe déjà',
                'libelleMatiere.unique' => 'Ce libellé existe déjà',
         ]);

        $matiere = Matiere::findOrFail($id);
        $matiere->update($request->all());

        return response()->json(['success' => 'Matière mise à jour avec succès']);

    }

    /**
     *
     */
    public function destroy($id) {
        $matiere = Matiere::findOrFail($id);
        $epreuves = Epreuve::all()->where('matiere_id',$matiere->id);
        dd($epreuves);
        foreach ($epreuves as $epreuve) {
            $epreuve->delete();
        }
        $enseignantMatieres = EnseignantMatiereModel::all()->where('matiere_id',$matiere->id);
        foreach ($enseignantMatieres as $ensMat) {
            $enseignatMatieresYear = EnsMatAnneeScolaire::all()->where('enseignant_matiere_models_id',$ensMat->id);
            $enseignements = Enseignement::all()->where('enseignant_matiere_id',$ensMat->id);
            foreach ($enseignements as $enseignement) {
                $enseignementYear = EnseignementAnneeScolaire::all()->where('enseignement_id',$enseignement->id);
                $enseignementYear->delete();
                $enseignement->delete();
            }
            $enseignatMatieresYear->delete();
            $ensMat->delete();
        }
        $matiere->delete();
        // also add deletion process for evaluation & programme booster & configuration matiere

        return redirect()->route('education.matiere')->with('deleteSuccess', 'Matière supprimée avec succès');
    }
}
