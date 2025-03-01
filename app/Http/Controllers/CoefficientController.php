<?php

namespace App\Http\Controllers;

use App\Models\AnneeScolaire;
use App\Models\Classe;
use App\Models\CoefAnneeScolaire;
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
        $activeYear = AnneeScolaire::all()->where('statut','=', true)->first();
        $migrateYears = AnneeScolaire::all()->where('created_at', '>', $activeYear->created_at);
        $coefSchoolYears = CoefAnneeScolaire::all()->where('annee_scolaire_id','=', $activeYear->id);
        $coefSchoolYearsIds = $coefSchoolYears->pluck('coefficient_id');
        // filters inputs
        $searchCoef = $request->input('searchCoef');
        $classeFilter = $request->input('classeFilter');
        $matiereFilter = $request->input('matiereFilter');
        $groupFilter = $request->input('groupFilter');

        // querying
        if(isset($coefSchoolYears)) {
            $query = Coefficient::query()->whereIn('id', $coefSchoolYearsIds);
        }

        // filtering
        if(!empty($searchCoef)) {
            $query->whereHas('coefAnneeScolaire', function ($q) use ($searchCoef) {
                $q->where('coefficient_value', 'LIKE', "%{$searchCoef}%");
            });
        }
        if(!empty($groupFilter)) {
            $query->whereHas('coefAnneeScolaire', function ($q) use ($groupFilter) {
                $q->where('groupe_matiere', 'LIKE', "%{$groupFilter}%");
            });
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

        $coefficients = $query->paginate(10);

        if($request->ajax()) {
            return view('partials._coefficients_table', compact('matieres','classes','coefficients','user','activeYear','migrateYears'));
        } else {
            return view('education.coefficients', compact('matieres','classes','coefficients','user','activeYear','migrateYears'));
        }
    }

    /**
     * create a specific matieres configuration for a specific class
     */
    public function store(Request $request) {
        $request->validate([
            'classe_id' => 'required|exists:classes,id',
            'matiere_id' => 'required|exists:matieres,id',
            'coefficient' => 'required|numeric',
            'groupe_matiere' => 'required',
            'active_year_id' => 'required'
        ], [
            'classe_id.required' => 'Selectionnez la classe',
            'matiere_id.required' => 'Selectionnez la matière',
            'groupe_matiere.required' => 'Selectionnez le groupe de la matière pour la classe',
            'coefficient.required' => 'Définissez la valeur du coefficient',
            'active_year_id.required' => 'Veuillez activer une année scolaire!'
        ]);

        // check if the configuration already exists
        $existingCoefficient = Coefficient::where('classe_id', $request->classe_id)
            ->where('matiere_id', $request->matiere_id)->first();
        if ($existingCoefficient) {
            $existingCoefYear = CoefAnneeScolaire::where('coefficient_id', $existingCoefficient->id)
                ->where('groupe_matiere', $request->groupe_matiere)
                ->where('annee_scolaire_id', $request->active_year_id)
                ->first();
            if($existingCoefYear)
                return response()->json(['error' => 'Un coefficient pour cette matière exite déjà dans cette classe']);
        }

        // check if some subjet is already in a group in the correspondig class
        $existingGroup = Coefficient::where('classe_id', $request->classe_id)
            ->where('matiere_id', $request->matiere_id)->first();
        if ($existingGroup) {
            $existingGroupYear = CoefAnneeScolaire::where('coefficient_id', $existingGroup->id)
                ->where('groupe_matiere', '!=', $request->groupe_matiere)
                ->where('annee_scolaire_id', $request->active_year_id)
                ->exists();
            if($existingGroupYear)
                return response()->json(['error' => 'Cette matière est déjà affectée à un autre groupe dans cette classe.']);
        }

        $coef = Coefficient::create([
            'classe_id' => $request->classe_id,
            'matiere_id' => $request->matiere_id,
            'annee_scolaire_id' => $request->active_year_id,
        ]);

        $coefYear = CoefAnneeScolaire::create([
            'annee_scolaire_id' => $request->active_year_id,
            'coefficient_id' => $coef->id,
            'groupe_matiere' => $request->groupe_matiere,
            'coefficient_value' => $request->coefficient
        ]);

        if($coef && $coefYear) {
            return response()->json(['success' => 'Configuration de la matière pour la classe avec succès!']);
        } else {
            return response()->json(['error' => 'Erreur inconue lors de la configuration de la matière pour la classe']);
        }
    }

    /**
     * get specific configuration for editing
     */
    public function edit($id, $yearId) {
        $coefToEdit = Coefficient::findOrFail($id);
        $coefYear = CoefAnneeScolaire::where('coefficient_id', '=', $coefToEdit->id)
        ->where('annee_scolaire_id', '=', (int)$yearId)->first();
        return response()->json([
            'coefToEdit' => $coefToEdit,
            'coefficient' => $coefYear->coefficient_value,
            'groupe_matiere' => $coefYear->groupe_matiere
        ]);
    }

    /**
     * update specific configuration
     */
    public function update(Request $request, $id) {
        $request->validate([
            'classe_id' => 'required|exists:classes,id',
            'matiere_id' => 'required|exists:matieres,id',
            'coefficient' => 'required|numeric',
            'groupe_matiere' => 'required',
            'active_year_id' => 'required'
        ], [
            'classe_id.required' => 'Selectionnez la classe',
            'groupe_matiere.required' => 'Selectionnez le groupe de la matière pour la classe',
            'matiere_id.required' => 'Selectionnez la matière',
            'coefficient.required' => 'Définissez la valeur du coefficient',
            'active_year_id.required' => 'Veuillez activer une année scolaire!'
        ]);

        // check if the configuration already exists
        $existingCoefficient = Coefficient::where('classe_id', $request->classe_id)
            ->where('matiere_id', $request->matiere_id)
            ->where('id', '!=', $id)->first();

        if ($existingCoefficient) {
            $existingCoefYear = CoefAnneeScolaire::where('coefficient_id',$existingCoefficient->id)
            ->where('groupe_matiere', $request->groupe_matiere)
            ->where('annee_scolaire_id', $request->active_year_id)
            ->exists();
            if($existingCoefYear)
                return response()->json(['error' => 'Une autre configuration existe déjà avec cette combinaison classe-matière-groupe.']);
        }

        // check if some subjet is already in a group in the correspondig class
        $existingGroup = Coefficient::where('classe_id',$request->classe_id)
            ->where('matiere_id',$request->matiere_id)
            ->where('id','!=',$id)->first();

        if ($existingGroup) {
            $existingGroupYear = CoefAnneeScolaire::where('coefficient_id', $existingGroup->id)
                ->where('groupe_matiere',$request->groupe_matiere)
                ->where('annee_scolaire_id',$request->active_year_id)
                ->exists();
            if($existingGroupYear)
                return response()->json(['error' => 'Cette matière est déjà affectée à un autre groupe dans cette classe.']);
        }

        $coefficient = Coefficient::findOrFail($id);
        $coefficient->update($request->all());
        $coefYear = CoefAnneeScolaire::where('coefficient_id', $coefficient->id)
            ->where('annee_scolaire_id', $request->active_year_id)->first();

        $coefYear->update([
            'coefficient_value' => $request->coefficient,
            'groupe_matiere' => $request->groupe_matiere
        ]);

        return response()->json(['success' => 'Matière mise à jour avec succès']);
    }

    /**
     * delete configuration permanently
     */
    public function destroy($id) {
        $coefficient = Coefficient::findOrFail($id);
        $coefYears = CoefAnneeScolaire::all()->where('coeffiecient_id', $coefficient->id);
        foreach ($coefYears as $coefYear) {
            $coefYear->delete();
        }
        $coefficient->delete();
        return redirect()->route('education.coefficients')->with('deleteSuccess', 'Configuration de la matière supprimée avec succès');
    }

    /**
     *  delete coefficient for current year
     */
    public function deleteCoefCurrentYear(Request $request) {

        $coefYear = CoefAnneeScolaire::all()->where('annee_scolaire_id', '=', $request->delusyear_year_id)->where('coefficient_id', '=', $request->delusyear_coef_id)->first();

        if ($coefYear->delete()) {
            return redirect()->route('education.coefficients')->with('deleteSuccess', 'Configuration supprimé avec succès pour l\'année courrante');
        } else {
            return redirect()->route('education.coefficients')->with('errorSuccess', 'Echec de surpression de la configuration pour l\'année courrante');
        }

    }

    /**
     * Migrate configuration for a forward year to achieve application evolution
     */
    public function migrate(Request $request) {
        $request->validate([
            'migrate_year_id' => 'required|exists:annee_scolaires,id',
            'migrate_coef_id' => 'required|exists:coefficients,id',
            'migrate_coef_value' => 'required',
            'migrate_groupe_matiere' => 'required',
            'migrate_current_year_id' => 'required|exists:annee_scolaires,id'
        ], [
            'migrate_year_id.required' => 'Aucune année selectionnée',
            'migrate_coef_id.required' => 'Veuillez selectionnez une configuration',
            'migrate_current_year_id.required' => 'Veuillez activer une année scolaire',
            'migrate_coef_value' => 'La valeur du coefficient n\'est pas ajouté',
            'migrate_groupe_matiere' => 'Le groupe de la matière n\'est pas défini',
        ]);

        // getting data for evaluation
        $y = AnneeScolaire::findOrFail($request->migrate_year_id);

        // checking if the configuration already migrated:
        $coefYear = CoefAnneeScolaire::all()
            ->where('annee_scolaire_id',$request->migrate_year_id)
            ->where('coefficient_id',$request->migrate_coef_id)
            ->first();

        if($coefYear) {
            return response()->json([
                'error' => 'La configuration a déjà été inclu pour l\'année : '.$y->libelleAnneeScolaire
            ]);
        } else {
            $newCoefYear = CoefAnneeScolaire::create([
                'coefficient_id' => $request->migrate_coef_id,
                'annee_scolaire_id' => $request->migrate_year_id,
                'coefficient_value' => $request->migrate_coef_value,
                'groupe_matiere' => $request->migrate_groupe_matiere,
            ]);

            if($newCoefYear) {
                return response()->json(['success' => 'Configuration migré avec succès']);
            } else {
                return response()->json(['error' => 'Impossible de faire migrer la configuration']);
            }
        }
    }
}
