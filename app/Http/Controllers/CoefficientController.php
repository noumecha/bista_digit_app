<?php

namespace App\Http\Controllers;

use App\Models\Classe;
use App\Models\Coefficient;
use App\Models\Matiere;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CoefficientController extends Controller
{
    public function index(Request $request) {
        $user = User::find(Auth::id());
        $classes = Classe::all();
        $matieres = Matiere::all();
        // filters inputs
        $searchCoef = $request->input('searchCoef');
        $classeFilter = $request->input('classeFilter');
        $matiereFilter = $request->input('matiereFilter');
        $groupFilter = $request->input('groupFilter');

        // querying
        $query = Coefficient::query();

        // filtering
        if(!empty($searchCoef)) {
            $query->where('coefficient', 'LIKE', "%{$searchCoef}%");
        }
        if(!empty($groupFilter)) {
            $query->where('groupe_matiere', 'LIKE', "%{$groupFilter}%");
        }
        if(!empty($classeFilter)) {
            $query->whereHas('classe', function ($q) use ($classeFilter) {
                $q->where('id', $classeFilter);
            });
        }
        if(!empty($matiereFilter)) {
            $query->whereHas('matiere', function ($q) use ($matiereFilter) {
                $q->where('id', $matiereFilter);
            });
        }

        //dd($query);
        $coefficients = $query->paginate(10);

        if($request->ajax()) {
            return view('partials._coefficients_table', compact('matieres','classes','coefficients','user'));
        } else {
            return view('education.coefficients', compact('matieres','classes','coefficients','user'));
        }
    }

    public function store(Request $request) {
        $request->validate([
            'classe_id' => 'required|exists:classes,id',
            'matiere_id' => 'required|exists:matieres,id',
            'coefficient' => 'required|numeric',
            'groupe_matiere' => 'required',
        ], [
            'classe_id.required' => 'Selectionnez la classe',
            'matiere_id.required' => 'Selectionnez la matière',
            'groupe_matiere.required' => 'Selectionnez le groupe de la matière pour la classe',
            'coefficient.required' => 'Définissez la valeur du coefficient',
        ]);

        // check if the configuration already exists
        $existingCoefficient = Coefficient::where('classe_id', $request->classe_id)
            ->where('matiere_id', $request->matiere_id)
            ->where('groupe_matiere', $request->groupe_matiere)
            ->first();

        if ($existingCoefficient) {
            return response()->json(['error' => 'Un coefficient pour cette matière exite déjà dans cette classe']);
        }

        // check if some subjet is already in a group in the correspondig class
        $existingGroup = Coefficient::where('classe_id', $request->classe_id)
            ->where('matiere_id', $request->matiere_id)
            ->where('groupe_matiere', '!=', $request->groupe_matiere)
            ->exists();

        if ($existingGroup) {
            return response()->json(['error' => 'Cette matière est déjà affectée à un autre groupe dans cette classe.']);
        }

        $coef = Coefficient::create($request->all());

        if($coef) {
            return response()->json(['success' => 'Configuration de la matière pour la classe avec succès!']);
        } else {
            return response()->json(['error' => 'Erreur inconue lors de la configuration de la matière pour la classe']);
        }
    }

    public function edit($id) {
        $coefToEdit = Coefficient::findOrFail($id);
        return response()->json(['matiereToEdit' => $coefToEdit]);
    }

    public function update(Request $request, $id) {
        $request->validate([
            'classe_id' => 'required|exists:classes,id',
            'matiere_id' => 'required|exists:matieres,id',
            'coefficient' => 'required|numeric',
            'groupe_matiere' => 'required',
        ], [
            'classe_id.required' => 'Selectionnez la classe',
            'groupe_matiere.required' => 'Selectionnez le groupe de la matière pour la classe',
            'matiere_id.required' => 'Selectionnez la matière',
            'coefficient.required' => 'Définissez la valeur du coefficient',
        ]);

        // check if the configuration already exists
        $existingCoefficient = Coefficient::where('classe_id', $request->classe_id)
            ->where('matiere_id', $request->matiere_id)
            ->where('groupe_matiere', $request->groupe_matiere)
            ->where('id', '!=', $id)
            ->exists();

        if ($existingCoefficient) {
            return response()->json(['error' => 'Une autre configuration existe déjà avec cette combinaison classe-matière-groupe.']);
        }

        // check if some subjet is already in a group in the correspondig class
        $existingGroup = Coefficient::where('classe_id', $request->classe_id)
            ->where('matiere_id', $request->matiere_id)
            ->where('groupe_matiere', '!=', $request->groupe_matiere)
            ->where('id', '!=', $id)
            ->exists();

        if ($existingGroup) {
            return response()->json(['error' => 'Cette matière est déjà affectée à un autre groupe dans cette classe.']);
        }

        $coefficient = Coefficient::findOrFail($id);
        $coefficient->update($request->all());
        return response()->json(['success' => 'Matière mise à jour avec succès']);
    }

    public function destroy($id) {
        $coefficient = Coefficient::findOrFail($id);
        $coefficient->delete();
        return redirect()->route('education.coefficients')->with('deleteSuccess', 'Configuration de la matière supprimée avec succès');
    }
}
