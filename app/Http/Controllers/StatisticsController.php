<?php

namespace App\Http\Controllers;

use PDF;
use App\Models\Bulletin;
use App\Models\Classe;
use App\Models\Trimestre;
use Illuminate\Http\Request;

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
     * generate pdf file
     */
    public function generate(Request $request)
    {
        $request->validate([
            'trimestre_id' => 'required|exists:trimestres,id',
            'classe_id' => 'required|exists:classes,id',
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
        $data = [
            'trimestre' => $trimestre,
            'classe' => $classe,
            'bulletins' => $bulletins,
        ];
        if ($request->has('export_pdf')) {
            return $this->exportPDF($data);
        }
        return view('statistics.results', $data);
    }

    /**
     * export as a pdf
     */
    protected function exportPDF($data)
    {
        $pdf = PDF::loadView('statistics.pdf.results', $data);
        return $pdf->download(
            'statistiques_'.$data['trimestre']->libelleTrimestre.'_'
            .$data['classe']->libClasse.'.pdf');
    }
}