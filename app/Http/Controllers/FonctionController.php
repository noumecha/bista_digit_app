<?php

namespace App\Http\Controllers;

use Illuminate\Validation\Rule;
use App\Models\Fonction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\User;

class FonctionController extends Controller
{
    /**
     *
     */
    public function index(Request $request) {
        $user = User::find(Auth::id());
        $searchFonction = $request->input('searchFonction');
        $query = Fonction::query();
        if(!empty($searchFonction) ) {
            $query->where('libelleFonction','LIKE',"%{$searchFonction}%");
        }

        $fonctions = $query->paginate(10);
        if($request->ajax()) {
            return view('partials._fonctions_table', compact('user','fonctions'));
        } else {
            return view('utilisateurs.fonctions', compact('user','fonctions'));
        }
    }

     /**
     * saving administrators members
     * @param  \Illuminate\Http\Request  $request
     */
    public function store(Request $request) {
        $request->validate([
            'libelleFonction' => 'required|min:3|max:255|unique:fonctions,libelleFonction',
        ], [
            'libelleFonction.required' => 'Entrez le libellé',
            'libelleFonction.unique' => 'Ce libellé existe déjà',
        ]);

        Fonction::create([
            'libelleFonction' => $request->libelleFonction,
        ]);

        return response()->json(['success' => 'Fonction ajoutée avec succès']);
    }

    /**
     *
     */
    public function edit($id) {
        $fonctionToEdit = Fonction::findOrFail($id);
        return response()->json(['fonction' => $fonctionToEdit]);
    }

    /**
     * updating fonction
     */
    public function update(Request $request, $id) {
        $request->validate([
            'libelleFonction' => ['required','min:3', 'max:255',Rule::unique('fonctions')->ignore($id)],
        ], [
            'libelleFonction.required' => 'Entrez le libellé',
            'libelleFonction.unique' => 'Ce libellé existe déjà',
        ]);

        $fonction = Fonction::findOrFail($id);

        $fonction->update($request->all());

        return response()->json(['success' => 'La fonction a été mise à jour avec succès']);
    }

    /**
     * delete fonction definitely
     */
    public function destroy($id) {
        $fonction = Fonction::findOrFail($id);
        $fonction->delete();

        return redirect()->route('utilisateur.fonctions')->with('deleteSuccess', 'Fonction supprimé avec succès');
    }
}
