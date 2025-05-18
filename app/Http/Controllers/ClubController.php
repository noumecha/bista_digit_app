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
        $customAttributes = [];
        if ($request->has('sliders')) {
            foreach ($request->input('sliders') as $index => $value) {
                $sliderId = $index + 1;
                $customAttributes["sliders.$index.title"] = "slider $sliderId";
                $customAttributes["sliders.$index.image"] = "slider $sliderId";
            }
        }
        $request->validate([
            'club_name' => 'required|min:3|max:255|unique:clubs,club_name',
            'content' => 'required',
            'club_image' => 'required|image|mimes:jpg,jpeg,png,gif|max:4096',
            'sliders' => 'required|array',
            'sliders.*.title' => 'required|string',
            'sliders.*.image' => 'required|image|mimes:jpg,jpeg,png,gif|max:4096',
        ], [
            'club_name.required' => 'Veuillez entrez le nom du club',
            'club_name.unique' => 'Ce nom de club existe déja',
            'club_name.min' => 'Le nom du club doit contenir minimum 3 caractères',
            'club_name.max' => 'Le nom du club doit contenir maximum 255 caractères',
            'content.required' => 'Veuillez remplir la description du club',
            'club_image.required' => 'Veuillez selectionner une image de mise en avant',
            'club_image.mimes' => 'L\'image doit être du type (jpg, jpeg, png, gif)',
            'sliders.required' => 'Veuillez ajouter au moins une image + descripton',
            'sliders.*.title.required' => 'Ajouter un titre ou mini description au :attribute',
            'sliders.*.image.required' => 'Ajouter une image au :attribute',
            'sliders.*.image.mimes' => 'L\'image du :attribute doit être du type (jpg, jpeg, png, gif)',
        ], $customAttributes);
        $sliderData = [];
        if ($request->has('sliders')) {
            foreach ($request->sliders as $slider) {
                $path = null;
                if (isset($slider['image'])) {
                    $path = $slider['image']->store('sliders', 'public');
                }
                $sliderData[] = [
                    'title' => $slider['title'] ?? '',
                    'image' => $path,
                ];
            }
        }
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
            'sliders' => $sliderData,
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
        $club = Club::findOrFail($id);
        $rules = [
            'club_name' => 'required|min:3|max:255',Rule::unique('clubs')->ignore($id),
            'content' => 'required',
            'club_image' => 'image|mimes:jpg,jpeg,png,gif|max:4096',
            'sliders' => 'required|array',
        ];
        $errors = [
            'club_name.required' => 'Veuillez entrez un nom de club',
            'club_name.min' => 'Le nom du club doit contenir au minimum 3 caractères',
            'club_name.max' => 'Le nom du club doit contenir au maximum 255 caractères',
            'club_name.unique' => 'Ce nom de club existe déja',
            'content.required' => 'Veuillez remplir la description du club',
            'club_image.mimes' => 'L\'image doit être du type (jpg, jpeg, png, gif)',
            'sliders.required' => 'Veuillez ajouter au moins un slider',
        ];
        $sliderData = [];
        $customAttributes = [];
        $existingSliders = $club->sliders ?? [];
        if($request->has('sliders')) {
            foreach ($request->sliders as $index => $slider) {
                $sliderId = $index + 1;
                $customAttributes["sliders.$index.title"] = "slider $sliderId";
                $customAttributes["sliders.$index.image"] = "slider $sliderId";
                // checking titles
                $rules["sliders.$index.title"] = 'required|string';
                $errors["sliders.$index.title.required"] = "Ajouter un titre ou mini description au slider $sliderId";
                // checking images
                if(!isset($existingSliders[$index]['image'])) {
                    $rules["sliders.$index.image"] = 'required|image|mimes:jpg,jpeg,png,gif|max:4096';
                    $errors["sliders.$index.image.required"] = "Ajouter une image au slider $sliderId";
                    $errors["sliders.$index.image.mimes"] = "L'image du slider $sliderId doit être du type (jpg, jpeg, png, gif)";
                } else {
                    $rules["sliders.$index.image"] = 'nullable|image|mimes:jpg,jpeg,png,gif|max:4096';
                    $errors["sliders.$index.image.mimes"] = "L'image du slider $sliderId doit être du type (jpg, jpeg, png, gif)";
                }
            }
        }
        $request->validate($rules, $errors, $customAttributes);
        try {
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
            // adding new sliders
            if ($request->has('sliders')) {
                foreach ($request->sliders as $index => $slider) {
                    $path = null;
                    // update in case that a new image is upload
                    if (isset($slider['image'])) {
                        $path = $slider['image']->store('sliders', 'public');
                        // delete the old image file
                        if (!empty($existingSliders[$index]['image'])) {
                            Storage::disk('public')->delete($existingSliders[$index]['image']);
                        }
                    } else {
                        // keeping old image
                        $path = $existingSliders[$index]['image'] ?? null;
                    }
                    $sliderData[] = [
                        'title' => $slider['title'] ?? '',
                        'image' => $path,
                    ];
                }
            }
            // finally update club
            $club->update([
                'club_name' => $request->club_name,
                'contenu' => $request->content,
                'sliders' => $sliderData
            ]);
            return response()->json(['success' => 'Informations du club mises à jour avec succès']);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => 'Erreur lors de la mise à jour : '.$th->getMessage()
            ]);
        }
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
