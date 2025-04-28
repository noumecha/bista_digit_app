<?php

namespace App\Http\Controllers;

use App\Models\AnneeScolaire;
use App\Models\Classe;
use App\Models\ClasseAnneeScolaireStudent;
use App\Models\ConseilDiscipline;
use App\Models\Evaluation;
use App\Models\Trimestre;
use App\Models\User;
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
        $rules = [
            'user_id' => 'required|exists:users,id',
            'evaluation_id' => 'required|exists:evaluations,id',
            'annee_scolaire_id' => 'required|exists:annee_scolaires,id',
            'mois' => 'required',
            'date_conseil' => 'required|max:255',
            'motif' => 'required|string|max:255',
            'decision' => 'required|string'
        ];
        $messages = [
            'user_id' => 'Veuillez Selectionnez un élève',
            'annee_scolaire_id' => 'Veuillez activez une année scolaire',
            'evaluation_id' => 'Veuillez selectionnez une séquence',
            'mois' => 'Veuillez Selectionnez un mois',
            'date_conseil' => 'Veuillez entre la date du conseil de discipline',
            'motif' => "Veuillez entrez le motif du conseil de discipline",
            'decision' => "Veuillez renseigner la décision"
        ];
        $request->validate($rules, $messages);
        $conseildiscipline = ConseilDiscipline::create([
            'user_id' => $request->user_id,
            'mois' => $request->mois,
            'annee_scolaire_id' => $request->annee_scolaire_id,
            'evaluation_id' => $request->evaluation_id,
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
        return response()->json([
            'dataToEdit' => $dataToEdit,
            'dateDeDebutTrim' => $trimestre->dateDeDebut,
            'dateDeFinTrim' => $trimestre->dateDeDebut
        ]);
    }

    /**
     * update conseils discipline datas
     */
    public function update(Request $request, $id) {
        $conseildiscipline = ConseilDiscipline::findOrFail($id);
        $rules = [
            'motif' => 'required|string|max:255',
            'decision' => 'required|string',
            'date_conseil' => 'required|max:255',
        ];
        $messages = [
            "decision" => "Veuillez renseigner la décision",
            "motif" => "Veuillez renseigner le motif",
            "date_conseil" => "Veuillez renseigner la date du conseil de discipline"
        ];
        $request->validate($rules, $messages);
        $conseildiscipline->update([
            'decision' => $request->decision,
            'motif' => $request->motif,
        ]);

        if($conseildiscipline) {
            return response()->json(['success' => 'Rapport conseil de disciplines mis à jour avec succès']);
        } else {
            return response()->json(['error' => 'Erreur inconue lors de l\'enregistrement ! réssayer']);        }
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
        $evaluation = Evaluation::all()->where('id',$evalId)->first();
        $trimestre = Trimestre::findOrFail($evaluation->trimestre_id);
        return response()->json([
            'dateDeDebutTrim' => $trimestre->dateDeDebut,
            'dateDeFinTrim' => $trimestre->dateDeFin,
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
