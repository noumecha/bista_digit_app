<?php

namespace App\Http\Controllers;

use App\Models\Section;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SectionController extends Controller
{
    /**
     * first function to show the datas
     */
    public function index(Request $request) {
        $user = User::find(Auth::id());
        $sections = Section::all();
        $searchSection = $request->input('searchSection');
        $query = Section::query();
        if(!empty($searchSection)) {
            $query->where(function($q) use ($searchSection) {
                $q->where('libelleSection', 'LIKE', "%{$searchSection}%");
            });
        }
        $sections = $query->paginate(10);
        if($request->ajax()) {
            return view('partials._sections_table', compact('user','sections','searchSection'));
        } else {
            return view('education.sections', compact('user','sections','searchSection'));
        }
    }
     /**
     * saving school sections
     * @param  \Illuminate\Http\Request  $request
     */
    public function store(Request $request) {
        $request->validate([
            'libelleSection' => [
                'required',
                'unique:sections',
            ],
        ], [
            'libelleSection.required' => 'Définissez une section d\'enseignement',
            'libelleSection.unique' => 'Cette section d\'enseignement existe déjà',
        ]);
        $annneScolaire = Section::create([
            'libelleSection' => $request->libelleSection,
        ]);

        if($annneScolaire) {
            return response()->json(['success' => 'Section d\'enseignement ajoutée avec succès!']);
        } else {
            return response()->json(['error' => 'Erreur lors de l\'enregistrement de la section d\'enseignement']);
        }
    }


    /**
     * edit specific section
     */
    public function edit($id) {
        $sectionToEdit = Section::findOrFail($id);
        return response()->json(['sectionToEdit' => $sectionToEdit]);
    }
    /**
     * function to update a section.
     */
    public function update(Request $request, $id) {
        $request->validate([
            'libelleSection' => [
                'required',
                Rule::unique('sections')->ignore($id)
            ],
        ], [
            'libelleSection.unique' => 'Cette section d\'enseignement existe déjà',
            'libelleSection.required' => 'Définissez une section d\'enseignement scolaire',
         ]);

        $section = Section::findOrFail($id);
        $section->update($request->all());

        return response()->json(['success' => 'Section mise à jour avec succès']);
    }

    /**
     * function to delete a section
     */
    public function destroy($id) {
        $section = Section::findOrFail($id);
        $section->delete();

        return redirect()->route('education.sections')->with('listSuccess', 'Section supprimée avec succès');
    }
}
