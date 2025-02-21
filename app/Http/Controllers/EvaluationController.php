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
        $user = User::find(Auth::id());

        return view('evaluation.notes', compact('user'));
    }

    /**
     *
     */
    public function evaluations() {
        $evaluations = Evaluation::all();
        $trimestres = Trimestre::all();

        $query = Evaluation::query();
        $evaluations = $query->paginate(10);

        return view('evaluation.evaluations', compact('evaluations', 'trimestres'));
    }

    /**
     *
     */
    public function evaluationsStore(Request $request) {
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
     *
     */
    public function evaluationsEdit($id) {
        $evaluations = Evaluation::all();
        $trimestres = Trimestre::all();
        $evaluationToEdit = Evaluation::findOrFail($id);

        $query = Evaluation::query();
        $evaluations = $query->paginate(10);

        return view('evaluation.evaluations', compact('evaluation', 'trimestres','evaluationToEdit'));
    }

    /**
     *
     */
    public function evaluationsUpdate(Request $request, $id) {
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
     *
     */
    public function evaluationsDestroy($id) {
        $evaluation = Evaluation::findOrFail($id);
        $evaluation->delete();

        return redirect()->route('evaluation.evaluations')->with('success', 'Evaluation supprimée avec succès');
    }
}
