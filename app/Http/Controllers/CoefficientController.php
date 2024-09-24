<?php

namespace App\Http\Controllers;

use App\Models\Classe;
use App\Models\Coefficient;
use App\Models\Matiere;
use Illuminate\Http\Request;

class CoefficientController extends Controller
{
    public function index() {
        $classes = Classe::all();
        $matieres = Matiere::all();
        $coefficients = Coefficient::all();

        return view('education.coefficients', compact('classes', 'matieres', 'coefficients'));
    }

    public function store(Request $request) {
        $request->validate([
            'classe_id' => 'required|exists:classes,id',
            'matiere_id' => 'required|exists:matieres,id',
            'coefficient' => 'required|numeric',
            'groupe_matiere' => 'required',
        ], [
            'classe_id.required' => 'Selectionnez la classe',
            'matiere_id.required' => 'Selectionnez la matière',
            'groupe_matiere.required' => 'Selectionnez le groupe de la matière pour la classe',
            'coefficient.required' => 'Définissez la valeur du coefficient',
        ]);

        Coefficient::create($request->all());

        return redirect()->route('education.coefficients')->with('success', 'Configuration de la matière pour la classe avec succès!');
    }

    public function edit($id) {
        $classes = Classe::all();
        $matieres = Matiere::all();
        $coefficients = Coefficient::all();
        $coefficient = Coefficient::findOrFail($id);

        return view('education.coefficients', compact('classes', 'matieres', 'coefficient', 'coefficients'));
    }

    public function update(Request $request, $id) {
        $request->validate([
            'classe_id' => 'required|exists:classes,id',
            'matiere_id' => 'required|exists:matieres,id',
            'coefficient' => 'required|numeric',
            'groupe_matiere' => 'required',
        ], [
            'classe_id.required' => 'Selectionnez la classe',
            'groupe_matiere.required' => 'Selectionnez le groupe de la matière pour la classe',
            'matiere_id.required' => 'Selectionnez la matière',
            'coefficient.required' => 'Définissez la valeur du coefficient',
        ]);

        $coefficient = Coefficient::findOrFail($id);
        $coefficient->update($request->all());

        return redirect()->route('education.coefficients')->with('success', 'Configuration de la matière mise à jour avec succès');
    }

    public function destroy($id) {
        $coefficient = Coefficient::findOrFail($id);
        $coefficient->delete();
        return redirect()->route('education.coefficients')->with('deleteSuccess', 'Configuration de la matière supprimée avec succès');
    }
}
