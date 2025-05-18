<?php

namespace App\Http\Controllers;

use App\Models\Club;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ClubConfigurationController extends Controller
{
    /**
     * index functions to manage club configuration
     * when the presient of the club is connected
     */
    public function index() {
        # $clubconfiguration = Club::all()->where('president_id',Auth::id());
        $clubconfiguration = Club::all()->last();
        return view('configurations.club_configuration', compact('clubconfiguration'));
    }

     /**
     * index function to create or update configuration
     */
    public function update(Request $request, $action) {
        $clubconfiguration = Club::findOrFail($request->clubconfigurationId);
        $rules = [
            'club_name' => 'required|min:3|max:255',Rule::unique('clubs')->ignore($request->clubconfigurationId),
            'content' => 'required',
            'sliders' => 'required|array',
        ];
        $errors = [
            'club_name.required' => 'Veuillez entrez un nom de club',
            'club_name.min' => 'Le nom du club doit contenir au minimum 3 caractères',
            'club_name.max' => 'Le nom du club doit contenir au maximum 255 caractères',
            'club_name.unique' => 'Ce nom de club existe déja',
            'content.required' => 'Veuillez remplir la description du club',
            'sliders.required' => 'Veuillez ajouter au moins un slider',
        ];
        $sliderData = [];
        $customAttributes = [];
        $existingSliders = $clubconfiguration->sliders ?? [];
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
            if($action === 'update' && isset($request->clubconfigurationId)) {
                $clubconfiguration = Club::findOrFail($request->clubconfigurationId);
                if($request->hasFile('club_image')) {
                    $imagePath = $request->file('club_image')->store('clubs', 'public');
                    if ($clubconfiguration->club_image) {
                        Storage::disk('public')->delete($clubconfiguration->club_image);
                    }
                    $clubconfiguration->club_image = $imagePath;
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
                $clubconfiguration->update([
                    'club_name' => $request->club_name,
                    'contenu' => $request->content,
                    'sliders' => $sliderData
                ]);
                return response()->json(['success' => 'Configuration du club mis à jour avec succès']);
            }
        } catch (Exception $ex) {
            return response()->json([
                'error' => 'Erreur lors de la mise à jour '.$ex->getMessage()
            ]);
        }
    }

    /**
     * edit clubconfiguration
     */
    public function edit($id) {
        $clubconfiguration = Club::findOrFail($id);
        return response()->json([
            'clubconfiguration' => $clubconfiguration,
            'content' => $clubconfiguration->contenu
        ]);
    }
}
