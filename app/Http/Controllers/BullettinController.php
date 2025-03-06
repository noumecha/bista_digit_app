<?php

namespace App\Http\Controllers;

use App\Models\AnneeScolaire;
use App\Models\AppConfiguration;
use App\Models\Bulletin;
use App\Models\Classe;
use App\Models\CoefAnneeScolaire;
use App\Models\Coefficient;
use App\Models\Evaluation;
use App\Models\Matiere;
use App\Models\Note;
use App\Models\Trimestre;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BullettinController extends Controller
{
    /**
     * Bulletin lists
     */
    public function index(Request $request)
    {
        $user = User::find(Auth::id());
        $activeYear = AnneeScolaire::all()->where('statut','=', true)->first();
        $evaluations = Evaluation::all();
        $trimestres = Trimestre::all();
        $classes = Classe::all();
        // loading app configuration :
        $appconfiguration = AppConfiguration::all()->last();
        // filter vars
        $evaluationFilter = $request->input('evaluationFilter');
        $trimestreFilter = $request->input('trimestreFilter');
        $classFilter = $request->input('classFilter');
        $searchStudent = $request->input('searchStudent');
        // querying
        $query = Bulletin::query();
        // filtering
        if(!empty($evaluationFilter)) {
            $query->where('evaluation_id', $evaluationFilter);
        }
        if(!empty($trimestreFilter)) {
            $query->where('trimestre_id', $trimestreFilter);
        }
        if(!empty($classFilter)) {
            $query->where('classe_id', $classFilter);
        }
        if(!empty($searchStudent)) {
            $query->whereHas('user_id', function ($q) use ($searchStudent) {
                $q->where('name', 'LIKE', "%{$searchStudent}%")
                ->orWhere('surname', 'LIKE', "%{$searchStudent}%");
            });
        }

        $bulletins = $query->paginate(10);

        if($request->ajax()) {
            return view('partials._bulletins_table', compact('bulletins','appconfiguration','trimestres','evaluations','classes','user'));
        } else {
            return view('bulletin.bulletins', compact('bulletins','appconfiguration','trimestres','evaluations','classes','user'));
        }
    }

    /**
     * generate bulletin for a specific student
     */
    public function generate(Request $request) {
        dd($request);
        $request->validate([
            'evaluation_id' => 'required',
            'trimestre_id' => 'required',
            'classe_id' => 'required',
        ], [
            'classe_id.required' => 'Veuillez selectionnez une classe',
            'evaluation_id.required' => 'Veuillez selectionnez une évaluation',
            'trimestre_id.required' => 'Veuillez selectionnez une trimestre',
        ]);
    }

    /**
     * generate bulletin for all students in specific classe
     */
    public function generateAll(Request $request) {
        // checking on
        dd($request);
        // load schoolYear
        $activeYear = AnneeScolaire::all()->where('statut','=', true)->first();
        // load appConfiguration
        $appConfig = AppConfiguration::first();

        $request->validate([
            'evaluation_id' => 'required',
            'trimestre_id' => 'required',
            'classe_id' => 'required',
            'type_bulletin' => 'required',
        ], [
            'classe_id.required' => 'Veuillez selectionnez une classe',
            'evaluation_id.required' => 'Veuillez selectionnez une évaluation',
            'trimestre_id.required' => 'Veuillez selectionnez une trimestre',
            'type_bulletin.required' => 'Veuillez selectionnez le type de bulletin',
        ]);
        // getting classe and evaluation
        $classe = Classe::findOrFail($request->classe_id);
        $evaluation = Evaluation::findOrFail($request->evaluation_id)
        ->where('trimestre_id', $request->trimestre_id);
        $matieres = Matiere::all()->whereIn('id', getCurrentYearCoefConfigurationMatId($activeYear->id));
        // check if all user in the specified class as note in every corresponding evaluation matiere
        foreach ($classe->students as $student) {
            foreach ($matieres as $matiere) {
                if (!Note::where('user_id', $student->id)
                         ->where('matiere_id', $matiere->id)
                         ->where('evaluation_id', $evaluation->id)
                         ->exists()) {
                    return back()->with('error', "L'élève {$student->name} n'a pas de note en {$matiere->libelleMatiere}.");
                }
            }
        }

        // genrate bulletin and pdf for each student
        $firstGroupMatiereIds = Matiere::all()->whereIn('id', getGroupeMatieresIds('1er groupe', $activeYear->id));
        $sndGroupMatiereIds = Matiere::all()->whereIn('id', getGroupeMatieresIds('2e groupe', $activeYear->id));
        $thirdGroupMatiereIds = Matiere::all()->whereIn('id', getGroupeMatieresIds('3e groupe', $activeYear->id));
        foreach ($classe->students as $student) {
            // create the pdf file first
            /* getting all notes & all notes by matiere group */
            $notes = Note::where('user_id', $student->id)
            ->where('evaluation_id', $evaluation->id)
            ->get();
            $studentNotesFirstGroup = Note::where('user_id', $student->id)
                ->where('evaluation_id', $evaluation->id)
                ->whereIn('matiere_id', $firstGroupMatiereIds)
                ->get();
            $studentNotesSndGroup = Note::where('user_id', $student->id)
                ->where('evaluation_id', $evaluation->id)
                ->whereIn('matiere_id', $sndGroupMatiereIds)
                ->get();
            $studentNotesThirdGroup = Note::where('user_id', $student->id)
                ->where('evaluation_id', $evaluation->id)
                ->whereIn('matiere_id', $thirdGroupMatiereIds)
                ->get();
            /** make the necessary calculation */
            $coefsValues = [];
            foreach ($notes as $note) {
                $coef = Coefficient::all()->where('matiere_id', $note->matiere_id)->
                    where('annee_scolaire_id', $activeYear->id);
                $coefValue = CoefAnneeScolaire::where('coefficient_id', $coef->id)->get();
                array_push($coefsValues, $coefValue->coefficient_value);
            }
            $notesValues = [];
            foreach ($notes as $note) {
                array_push($notesValues, $note->note);
            }
            $average = getAverage($coefsValues, $notesValues); // calculate the current student average
            $appreciation = getAppreciation($average); // define the appreciation base on the average
            $averages = [];
            $averagesData = Bulletin::all()->where('classe_id',$request->classe_id)
                ->where('evaluation_id',$request->evaluation_id)
                ->where('trimestre_id',$request->trimestre_id);
            foreach($averagesData as $averageData) {
                array_push($averageData->average, $notes);
            }
            array_push($averages, $average); // adding the new average
            $range = getRange($average, $averages); // finally get the range
            $gcma = getGeneralMoy($averages); // get the general class average of the subject
            $minValue = min($averages);
            $maxValue = max($averages);
            $sd = getStandardDeviation($averages);

            // create bulletin base on the selected type
            if($request->type_bulletin === 'evaluation') {
                $data = [
                    'config' => $appConfig,
                    'annee_scolaire' => $activeYear,
                    'student' => $student,
                    'classe' => $classe,
                    'evaluation' => $evaluation,
                    'trimestre' => $trimestre,
                    'notes' => $notes,
                    'notesFirstGroup' => $studentNotesFirstGroup,
                    'notesSndGroup' => $studentNotesSndGroup,
                    'notesThirdGroup' => $studentNotesThirdGroup,
                    'type_bulletin' => $request->type_bulletin,
                    'discipline' => $student->discipline,
                    'avg' => $average,
                    'appreciation' => $appreciation,
                    'range' => $range,
                    'min_average' => $minValue,
                    'max_average' => $maxValue,
                    'general_average' => $gcma,
                    'standard_deviation' => $sd,
                ];
            } else if ($request->type_bulletin === 'trimestre') {
            } else {
            }

            // starting the Bulletin generation
            Bulletin::create([
                'user_id' => $student->id,
                'classe_id' => $request->classe_id,
                'app_configuration_id' => $appConfig->id,
                'annee_scolaire_id' => $activeYear->id,
                'bulletin_file' => $student->bulletin_file, // to manage
                'type_bulletin' => $request->type_bulletin,
                'evaluation_id' => $request->evaluation_id,
                'trimestre_id' => $request->trimestre_id,
                'discipline_id' => $student->discipline->id,
                'appreciation' => $appreciation,
                'average' => $average,
                'min_average' => $minValue,
                'max_average' => $maxValue,
                'general_average' => $gcma,
                'standard_deviation' => $sd,
                'range' => $range,
            ]);
        }

        return back()->with('success', "Les bulletins ont été générés avec succès !");
    }

    /**
     * update a specific bulletin (regenerate it)
     */
    public function udpate(Request $request) {
        dd($request);
    }

    /**
     * Bulletin configuration - only for test purpose
     */
    public function configs()
    {
        $user = User::find(Auth::id());

        //return view('bulletin.annual', compact('user'));
        return view('bulletin.evaluation', compact('user'));
    }

    /**
     * Bulletin deletion
     */
    public function destroy($id) {
        $bulletin = Bulletin::findOrFail($id);
        $bulletin->delete();
        return response()->json(['deleteSuccess' => 'Bulletin supprimé avec succès!']);
    }
}
