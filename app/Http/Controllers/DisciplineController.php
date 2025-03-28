<?php

namespace App\Http\Controllers;

use App\Models\AnneeScolaire;
use App\Models\Classe;
use App\Models\ClasseAnneeScolaireStudent;
use App\Models\Discipline;
use App\Models\Evaluation;
use App\Models\Trimestre;
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
        $trimestreIds = Trimestre::all()->where('annee_scolaire_id',getCurrentYear()->id)->pluck('id');
        $evaluations = Evaluation::all()->whereIn('trimestre_id', $trimestreIds);
        $activeYear = AnneeScolaire::all()->where('statut','=', true)->first();
        // filter vars
        $classeFilter = $request->input('classeFilter');
        $searchDiscipline = $request->input('searchDiscipline');
        $monthFilter = $request->input('monthFilter');
        $evaluationFilter = $request->input('evaluationFilter');
        // querying
        $query = Discipline::query()->where('annee_scolaire_id', $activeYear->id);

        // filtering
        if(!empty($searchDiscipline)) {
            $query->where(function ($q) use ($searchDiscipline) {
                $q->whereHas('eleve', function ($studentQuery) use ($searchDiscipline) {
                    $studentQuery->where('name', 'LIKE', "%{$searchDiscipline}%")
                                 ->orWhere('surname', 'LIKE', "%{$searchDiscipline}%");
                });
            })->orWhere('total_absences',$searchDiscipline);
        }
        if(!empty($evaluationFilter)) {
            $query->where('evaluation_id', $evaluationFilter);
        }
        if(!empty($monthFilter)) {
            $query->where('mois', $monthFilter);
        }
        if(!empty($classeFilter)) {
            $query->where('classe_id', $classeFilter);
        }
        $disciplines = $query->paginate(10);

        if($request->ajax()) {
            return view('partials._disciplines_table', compact('evaluations','disciplines','classes','activeYear'));
        } else {
            return view('education.discipline', compact('evaluations','disciplines','classes','activeYear'));
        }
    }

    /**
     * getting all students of a specific class base on classe filter
     */
    public function getStudents($classe_id)
    {
        $currrentActiveYear = AnneeScolaire::all()->where('statut','=', true)->first();
        $elevesAnneeScolaire = ClasseAnneeScolaireStudent::all()
            ->where('annee_scolaire_id', $currrentActiveYear->id)
            ->where('classe_id', $classe_id)
            ->pluck('user_id');
        $eleves = User::where('typeUser','eleve')
            ->whereIn('id',$elevesAnneeScolaire)->get();
        return response()->json($eleves);
    }

    /**
     * getting student base on id when editing
     */
    public function getStudent($user_id)
    {
        $eleve = User::where('id', $user_id)->where('typeUser','eleve')->get();
        return response()->json($eleve);
    }

    /**
     * saving discipline datas
     */
    public function store(Request $request) {
        $rules = [
            'user_id' => 'required|exists:users,id',
            'classe_id' => 'required|exists:classes,id',
            'evaluation_id' => 'required|exists:evaluations,id',
            'annee_scolaire_id' => 'required|exists:annee_scolaires,id',
            'mois' => 'required',
            'heures_absence' => 'required|integer|min:0',
            'heures_justifiees' => [
                'required',
                'integer',
                'min:0',
                function ($attribute, $value, $fail) use ($request) {
                    if ($value > $request->heures_absence) {
                        $fail("Le nombre d'heures justifiées ne peut pas être supérieur au nombre d'heures d'absences.");
                    }
                },
            ],
            'decision' => 'nullable|string|max:255'
        ];
        $messages = [
            'user_id' => 'Veuillez Selectionnez un élève',
            'classe_id' => 'Veuillez Selectionnez une classe',
            'annee_scolaire_id' => 'Veuillez activez une année scolaire',
            'evaluation_id' => 'Veuillez selectionnez une séquence',
            'mois' => 'Veuillez Selectionnez un mois',
            'heures_absence' => 'Veuillez entrées le total des heures d\'absences',
            'heures_justifiees' => $request->heures_justifiees > $request->heures_absence
            ? 'Le nombre d\'heures justifiées ne peut pas être supérieur au nombre d\'heures d\'absences.'
            : 'Veuillez entrées le total des heures d\'absences justifiées',
        ];

        // check if the configuration already exists
        $existingDiscipline = Discipline::where('classe_id', $request->classe_id)
            ->where('user_id', $request->user_id)
            ->where('mois', $request->mois)
            ->where('annee_scolaire_id',$request->annee_scolaire_id)->first();
        if ($existingDiscipline) {
            return response()->json(['error' => 'Un état disciplinaire pour cet élève existe déjà pour ce mois']);
        }

        $totalAbsences = $request->heures_absence - $request->heures_justifiees;

        // somes rules
        if($totalAbsences > 40) {
            $rules['decision'] ='required|string';
            $messages['decision.required'] = "Veuillez renseigner la décision";
        }

        $request->validate($rules, $messages);

        $avertissement = null;
        if ($totalAbsences == 40) {
            $avertissement = "Avertissement Conduite";
        } elseif ($totalAbsences >= 30 && $totalAbsences < 40) {
            $avertissement = "Blâme";
        } elseif ($totalAbsences > 40) {
            $avertissement = "Avertisement Blâme";
        }
        $discipline = Discipline::create([
            'user_id' => $request->user_id,
            'mois' => $request->mois,
            'annee_scolaire_id' => $request->annee_scolaire_id,
            'classe_id' => $request->classe_id,
            'evaluation_id' => $request->evaluation_id,
            'heures_absence' => $request->heures_absence,
            'heures_justifiees' => $request->heures_justifiees,
            'total_absences' => $totalAbsences,
            'avertissement' => $avertissement,
            'decision' => $request->decision,
        ]);
        // then show the message
        if($discipline) {
            return response()->json(['success' => 'Données de discipline ajoutées avec succès']);
        } else {
            return response()->json(['error' => 'Erreur inconue lors de l\'enregistrement des données de discipline']);
        }
    }

    /**
     * edit a specific discipline data
     */
    public function edit($id) {
        $disciplineToEdit = Discipline::findOrFail($id);
        return response()->json([
            'disciplineToEdit' => $disciplineToEdit
        ]);
    }

    /**
     * update specific discipline datas
     */
    public function update(Request $request, $id) {
        $rules = [
            'heures_absence' => 'required|integer|min:0',
            'heures_justifiees' => [
                'required',
                'integer',
                'min:0',
                function ($attribute, $value, $fail) use ($request) {
                    if ($value > $request->heures_absence) {
                        $fail("Le nombre d'heures justifiées ne peut pas être supérieur au nombre d'heures d'absences.");
                    }
                },
            ],
            'decision' => 'nullable|string|max:255'
        ];
        $messages = [
            'heures_absence' => 'Veuillez entrées le total des heures d\'absences',
            'heures_justifiees' => $request->heures_justifiees > $request->heures_absence
            ? 'Le nombre d\'heures justifiées ne peut pas être supérieur au nombre d\'heures d\'absences.'
            : 'Veuillez entrées le total des heures d\'absences justifiées',
        ];

        $totalAbsences = $request->heures_absence - $request->heures_justifiees;

        $avertissement = null;
        if ($totalAbsences == 40) {
            $avertissement = "Avertissement Conduite";
        } elseif ($totalAbsences >= 30 && $totalAbsences < 40) {
            $avertissement = "Blâme";
        } elseif ($totalAbsences > 40) {
            $avertissement = "Avertisement Blâme";
        }

        // some rules
        if($totalAbsences > 40) {
            $rules['decision'] ='required|string';
            $messages['decision.required'] = "Veuillez renseigner la décision";
        }

        $request->validate($rules, $messages);

        $discipline = Discipline::find($id);
        $discipline->update([
            'heures_absence' => $request->heures_absence,
            'heures_justifiees' => $request->heures_justifiees,
            'total_absences' => $totalAbsences,
            'avertissement' => $avertissement,
            'decision' => $totalAbsences > 40 ? $request->decision : null,
        ]);

        if($discipline) {
            return response()->json(['success' => 'Données de discipline mises à jour avec succès']);
        } else {
            return response()->json(['error' => 'Erreur inconue lors de l\'enregistrement des données de discipline']);
        }
    }

    /**
     * deleting specific discipline record from database
     */
    public function destroy($id) {
        $discipline = Discipline::findOrFail($id);
        $student = User::where('id', $discipline->user_id)->first();
        $discipline->delete();
        return redirect()->route('education.discipline')->with('deleteSuccess', 'Données de discipline de l\'élève '.$student->name.' supprimées avec succès');
    }
}
