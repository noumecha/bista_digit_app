<?php

namespace App\Http\Controllers;

use App\Models\Classe;
use App\Models\Discipline;
use App\Models\User;
use Illuminate\Http\Request;

class DisciplineController extends Controller
{
    /**
     *
     */
    public function index(Request $request) {
        // usefull vars
        $classes = Classe::all();
        // filter vars
        $classeFilter = $request->input('classeFilter');
        $searchDiscipline = $request->input('searchDiscipline');
        // querying
        $query = Discipline::query();

        // filtering
        if(!empty($searchDiscipline)) {
            $query->where(function ($q) use ($searchDiscipline) {
                $q->whereHas('eleve', function ($studentQuery) use ($searchDiscipline) {
                    $studentQuery->where('name', 'LIKE', "%{$searchDiscipline}%")
                                 ->orWhere('surname', 'LIKE', "%{$searchDiscipline}%");
                });
            })->orWhere('total_absences',$searchDiscipline);
        }
        if(!empty($classeFilter)) {
            $query->where('classe_id',"%{$classeFilter}%");
        }

        $disciplines = $query->paginate(10);

        if($request->ajax()) {
            return view('partials._disciplines_table', compact('disciplines','classes'));
        } else {
            return view('education.discipline', compact('disciplines','classes'));
        }
    }

    /**
     * getting student base on classe filter
     */
    public function getStudents($classe_id)
    {
        $eleves = User::where('classe_id', $classe_id)->where('typeUser','eleve')->get();
        return response()->json($eleves);
    }

    /**
     *
     */
    public function store(Request $request) {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'classe_id' => 'required|exists:classes,id',
            'annee_scolaire_id' => 'required|exists:annee_scolaires,id',
            'mois' => 'required|integer|min:1|max:10',
            'heures_absence' => 'required|integer|min:0',
            'heures_justifiees' => 'required|integer|min:0',
            'decision' => 'nullable|string|max:255'
        ], [
            'user_id' => 'Veuillez Selectionnez un élève',
            'classe_id' => 'Veuillez Selectionnez une classe',
            'annee_scolaire_id' => 'Veuillez activez une année scolaire',
            'mois' => 'Veuillez Selectionnez un mois',
            'heures_absence' => 'Veuillez entrées le total des heures d\'absences',
            'heures_justifiees' => 'Veuillez entrées le total des heures d\'absences justifiées',
        ]);

        $totalAbsences = $request->heures_absence - $request->heures_justifiees;

        $avertissement = null;
        if ($totalAbsences == 40) {
            $avertissement = "Avertissement Conduite";
        } elseif ($totalAbsences >= 30) {
            $avertissement = "Blâme";
        } elseif ($totalAbsences > 40) {
            $avertissement = "Avertisement Blâme";
        }

        $discipline = Discipline::create([
            'user_id' => $request->user_id,
            'mois' => $request->mois,
            'annee_scolaire_id' => $request->annee_scolaire_id,
            'classe_id' => $request->classe_id,
            'heures_absence' => $request->heures_absence,
            'heures_justifiees' => $request->heures_justifiees,
            'total_absences' => $totalAbsences,
            'avertissement' => $avertissement,
            'decision' => $request->decision,
        ]);

        if($discipline) {
            return response()->json(['success' => 'Données de discipline ajoutées avec succès']);
        } else {
            return response()->json(['error' => 'Erreur inconue lors de l\'enregistrement des données de discipline']);
        }
    }

    /**
     *
     */
    public function edit($id) {
        $disciplineToEdit = Discipline::findOrFail($id);
        return response()->json([
            'disciplineToEdit' => $disciplineToEdit
        ]);
    }

    /**
     *
     */
    public function update(Request $request, $id) {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'classe_id' => 'required|exists:classes,id',
            'annee_scolaire_id' => 'required|exists:annee_scolaires,id',
            'mois' => 'required|integer|min:1|max:10',
            'heures_absence' => 'required|integer|min:0',
            'heures_justifiees' => 'required|integer|min:0',
            'decision' => 'nullable|string|max:255'
        ], [
            'user_id' => 'Veuillez Selectionnez un élève',
            'classe_id' => 'Veuillez Selectionnez une classe',
            'annee_scolaire_id' => 'Veuillez activez une année scolaire',
            'mois' => 'Veuillez Selectionnez un mois',
            'heures_absence' => 'Veuillez entrées le total des heures d\'absences',
            'heures_justifiees' => 'Veuillez entrées le total des heures d\'absences justifiées',
        ]);

        $totalAbsences = $request->heures_absence - $request->heures_justifiees;

        $avertissement = null;
        if ($totalAbsences == 40) {
            $avertissement = "Avertissement Conduite";
        } elseif ($totalAbsences >= 30) {
            $avertissement = "Blâme";
        } elseif ($totalAbsences > 40) {
            $avertissement = "Avertisement Blâme";
        }

        $discipline = Discipline::find($id);
        $discipline->update([
            'user_id' => $request->user_id,
            'mois' => $request->mois,
            'annee_scolaire_id' => $request->annee_scolaire_id,
            'classe_id' => $request->classe_id,
            'heures_absence' => $request->heures_absence,
            'heures_justifiees' => $request->heures_justifiees,
            'total_absences' => $totalAbsences,
            'avertissement' => $avertissement,
            'decision' => $request->decision,
        ]);

        if($discipline) {
            return response()->json(['success' => 'Données de discipline ajoutées avec succès']);
        } else {
            return response()->json(['error' => 'Erreur inconue lors de l\'enregistrement des données de discipline']);
        }
    }

    /**
     *
     */
    public function destroy($id) {
        $discipline = Discipline::findOrFail($id);
        $student = User::where('id', $discipline->user_id)->first();
        $discipline->delete();
        return redirect()->route('education.discipline')->with('deleteSuccess', 'Données de discipline de l\'élève '.$student->name.' supprimées avec succès');
    }
}
