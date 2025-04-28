<?php

namespace App\Http\Controllers;

use App\Models\AnneeScolaire;
use App\Models\Classe;
use App\Models\ClasseAnneeScolaireStudent;
use App\Models\ConseilDiscipline;
use App\Models\Evaluation;
use App\Models\Trimestre;
use App\Models\User;
use DateTime;
use Exception;
use Illuminate\Http\Request;

class ConseilDisciplineController extends Controller
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
        $searchText = $request->input('searchText');
        $monthFilter = $request->input('monthFilter');
        $evaluationFilter = $request->input('evaluationFilter');
        // querying
        $query = ConseilDiscipline::query()->where('annee_scolaire_id', $activeYear->id);

        // filtering
        if(!empty($searchText)) {
            $query->where(function ($q) use ($searchText) {
                $q->whereHas('eleve', function ($studentQuery) use ($searchText) {
                    $studentQuery->where('name', 'LIKE', "%{$searchText}%")
                                 ->orWhere('surname', 'LIKE', "%{$searchText}%");
                });
            })->orWhere('total_absences',$searchText);
        }
        if(!empty($evaluationFilter)) {
            $query->where('evaluation_id', $evaluationFilter);
        }
        if(!empty($monthFilter)) {
            $query->where('mois', $monthFilter);
        }
        $conseilDisciplines = $query->paginate(10);

        if($request->ajax()) {
            return view('partials._conseildisciplines_table', compact('evaluations','classes','conseilDisciplines','activeYear'));
        } else {
            return view('education.conseildisciplines', compact('evaluations','classes','conseilDisciplines','activeYear'));
        }
    }

    /**
     * saving conseils discipline datas
     */
    public function store(Request $request) {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'evaluation_id' => 'required|exists:evaluations,id',
            'annee_scolaire_id' => 'required|exists:annee_scolaires,id',
            'mois' => 'required',
            'motif' => 'required|string|max:255',
            'decision' => 'required|string',
            'date_conseil' => [
                'required','max:255',
                function ($attribute, $value, $fail) use ($request) {
                    $date = new DateTime($value);
                    if(($date->format('m') !== $request->mois)) {
                        $fail("La date selectionnée ne correspond pas au mois choisi !");
                    }
                }
            ],
        ],[
            'user_id.required' => 'Veuillez Selectionnez un élève',
            'annee_scolaire_id.required' => 'Veuillez activez une année scolaire',
            'evaluation_id.required' => 'Veuillez selectionnez une séquence',
            'mois.required' => 'Veuillez Selectionnez un mois',
            'date_conseil.required' => 'Veuillez entre la date du conseil de discipline',
            'motif.required' => "Veuillez entrez le motif du conseil de discipline",
            'decision.required' => "Veuillez renseigner la décision"
        ]);
        // check if the configuration already exists
        $exists = ConseilDiscipline::where('user_id', $request->user_id)
            ->where('mois', $request->mois)
            ->where('annee_scolaire_id',getCurrentYear()->id)->first();
        if ($exists) {
            return response()->json([
                'error' => 'Un conseil de discipline pour cet élève existe déjà pour ce mois'
            ]);
        }
        $conseildiscipline = ConseilDiscipline::create([
            'user_id' => $request->user_id,
            'mois' => $request->mois,
            'annee_scolaire_id' => $request->annee_scolaire_id,
            'evaluation_id' => $request->evaluation_id,
            'date_conseil' => $request->date_conseil,
            'motif' => $request->motif,
            'decision' => $request->decision,
        ]);
        // then show the message
        if($conseildiscipline) {
            return response()->json(['success' => 'Rapport de conseil de discipline enregistré avec succès']);
        } else {
            return response()->json(['error' => 'Erreur inconue lors de l\'enregistrement du rapport de conseil de discipline']);
        }
    }

    /**
     * edit a specific discipline data
     */
    public function edit($id) {
        $dataToEdit = ConseilDiscipline::findOrFail($id);
        $evaluation = Evaluation::where('id',$dataToEdit->evaluation_id)->first();
        $trimestre = Trimestre::findOrFail($evaluation->trimestre_id);
        $monthId = $dataToEdit->mois;
        return response()->json([
            'dataToEdit' => $dataToEdit,
            'dateDeDebutTrim' => $trimestre->dateDeDebut,
            'dateDeFinTrim' => $trimestre->dateDeDebut,
            'monthId' => $monthId,
            'monthName' => monthNameToFrench($monthId)
        ]);
    }

    /**
     * update conseils discipline datas
     */
    public function update(Request $request, $id) {
        try {
            $conseildiscipline = ConseilDiscipline::findOrFail($id);
            $request->validate([
                'motif' => 'required|string|max:255',
                'decision' => 'required|string',
                'date_conseil' => [
                    'required','max:255',
                    function ($attribute, $value, $fail) use ($request) {
                        $date = new DateTime($value);
                        if(($date->format('m') !== $request->mois)) {
                            $fail("La date selectionnée ne correspond pas au mois choisi !");
                        }
                    }
                ],
            ],[
                "decision.required" => "Veuillez renseigner la décision",
                "motif.required" => "Veuillez renseigner le motif",
                "date_conseil.required" => "Veuillez renseigner la date du conseil de discipline"
            ]);
            $conseildiscipline->update([
                'decision' => $request->decision,
                'motif' => $request->motif,
                'date_conseil' => $request->date_conseil
            ]);
            return response()->json(['success' => 'Rapport conseil de disciplines mis à jour avec succès']);
        } catch (Exception $ex) {
            return response()->json(['error' => 'Erreur lors de la mise à jour : '.$ex->getMessage()]);
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
     *  get trimestres date
    */
    public function getTrimsDate($evalId) {
        $evaluation = Evaluation::where('id',$evalId)->first();
        $months = [];
        $trimestre = Trimestre::where('id', $evaluation->trimestre_id)->first();
        foreach(getAllSchoolMonths() as $m) {
            if ($m >= new DateTime($trimestre->dateDeDebut) && $m <= new DateTime($trimestre->dateDeFin)) {
                array_push($months, $m);
            }
        }
        foreach($months as $month => $key) {
            $months[$month] = [
                'm' => $key->format("m"),
                'name' => monthNameToFrench($key->format("m")),
            ];
        }
        return response()->json([
            'dateDeDebutTrim' => $trimestre->dateDeDebut,
            'dateDeFinTrim' => $trimestre->dateDeFin,
            'months' => $months
        ]);
    }

    /**
     * delete conseils discipline datas
     */
    public function destroy($id) {
        $conseildiscipline = ConseilDiscipline::findOrFail($id);
        $student = User::where('id', $conseildiscipline->user_id)->first();
        $conseildiscipline->delete();
        return redirect()->route('education.discipline')->with('deleteSuccess', 'Données de discipline de l\'élève '.$student->name.' supprimées avec succès');
    }
}
