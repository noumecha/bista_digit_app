<?php

namespace App\Http\Controllers;

use App\Models\Classe;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClasseController extends Controller
{
    /**
     * Classe controller implmentation
     */
    public function index()
    {
        $user = User::find(Auth::id());
        $classes = Classe::All();

        return view('education.classes',['classes' => $classes], compact('user'));
    }

    /**
     *
     */
    public function store(Request $request) {
        $request->validate([
            'libClasse' => 'required|unique:classes',
            'effectifClasse' => 'required',
            'cycleClasse' => 'required',
        ], [
                'libClasse.required' => 'Entrez le libellé de la classe',
                'effectifClasse.required' => 'Entrez l\'effectif de la classe',
                'cycleClasse.required' => 'Choisissez le cycle de la classe',
                'libClasse.unique' => 'Ce libellé de classe existe déjà'
         ]);

        //dd($request->libClasse);
        Classe::create([
            'libClasse' => $request->libClasse,
            'effectifClasse' => $request->effectifClasse,
            'cycleClasse' => $request->cycleClasse,
        ]);

        return redirect()->route('education.classes')->with('success', 'Classe créée avec succès !');
    }


    /**
     *
     */
    public function edit($id) {
        $classToEdit = Classe::findOrFail($id);
        $classes = Classe::all();

        return view('education.classes', compact('classes', 'classToEdit'));
    }

    /**
     *
     */
    public function update(Request $request, $id) {
        $request->validate([
            'libClasse' => 'required',
            'effectifClasse' => 'required',
            'cycleClasse' => 'required',
        ], [
                'libClasse.required' => 'Entrez le libellé de la classe',
                'effectifClasse.required' => 'Entrez l\'effectif de la classe',
                'cycleClasse.required' => 'Choisissez le cycle de la classe',
         ]);

        $classe = Classe::findOrFail($id);
        $classe->update([
            'libClasse' => $request->libClasse,
            'effectifClasse' => $request->effectifClasse,
            'cycleClasse' => $request->cycleClasse,
        ]);

        return redirect()->route('education.classes')->with('success', 'Classe mise à jour avec succès');
    }

    /**
     *
     */
    public function destroy($id) {
        $classe = Classe::findOrFail($id);
        $classe->delete();

        return redirect()->route('education.classes')->with('deleteSuccess', 'Classe supprimée avec succès');
    }
}
