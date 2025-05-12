<?php

namespace App\Http\Controllers;

use App\Models\Specialite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class SpecialiteController extends Controller
{
    /**
     *
     */
    public function index(Request $request)
    {
        # useful var
        $query = Specialite::query()->where('type','specialite');
        # filtering
        $searchText = $request->input('searchText');
        if (!empty($searchText)) {
            $query->where('specialite_title', 'LIKE', "%{$searchText}%")
                ->orWhere('contenu', 'LIKE', "%{$searchText}%");
        }
        $specialites = $query->paginate(10);
        if($request->ajax()) {
            return view('partials._specialites_table', compact('specialites'));
        } else {
            return view('configurations.specialites', compact('specialites'));
        }
    }

    /**
     * add a new specialite in db
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
            'specialite_title' => 'required|min:3|max:255|unique:specialites,specialite_title',
            'content' => 'required',
            'specialite_image' => 'required|image|mimes:jpg,jpeg,png,gif|max:4096',
            'sliders' => 'required|array',
            'sliders.*.title' => 'required|string',
            'sliders.*.image' => 'required|image|mimes:jpg,jpeg,png,gif|max:4096',
        ], [
            'specialite_title.required' => 'Veuillez entrez le titre de la specialite',
            'specialite_title.unique' => 'Ce titre de specialite existe déja',
            'specialite_title.min' => 'Le titre doit contenir minimum 3 caractères',
            'specialite_title.max' => 'Le titre contenir maximum 255 caractères',
            'content.required' => 'Veuillez remplir la description',
            'specialite_image.required' => 'Veuillez selectionner une image de mise en avant',
            'specialite_image.mimes' => 'L\'image doit être du type (jpg, jpeg, png, gif)',
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
        if($request->hasFile('specialite_image'))
            $imagePath = $request->file('specialite_image')->store('specialites', 'public');
        else
            $imagePath = '';

        $specialite = Specialite::create([
            'specialite_title' => $request->specialite_title,
            'contenu' => $request->content,
            'specialite_image' => $imagePath,
            'sliders' => $sliderData,
            'type' => 'specialite'
        ]);

        if($specialite) {
            return response()->json(['success' => 'Specialite ajouté avec succès!']);
        } else {
            return response()->json(['error' => 'Erreur inconue lors de la création de la specialite']);
        }
    }

    /**
     * for editing specialite
     */
    public function edit($id) {
        $specialiteToEdit = Specialite::findOrFail($id);
        return response()->json([
            'specialiteToEdit' => $specialiteToEdit,
            'content' => $specialiteToEdit->contenu
        ]);
    }

    /**
     * update specialite
     */
    public function update(Request $request, $id) {
        $specialite = Specialite::findOrFail($id);
        $rules = [
            'specialite_title' => 'required|min:3|max:255',Rule::unique('specialites')->ignore($id),
            'content' => 'required',
            'specialite_image' => 'image|mimes:jpg,jpeg,png,gif|max:4096',
            'sliders' => 'required|array',
        ];
        $errors = [
            'specialite_title.required' => 'Veuillez entrez un nom de specialite',
            'specialite_title.min' => 'Le titre doit contenir au minimum 3 caractères',
            'specialite_title.max' => 'Le titre doit contenir au maximum 255 caractères',
            'specialite_title.unique' => 'Ce titre existe déja',
            'content.required' => 'Veuillez remplir la description',
            'specialite_image.mimes' => 'L\'image doit être du type (jpg, jpeg, png, gif)',
            'sliders.required' => 'Veuillez ajouter au moins une image + descripton',
        ];
        $sliderData = [];
        $existingSliders = $specialite->sliders ?? [];
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
        // update image if it's define
        if($request->hasFile('specialite_image')) {
            $imagePath = $request->file('specialite_image')->store('specialites', 'public');
            if ($specialite->specialite_image) {
                Storage::disk('public')->delete($specialite->specialite_image);
            }
            $specialite->update([
                'specialite_image' => $imagePath
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
        // finally update specialite
        $specialite->update([
            'specialite_title' => $request->specialite_title,
            'contenu' => $request->content,
            'sliders' => $sliderData
        ]);
        return response()->json(['success' => 'Informations de la specialite mises à jour avec succès']);
    }

    /**
     * delete a specialite
     */
    public function destroy($id) {
        $specialite = Specialite::findOrFail($id);
        $specialite->delete();
        return redirect()->route('specialite.index')->with('deleteSuccess', 'Specialite supprimé avec succès !');
    }

}
