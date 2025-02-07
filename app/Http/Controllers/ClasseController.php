<?php

namespace App\Http\Controllers;

use App\Models\AnneeScolaire;
use App\Models\Classe;
use App\Models\ClasseEffectif;
use App\Models\Section;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ClasseController extends Controller
{
    /**
     * Classe controller implmentation
     */
    public function index(Request $request)
    {
        $user = User::find(Auth::id());
        $searchClasse = $request->input('searchClasse');
        $cycleFilter= $request->input('cycleFilter');
        $sectionFilter = $request->input('sectionFilter');
        $sections = Section::all();
        // querying
        $query = Classe::query();

        // filtering
        if(!empty($searchClasse)) {
            $query->where('libClasse', 'LIKE', "%{$searchClasse}%");
        }
        if(!empty($sectionFilter)) {
            $query->whereHas('section', function ($q) use ($sectionFilter) {
                $q->where('id', $sectionFilter);
            });
        }
        if(!empty($cycleFilter)) {
            $query->where('cycleClasse',$cycleFilter);
        }

        //dd($query);
        $classes = $query->paginate(10);

        if($request->ajax()) {
            return view('partials._classes_table', compact('classes', 'user','sections'));
        } else {
            return view('education.classes', compact('classes', 'user','sections'));
        }
    }

    /**
     *
     */
    public function store(Request $request) {
        $request->validate([
            'libClasse' => 'required|unique:classes',
            'section_id' => 'required',
            'cycleClasse' => 'required',
        ], [
                'libClasse.required' => 'Entrez le libellé de la classe',
                'section_id.required' => 'Selectionnez une section',
                'cycleClasse.required' => 'Selectionnez le cycle de la classe',
                'libClasse.unique' => 'Ce libellé de classe existe déjà'
         ]);

        //dd($request);
        $classe = Classe::create([
            'libClasse' => $request->libClasse,
            'section_id' => $request->section_id,
            'cycleClasse' => $request->cycleClasse,
        ]);
        // create all ClasseEffectifs when creating a class :
        $years = AnneeScolaire::all();
        foreach ($years as $year) {
            ClasseEffectif::create([
                'annee_scolaire_id' => $year->id,
                'classe_id' => $classe->id
            ]);
        }

        if($classe) {
            return response()->json(['success' => 'Classe créée avec succès !']);
        } else {
            return response()->json(['error' => 'Erreur inconue lors de la création de la classe']);
        }
    }


    /**
     * edit specific classe
     */
    public function edit($id) {
        $classToEdit = Classe::findOrFail($id);
        return response()->json(['classToEdit' => $classToEdit]);
    }

    /**
     * update specific class
     */
    public function update(Request $request, $id) {
        $request->validate([
            'libClasse' => ['required','min:3','max:255',Rule::unique('classes')->ignore($id)],
            'section_id' => 'required',
            'cycleClasse' => 'required',
        ], [
                'libClasse.required' => 'Entrez le libellé de la classe',
                'section_id.required' => 'Selectionnez une section',
                'cycleClasse.required' => 'Selectionnez le cycle de la classe',
                'libClasse.unique' => 'Ce libellé de classe existe déjà'
         ]);

        $classe = Classe::findOrFail($id);
        $classe->update([
            'libClasse' => $request->libClasse,
            'section_id' => $request->section_id,
            'cycleClasse' => $request->cycleClasse,
        ]);

        return response()->json(['success' => 'Classe mise à jour avec succès']);
    }

    /**
     * delete specific clase forever
     */
    public function destroy($id) {
        // delete some other stuff when deleting a classe
        $years = AnneeScolaire::all();
        foreach ($years as $year) {
            $classeEffectif = ClasseEffectif::where([
                'annee_scolaire_id' => $year->id,
                'classe_id' => $id
            ]);
            dd($classeEffectif);
        }
        $classe = Classe::findOrFail($id);
        $classe->delete();

        return redirect()->route('education.classes')->with('deleteSuccess', 'Classe supprimée avec succès');
    }
}
