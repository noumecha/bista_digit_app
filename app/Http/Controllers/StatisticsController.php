<?php

namespace App\Http\Controllers;

use App\Models\AnneeScolaire;
use PDF;
use App\Models\Bulletin;
use App\Models\Classe;
use App\Models\PublishedStatistic;
use App\Models\Trimestre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StatisticsController extends Controller
{
    /**
     * index
     */
    public function index(Request $request)
    {
        // base datas
        $trimestres = Trimestre::all();
        $classes = Classe::with('students')->get();
        $query = Bulletin::query()->where('annee_scolaire_id', getCurrentYear()->id)
            ->where('type_bulletin', 'trimestre');
        // filters
        $trimestreFilter = $request->input('trimestre_id');
        $classFilter = $request->input('classe_id');
        if(!empty($trimestreFilter)) {
            $query->where('trimestre_id', $trimestreFilter);
        }
        if(!empty($classFilter)) {
            $query->where('classe_id', $classFilter);
        }
        $bulletins = $query->orderBy('average', 'desc')->paginate(10);
        // return
        if($request->ajax()) {
            return view('statistics.partials.results-table', compact('bulletins','trimestres','classes'));
        } else {
            return view('statistics.index', compact('bulletins','trimestres','classes'));
        }
    }

    /**
     * export as a pdf
     */
    public function exportPDF(Request $request)
    {   $request->validate([
            'trimestre_id' => 'required|exists:trimestres,id',
            'classe_id' => 'required|exists:classes,id',
        ],  [
            'trimestre_id.required' => 'Veuillez selectionnez un trimestre',
            'classe_id.required' => 'Veuillez selectionnez une classe'
        ]);
        $trimestre = Trimestre::find($request->trimestre_id);
        $classe = Classe::find($request->classe_id);
        $query = Bulletin::query()->where('annee_scolaire_id', getCurrentYear()->id)
            ->where('type_bulletin', 'trimestre');
        if(!empty($request->input('trimestre_id'))) {
            $query->where('trimestre_id', $request->input('trimestre_id'));
        }
        if(!empty($request->input('classe_id'))) {
            $query->where('classe_id', $request->input('classe_id'));
        }
        $bulletins = $query->orderBy('average', 'desc')->get();
        if($bulletins->isEmpty()) {
            return redirect()->route('statistics.index')->with(
                'deleteSuccess', 'Données non disponibles pour la génération du PDF!'
            );
        }
        $data = [
            'trimestre' => $trimestre,
            'classe' => $classe,
            'bulletins' => $bulletins,
        ];
        $pdf = PDF::loadView('statistics.pdf.results', $data);
        return $pdf->download(
            'statistiques_'.$data['trimestre']->libelleTrimestre.'_'
            .$data['classe']->libClasse.'.pdf');
    }

    /**
     * Publish trimestrial stats
     */
    public function publish(Request $request)
    {
        try {
            $request->validate([
                'trimestre_id' => 'required|exists:trimestres,id',
                'classe_id' => 'required|exists:classes,id',
            ],  [
                'trimestre_id.required' => 'Veuillez selectionnez un trimestre',
                'classe_id.required' => 'Veuillez selectionnez une classe'
            ]);
            // Check if already published
            if (PublishedStatistic::where('trimestre_id', $request->trimestre_id)
                ->where('classe_id', $request->classe_id)
                ->where('type', 'trimestriel')
                ->exists()) {
                return response()->json([
                    'error' => 'Ces statistiques ont déjà été publiées'
                ]);
            }
            // Get the data to publish
            $bulletins = Bulletin::where('trimestre_id', $request->trimestre_id)
                ->where('type_bulletin', 'trimestre')
                ->where('classe_id', $request->classe_id)
                ->orderBy('average', 'desc')->get();
            if($bulletins->isEmpty()) {
                return response()->json([
                    'error' => 'Aucunes données disponibles pour la publication!'
                ]);
            }
            // Publish
            $stat = PublishedStatistic::create([
                'type' => 'trimestriel',
                'trimestre_id' => $request->trimestre_id,
                'classe_id' => $request->classe_id,
                'data' => $bulletins,
                'is_published' => true,
                'annee_scolaire_id' => getCurrentYear()->id,
                'user_id' => Auth::id()
            ]);
            if ($stat) {
                return response()->json([
                    'success' => 'Statistiques publiées avec succès'
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'error' => 'Erreur lors de la publication : '.$th->getMessage()
            ]);
        }
    }

    /**
     * Manage OBC classement
     */
    public function obc(Request $request)
    {
        $schoolYears = AnneeScolaire::orderBy('id', 'desc')->get();
        $trimestres = Trimestre::all()->where('annee_soclaire_id', getCurrentYear()->id);
        $classes = Classe::all();
        // filter vars
        $trimestreFilter = $request->input('trimestreFilter');
        $classFilter = $request->input('classFilter');
        $yearFilter = $request->input('yearFilter');
        // querying
        $query = PublishedStatistic::query()->where('type', 'obc');
        // filtering
        if(!empty($trimestreFilter)) {
            $query->where('trimestre_id', $trimestreFilter);
        }
        if(!empty($classFilter)) {
            $query->where('classe_id', $classFilter);
        }
        if(!empty($yearFilter)) {
            $query->where('annee_scolaire_id', $yearFilter);
        }
        $obcStats = $query->orderBy('annee_scolaire_id', 'desc')
            ->paginate(10);

        if($request->ajax()) {
            return view('statistics.partials.obc-stats-table', compact('trimestres', 'classes', 'schoolYears', 'obcStats'));
        } else {
            return view('statistics.obc', compact('trimestres', 'classes', 'schoolYears', 'obcStats'));
        }
    }

    /**
     * Store OBC classement
     */
    public function storeOBC(Request $request)
    {
        $request->validate([
            'annee_scolaire_id' => 'required|integer',
            'obc_rank' => 'required|integer|min:1',
            'total_schools' => 'required|integer|min:1'
        ], [
            'annee_scolaire_id.required' => 'Veuillez selectionner une année scolaire',
            'obc_rank.required' => 'Veuillez ajouter le rang de l\'établissement',
            'total_schools.required' => 'Veuillez ajouter le nombre total d\'établissements'
        ]);
        try {
            if ($request->obc_rank > $request->total_schools) {
                return response()->json([
                    'error' => 'Le rang ne peut pas etre supérieur au nombre total d\'établissements'
                ]);
            }
            if (PublishedStatistic::where('type', 'obc')
                ->where('annee_scolaire_id', $request->annee_scolaire_id)
                ->exists()) {
                return response()->json([
                    'error' => 'Le classement pour cette année existe déjà'
                ]);
            }
            PublishedStatistic::create([
                'type' => 'obc',
                'annee_scolaire_id' => $request->annee_scolaire_id,
                'obc_rank' => $request->obc_rank,
                'data' => ['total_schools' => $request->total_schools],
                'is_published' => true,
                'user_id' => Auth::id()
            ]);
            return response()->json(['success' => 'Classement OBC enregistré avec succès !']);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => 'Erreur lors de l\'enregistrement : '.$th->getMessage()
            ]);
        }
    }

    /**
     * edit OBC stats
     */
    public function editOBC($id) {
        $statToEdit = PublishedStatistic::findOrFail($id);
        return response()->json([
            'statToEdit' => $statToEdit,
        ]);
    }
    /**
     * update OBC stats
     */
    public function updateOBC(Request $request, $id) {
        try {
            $request->validate([
                'obc_rank' => 'required|integer|min:1',
                'total_schools' => 'required|integer|min:1'
            ], [
                'obc_rank.required' => 'Veuillez ajouter le rang de l\'établissement',
                'total_schools.required' => 'Veuillez ajouter le nombre total d\'établissements'
            ]);
            if ($request->obc_rank > $request->total_schools) {
                return response()->json([
                    'error' => 'Le rang ne peut pas etre supérieur au nombre total d\'établissements'
                ]);
            }
            $obcStat = PublishedStatistic::findOrFail($id);
            $obcStat->update([
                'obc_rank' => $request->obc_rank,
                'data' => ['total_schools' => $request->total_schools]
            ]);
            return response()->json(['success' => 'Classement mis à jour avec succès']);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => 'Erreur lors de la mise à jour '.$th->getMessage()
            ]);
        }
    }
    /**
     * delete OBC stats
     */
    public function destroyOBC($id) {
        $specialite = PublishedStatistic::findOrFail($id);
        $specialite->delete();
        return redirect()->route('statistics.obc')->with('deleteSuccess', 'Classement supprimé avec succès !');
    }
}