<?php

namespace App\Http\Controllers;

use App\Models\Club;
use App\Models\User;
use App\Models\UserAnneeScolaire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ClubController extends Controller
{
    /**
     *
     */
    public function index(Request $request)
    {
        # useful var
        $query = Club::query();
        $studentSchoolYear = UserAnneeScolaire::all()->where('annee_scolaire_id','=', getCurrentYear()->id);
        $studentsSchoolYearId = $studentSchoolYear->pluck('user_id');
        $students = User::all()->where('typeUser', '=', 'eleve')->whereIn('id', $studentsSchoolYearId);
        # filtering
        $searchText = $request->input('searchText');
        if (!empty($searchText)) {
            $query->where('club_name', 'LIKE', "%{$searchText}%")
                ->orWhere('contenu', 'LIKE', "%{$searchText}%");
        }
        $clubs = $query->paginate(10);
        if($request->ajax()) {
            return view('partials._clubs_table', compact('clubs','students'));
        } else {
            return view('club.clubs', compact('clubs', 'students'));
        }
    }

    /**
     * add a new club in db
     */
    public function store(Request $request) {
        $request->validate([
            'club_name' => 'required|min:3|max:255|unique:clubs,club_name',
            'content' => 'required',
            'club_image' => 'required|image|mimes:jpg,jpeg,png,gif|max:4096',
        ], [
            'club_name.required' => 'Veuillez entrez le nom du club',
            'club_name.unique' => 'Ce nom de club existe déja',
            'content.required' => 'Veuillez remplir la description du club',
            'club_image.required' => 'Veuillez selectionner une image de mise en avant',
        ]);

        if(isset($request->president_id)) {
            $exists = Club::where('president_id', $request->president_id)->exists();
            if ($exists) {
                return response()->json([
                    'error' => "Cet élève est déjà président d'un club"
                ]);
            }
        }

        if($request->hasFile('club_image'))
            $imagePath = $request->file('club_image')->store('clubs', 'public');
        else
            $imagePath = '';

        $club = Club::create([
            'club_name' => $request->club_name,
            'contenu' => $request->content,
            'president_id' => isset($request->president_id) ? $request->president_id : null,
            'club_image' => $imagePath,
        ]);

        if($club) {
            return response()->json(['success' => 'Club ajouté avec succès!']);
        } else {
            return response()->json(['error' => 'Erreur inconue lors de la création du club']);
        }
    }

    /**
     * for editing club
     */
    public function edit($id) {
        $clubToEdit = Club::findOrFail($id);
        return response()->json([
            'clubToEdit' => $clubToEdit,
            'content' => $clubToEdit->contenu
        ]);
    }

    /**
     * update club
     */
    public function update(Request $request, $id) {
        $request->validate([
            'club_name' => 'required|min:3|max:255',Rule::unique('clubs')->ignore($id),
            'content' => 'required',
            'club_image' => 'club_image|mimes:jpg,jpeg,png,gif|max:4096',
        ], [
            'club_name.required' => 'Veuillez entrez un nom de club',
            'club_name.min' => 'Le nom du club doit contenir au minimum 3 caractères',
            'club_name.max' => 'Le nom du club doit contenir au maximum 255 caractères',
            'club_name.unique' => 'Ce nom de club existe déja',
            'content.required' => 'Veuillez remplir la description du club',
        ]);
        $club = Club::findOrFail($id);
        # check if president_id is already president of some club
        if(isset($request->president_id)) {
            $exists = Club::where('president_id', $request->president_id)
                ->where('id','!=',$club->id)->exists();
            if ($exists) {
                return response()->json([
                    'error' => "Cet élève est déjà président d'un autre club !"
                ]);
            }
        }
        // update image if it's define
        if($request->hasFile('club_image')) {
            $imagePath = $request->file('club_image')->store('clubs', 'public');
            if ($club->club_image) {
                Storage::disk('public')->delete($club->club_image);
            }
            $club->club_image = $imagePath;
        }
        // update president_id if it's define
        if(isset($request->president_id)) {
            $club->update([
                'president_id' => $request->president_id
            ]);
        }
        // finally update club
        $club->update([
            'club_name' => $request->club_name,
            'contenu' => $request->content
        ]);
        return response()->json(['success' => 'Informations du club mises à jour avec succès']);
    }

    /**
     * delete a club
     */
    public function destroy($id) {
        $club = Club::findOrFail($id);
        $club->delete();
        return redirect()->route('clubs.index')->with('deleteSuccess', 'Club supprimé avec succès !');
    }

}
