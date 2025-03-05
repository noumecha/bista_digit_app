<?php

namespace App\Http\Controllers;

use App\Models\AnneeScolaire;
use App\Models\AppConfiguration;
use App\Models\Bulletin;
use App\Models\Classe;
use App\Models\Evaluation;
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
        ], [
            'classe_id.required' => 'Veuillez selectionnez une classe',
            'evaluation_id.required' => 'Veuillez selectionnez une évaluation',
            'trimestre_id.required' => 'Veuillez selectionnez une trimestre',
        ]);
        // Vérifier que l'utilisateur est un administrateur
        if (!Auth::user()->typeUser === 'admin') {
            return back()->with('error', 'Vous n\'avez pas les autorisations.');
        }

        // Récupérer la classe et l’évaluation
        $classe = Classe::findOrFail($request->classe_id);
        $evaluation = Evaluation::findOrFail($request->evaluation_id);

        // Vérifier que chaque élève a une note dans toutes les matières
        foreach ($classe->eleves as $eleve) {
            foreach ($classe->matieres as $matiere) {
                if (!Note::where('user_id', $eleve->id)
                         ->where('matiere_id', $matiere->id)
                         ->where('evaluation_id', $evaluation->id)
                         ->exists()) {
                    return back()->with('error', "L'élève {$eleve->name} n'a pas de note en {$matiere->libelle}.");
                }
            }
        }

        // Générer les PDF pour chaque élève
        foreach ($classe->eleves as $eleve) {
            $data = [
                'config' => $appConfig,
                'eleve' => $eleve,
                'classe' => $classe,
                'evaluation' => $evaluation,
                'notes' => Note::where('user_id', $eleve->id)
                               ->where('evaluation_id', $evaluation->id)
                               ->get()
            ];
            dd($data);
            /*$pdf = PDF::loadView('bulletins.template', $data);
            $pdf->save(storage_path("app/bulletins/{$eleve->id}_{$evaluation->id}.pdf"));*/
        }

        return back()->with('success', "Les bulletins ont été générés avec succès !");
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
}
