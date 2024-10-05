<?php

namespace App\Http\Controllers;

use App\Models\AnneeScolaire;
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
    public function trimestres() {
        $trimestres = Trimestre::all();
        $anneeScolaires = AnneeScolaire::all();

        $query = Trimestre::query();
        $trimestres = $query->paginate(10);

        return view('evaluation.trimestres', compact('trimestres', 'anneeScolaires'));
    }

    /**
     *
     */
    public function trimestresStore(Request $request) {
        $request->validate([
            'libelleTrimestre' => 'required|min:3|max:255',
            'annee_scolaire_id' => 'required',
        ], [
            'libelleTrimestre.required' => 'Veuillez entrez un libelle pour le trimestre',
            'annee_scolaire_id.required' => 'Selectionnez une année scolaire',
        ]);

        Trimestre::create([
            'libelleTrimestre' => $request->libelleTrimestre,
            'annee_scolaire_id' => $request->annee_scolaire_id,
        ]);

        return redirect()->route('evaluation.trimestres')->with('success', 'Trimestre ajouté avec succès');
    }

    /**
     *
     */
    public function trimestresEdit(Request $request, $id) {
        $trimestres = Trimestre::all();
        $anneeScolaires = AnneeScolaire::all();
        $trimestreToEdit = Trimestre::findOrFail($id);

        $query = Trimestre::query();
        $trimestres = $query->paginate(10);

        return view('evaluation.trimestres', compact('trimestres', 'anneeScolaires','trimestreToEdit'));
    }

    /**
     *
     */
    public function trimestresUpdate(Request $request, $id) {
        $request->validate([
            'libelleTrimestre' => 'required|min:3|max:255',
            'annee_scolaire_id' => 'required',
        ], [
            'libelleTrimestre.required' => 'Veuillez entrez un libelle pour le trimestre',
            'annee_scolaire_id.required' => 'Selectionnez une année scolaire',
        ]);
        $trimestre = Trimestre::findOrFail($id);

        $trimestre->update($request->all());

        return redirect()->route('evaluation.trimestres')->with('success', 'Trimestre mis à jour avec succès');
    }


    /**
     *
     */
    public function trimestresDestroy($id) {
        $trimestre = Trimestre::findOrFail($id);
        $trimestre->delete();

        return redirect()->route('evaluation.trimestres')->with('success', 'Trimestre supprimé avec succès');
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
