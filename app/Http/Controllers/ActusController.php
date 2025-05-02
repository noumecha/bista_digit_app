<?php

namespace App\Http\Controllers;

use App\Models\Actualite;
use App\Models\CategorieActualite;
use App\Models\Club;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ActusController extends Controller
{
    /**
     * index function
     */
    public function index (Request $request) {
        // utils vars
        $user = User::find(Auth::id());
        $categories = CategorieActualite::all();
        // filter vars
        $searchActualite = $request->input('searchActualite');
        $categorieFilter = $request->input('categorieFilter');
        // querying
        $query = Actualite::query();
        // filtering
        if(!empty($categorieFilter)) {
            $query->where('categorie_actualites_id', $categorieFilter);
        }
        if (!empty($searchActualite)) {
            $query->where('titre', 'LIKE', "%{$searchActualite}%")
                ->orWhere('contenu', 'LIKE', "%{$searchActualite}%");
        }
        // if user is club president
        $userIds = Club::all()->pluck('president_id');
        if ($userIds->contains(Auth::id())) {
            $club = Club::all()->where('president_id', Auth::id())->first();
            $query->where('club_id', $club->id);
        }
        $actualites = $query->latest()->paginate(10);
        if($request->ajax()) {
            return view('partials._actualites_table', compact('actualites', 'categories'));
        } else {
            return view('actualites.index', compact('actualites', 'categories'));
        }
    }

    /**
     * create new actualite
     */
    public function store(Request $request) {
        $request->validate([
            'titre' => 'required|min:3|max:255|unique:actualites,titre',
            'content' => 'required',
            'image' => 'required|image|mimes:jpg,jpeg,png,gif|max:4096',
            'categorie_actualites_id' => 'required'
        ], [
            'titre.required' => 'Veuillez entrez un titre',
            'titre.unique' => 'Ce titre existe déja',
            'content.required' => 'Veuillez remplire le contenu de l\'actualité',
            'image.required' => 'Veuillez selectionner une image de mise en avant',
            'categorie_actualites_id.required' => 'Veuillez selectionner selectionner la catégorie',
        ]);

        if($request->hasFile('image'))
            $imagePath = $request->file('image')->store('actualites', 'public');
        else
            $imagePath = '';

        // if user is club president
        $clubId = null;
        $userIds = Club::all()->pluck('president_id');
        if ($userIds->contains(Auth::id())) {
            $club = Club::all()->where('president_id', Auth::id())->first();
            $clubId = $club->id;
        }
        $actualite = Actualite::create([
            'titre' => $request->titre,
            'contenu' => $request->content,
            'user_id' => Auth::id(),
            'categorie_actualites_id' => $request->categorie_actualites_id,
            'image' => $imagePath,
            'club_id' => $clubId,
        ]);

        if($actualite) {
            return response()->json(['success' => 'Actualité ajoutée avec succès!']);
        } else {
            return response()->json(['error' => 'Erreur inconue lors de l\'ajout de l\'actualité']);
        }
    }


    /**
     * edit a specific actualite
     */
    public function edit($id) {
        $actualiteToEdit = Actualite::findOrFail($id);
        return response()->json([
            'actualiteToEdit' => $actualiteToEdit,
            'content' => $actualiteToEdit->contenu
        ]);
    }

    /**
     * updating specific actualite
     */
    public function update(Request $request, $id) {
        $request->validate([
            'titre' => 'required|min:3|max:255',Rule::unique('actualites')->ignore($id),
            'content' => 'required',
            'image' => 'image|mimes:jpg,jpeg,png,gif|max:4096',
            'categorie_actualites_id' => 'required'
        ], [
            'titre.required' => 'Veuillez entrez un titre',
            'titre.unique' => 'Ce titre existe déja',
            'content.required' => 'Veuillez remplire le contenu de l\'actualité',
            'categorie_actualites_id.required' => 'Veuillez selectionner selectionner la catégorie',
        ]);
        $actualite = Actualite::findOrFail($id);

        if($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('actualites', 'public');
            if ($actualite->image) {
                Storage::disk('public')->delete($actualite->image);
            }
            $actualite->image = $imagePath;
        }

        $actualite->update([
            'titre' => $request->titre,
            'contenu' => $request->content,
            'user_id' => Auth::id(),
            'categorie_actualites_id' => $request->categorie_actualites_id,
        ]);

        return response()->json(['success' => 'Actualité mise à jour avec succès']);
    }

    /**
     * delete specific actualite
     */
    public function destroy($id) {
        $actualite = Actualite::findOrFail($id);
        $actualite->delete();
        return redirect()->route('actualites.index')->with('deleteSuccess', 'Actualité supprimer avec succès');
    }
}
