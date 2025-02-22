<?php

namespace App\Http\Controllers;

use App\Models\Evaluation;
use App\Models\Trimestre;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EvaluationController extends Controller
{
    /**
     *
     */
    public function index(Request $request)
    {
        $evaluations = Evaluation::all();
        $trimestres = Trimestre::all();

        $query = Evaluation::query();
        $evaluations = $query->paginate(10);

        return view('evaluation.evaluations', compact('evaluations', 'trimestres'));
    }

    /**
     *
     */
    public function store(Request $request) {
        $request->validate([
            'libelleEvaluation' => 'required|min:3|max:255',
            'trimestre_id' => 'required',
        ], [
            'libelleEvaluation.required' => 'Veuillez entrez un libelle pour le trimestre',
            'trimestre_id.required' => 'Selectionnez une année scolaire',
        ]);

        Evaluation::create([
            'libelleEvaluation' => $request->libelleEvaluation,
            'trimestre_id' => $request->trimestre_id,
        ]);

        return redirect()->route('evaluation.evaluations')->with('success', 'Evaluation ajoutée avec succès');
    }

    /**
     * edit specific evaluation
     */
    public function edit($id) {
        $evaluationToEdit = Evaluation::findOrFail($id);
        return response()->json(['evaluationToEdit' => $evaluationToEdit]);
    }

    /**
     * udpate specific evaluation
     */
    public function update(Request $request, $id) {
        $request->validate([
            'libelleEvaluation' => 'required|min:3|max:255',
            'trimestre_id' => 'required',
        ], [
            'libelleEvaluation.required' => 'Veuillez entrez un libelle pour le trimestre',
            'trimestre_id.required' => 'Selectionnez une année scolaire',
        ]);
        $evaluation = Evaluation::findOrFail($id);

        $evaluation->update($request->all());

        return redirect()->route('evaluation.evaluations')->with('success', 'Evaluation mis à jour avec succès');
    }


    /**
     * delete specific evaluationf
     */
    public function destroy($id) {
        $evaluation = Evaluation::findOrFail($id);
        $evaluation->delete();

        return redirect()->route('evaluation.evaluations')->with('success', 'Evaluation supprimée avec succès');
    }
}
