<?php

namespace App\Http\Controllers;

use App\Models\Specialite;
use Carbon\Exceptions\Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class IamLeaderController extends Controller
{
    /**
     *
     */
    public function index(Request $request)
    {
        $page = Specialite::all()->where("type","leader-page")->last();
        return view('programme.iamleader', compact('page'));
    }

    /**
     * update or create page configuration
     */
    public function update(Request $request, $action) {
        $customAttributes = [];
        $imagePath = null;
        # entry var
        if (isset($request->pageconfigurationId)) {
            $specialite = Specialite::findOrFail($request->pageconfigurationId);
            $existingSliders = $specialite->sliders;
        } else {
            $existingSliders = [];
        }
        # validate form datas
        $rules = [
            'specialite_title' => 'required|min:3|max:255',
            'content' => 'required',
            'specialite_image' => 'image|mimes:jpg,jpeg,png,gif|max:4096',
            'sliders' => 'required|array',
            'sliders.*.title' => 'required|string',
            'sliders.*.image' => 'image|mimes:jpg,jpeg,png,gif|max:4096',
        ];
        $validators = [
            'specialite_title.required' => 'Veuillez entrez le titre de la specialite',
            'specialite_title.unique' => 'Ce titre de specialite existe déja',
            'specialite_title.min' => 'Le titre doit contenir minimum 3 caractères',
            'specialite_title.max' => 'Le titre contenir maximum 255 caractères',
            'content.required' => 'Veuillez remplir la description',
            'specialite_image.mimes' => 'L\'image doit être du type (jpg, jpeg, png, gif)',
            'sliders.required' => 'Veuillez ajouter au moins une image + descripton',
            'sliders.*.title.required' => 'Ajouter un titre ou mini description au :attribute',
            #'sliders.*.image.required' => 'Ajouter une image au :attribute',
            'sliders.*.image.mimes' => 'L\'image du :attribute doit être du type (jpg, jpeg, png, gif)',
        ];
        # checking data before processing
        if($request->has('sliders')) {
            foreach ($request->sliders as $index => $slider) {
                $sliderId = $index + 1;
                $customAttributes["sliders.$index.title"] = "slider $sliderId";
                $customAttributes["sliders.$index.image"] = "slider $sliderId";
                // checking titles
                $rules["sliders.$index.title"] = 'required|string';
                $validators["sliders.$index.title.required"] = "Ajouter un titre ou mini description au slider $sliderId";
                // checking images
                if(!isset($existingSliders[$index]['image'])) {
                    $rules["sliders.$index.image"] = 'required|image|mimes:jpg,jpeg,png,gif|max:4096';
                    $validators["sliders.$index.image.required"] = "Ajouter une image au slider $sliderId";
                    $validators["sliders.$index.image.mimes"] = "L'image du slider $sliderId doit être du type (jpg, jpeg, png, gif)";
                } else {
                    $rules["sliders.$index.image"] = 'nullable|image|mimes:jpg,jpeg,png,gif|max:4096';
                    $validators["sliders.$index.image.mimes"] = "L'image du slider $sliderId doit être du type (jpg, jpeg, png, gif)";
                }
            }
        }
        // adding new sliders
        $sliderData = [];
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
        # if the action is create make image required
        if($action === 'create') {
            # image
            $rules['specialite_image'] = 'required|image|mimes:jpg,jpeg,png,gif|max:4096';
            $validators['specialite_image.required'] = 'Veuillez selectionner une image de mise en avant';
            # slider images
            # slider images
            if($request->has('sliders')) {
                foreach ($request->sliders as $index => $slider) {
                    $rules["sliders.$index.image"] = 'required|image|mimes:jpg,jpeg,png,gif|max:4096';
                    $validators["sliders.$index.image.required"] = "Ajouter une image au slider $sliderId";
                    $validators["sliders.$index.image.mimes"] = "L'image du slider $sliderId doit être du type (jpg, jpeg, png, gif)";
                }
            }
            # on create setting image
            if($request->hasFile('specialite_image')) {
                $imagePath = $request->file('specialite_image')->store('specialites', 'public');
            }
        }
        if($action === "update" && isset($specialite) && !isset($specialite->specialite_image)) {
            # image
            $rules['specialite_image'] = 'required|image|mimes:jpg,jpeg,png,gif|max:4096';
            $validators['specialite_image.required'] = 'Veuillez selectionner une image de mise en avant';
        }
        $request->validate($rules, $validators, $customAttributes);
        try {
            if($action === 'update' && isset($request->pageconfigurationId)) {
                $leaderPage = Specialite::findOrFail($request->pageconfigurationId);
                $leaderPage->update([
                    'specialite_title' => $request->specialite_title,
                    'contenu' => $request->content,
                    'sliders' => $sliderData
                ]);
                if($request->hasFile('specialite_image')) {
                    $imagePath = $request->file('specialite_image')->store('specialites', 'public');
                    if ($leaderPage->specialite_image) {
                        Storage::disk('public')->delete($leaderPage->specialite_image);
                    }
                    $leaderPage->update([
                        'specialite_image' => $imagePath
                    ]);
                }
                return response()->json(['success' => 'Configuration de la page i am leader mise à jour avec succès']);
            }
            if ($action === 'create') {
                $leaderPage = Specialite::create([
                    'specialite_title' => $request->specialite_title,
                    'contenu' => $request->content,
                    'specialite_image' => $imagePath,
                    'sliders' => $sliderData,
                    'type' => 'leader-page',
                ]);
                if ($leaderPage) {
                    return response()->json(['success' => 'Configuration de la page i am leader enregistrée avec succès']);
                }
            }
        } catch (Exception $ex) {
            dd($ex);
        }
    }

    /**
     * edit page configuration
     */
    public function edit($id) {
        $pageconfiguration = Specialite::findOrFail($id);
        return response()->json([
            'pageconfiguration' => $pageconfiguration,
            'content' => $pageconfiguration->contenu
        ]);
    }

}
