<?php

namespace App\Http\Controllers;

use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class SliderController extends Controller
{
/**
     *
     */
    public function index(Request $request)
    {
        # useful var
        $query = Slider::query();
        # filtering
        $searchText = $request->input('searchText');
        if (!empty($searchText)) {
            $query->where('slider_title', 'LIKE', "%{$searchText}%")
                ->orWhere('slider_text', 'LIKE', "%{$searchText}%");
        }
        $sliders = $query->paginate(10);
        if($request->ajax()) {
            return view('partials._sliders_table', compact('sliders'));
        } else {
            return view('configurations.sliders', compact('sliders'));
        }
    }

    /**
     * add a new slider in db
     */
    public function store(Request $request) {
        $request->validate([
            'slider_title' => 'required|min:3|max:255|unique:sliders,slider_title',
            'slider_text' => 'required|max:255',
            'slider_image' => 'required|image|mimes:jpg,jpeg,png,gif|max:4096',
        ], [
            'slider_title.required' => 'Veuillez entrez un titre pour le slider',
            'slider_title.min' => 'Le titre doit contenir minimum 3 caractères',
            'slider_title.max' => 'Le titre doit contenir au maximum 255 caractères',
            'slider_text.min' => 'Le texte du slider doit contenir maximum 255 caractères',
            'slider_title.unique' => 'Ce titre existe déja',
            'slider_text.required' => 'Veuillez remplir la description du slider',
            'slider_image.required' => 'Veuillez selectionner une image de mise en avant',
            'slider_image.mimes' => 'L\'image du slider doit etre du type (jpg,jpeg,png,gif)',
            'slider_image.max' => 'L\'image du slider doit faire maximum 4Mo'
        ]);

        if($request->hasFile('slider_image'))
            $imagePath = $request->file('slider_image')->store('sliders', 'public');
        else
            $imagePath = '';

        $slider = Slider::create([
            'slider_title' => $request->slider_title,
            'slider_text' => $request->slider_text,
            'slider_image' => $imagePath,
        ]);

        if($slider) {
            return response()->json(['success' => 'Slider ajouté avec succès!']);
        } else {
            return response()->json(['error' => 'Erreur inconue lors de la création du slider']);
        }
    }

    /**
     * for editing slider
     */
    public function edit($id) {
        $clubToEdit = Slider::findOrFail($id);
        return response()->json([
            'clubToEdit' => $clubToEdit
        ]);
    }

    /**
     * update slider
     */
    public function update(Request $request, $id) {
        $request->validate([
            'slider_title' => 'required|min:3|max:255',Rule::unique('sliders')->ignore($id),
            'slider_text' => 'required|min:255',
            'slider_image' => 'image|mimes:jpg,jpeg,png,gif|max:4096',
        ], [
            'slider_title.required' => 'Veuillez entrez un nom de slider',
            'slider_title.min' => 'Le titre doit contenir minimum 3 caractères',
            'slider_title.max' => 'Le titre doit contenir au maximum 255 caractères',
            'slider_text.min' => 'Le texte du slider doit contenir maximum 255 caractères',
            'slider_title.unique' => 'Ce nom de slider existe déja',
            'slider_text.required' => 'Veuillez remplir la description du slider',
        ]);
        $slider = Slider::findOrFail($id);
        // update image if it's define
        if($request->hasFile('slider_image')) {
            $imagePath = $request->file('slider_image')->store('sliders', 'public');
            if ($slider->slider_image) {
                Storage::disk('public')->delete($slider->slider_image);
            }
            $slider->slider_image = $imagePath;
        }
        // finally update slider
        $slider->update([
            'slider_title' => $request->slider_title,
            'slider_text' => $request->slider_text
        ]);
        return response()->json(['success' => 'Informations du slider mises à jour avec succès']);
    }

    /**
     * delete a slider
     */
    public function destroy($id) {
        $slider = Slider::findOrFail($id);
        $slider->delete();
        return redirect()->route('sliders.index')->with('deleteSuccess', 'Slider supprimé avec succès !');
    }
}
