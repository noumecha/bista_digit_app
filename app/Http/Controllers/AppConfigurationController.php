<?php

namespace App\Http\Controllers;

use App\Models\AppConfiguration;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AppConfigurationController extends Controller
{
    /**
     * index functions to manage app configuration
     */
    public function index() {
        $appconfiguration = AppConfiguration::all()->last();
        return view('configurations.app_configuration', compact('appconfiguration'));
    }

    /**
     * index function to create or update configuration
     */
    public function update(Request $request, $action) {
        $request->validate([
            'school_name' => 'required|string|min:3|max:255',
            'school_motor' => 'required|string|min:3|max:255',
            'school_postal_box' => 'required|integer|min:4',
            'school_logo' => 'image|mimes:jpeg,png,gif|max:4096',
            'content' => 'required',
            'school_town' => 'required|min:3|max:255',
            'school_location' => 'required|min:3|max:255',
            'contact_phone_1' => ['required', 'regex:/^[0-9]{3}-[0-9]{3}-[0-9]{3}$/'],
            'contact_phone_2' => ['nullable','regex:/^[0-9]{3}-[0-9]{3}-[0-9]{3}$/'],
            'school_email' => 'nullable|email|max:255',
            'school_image' => 'image|mimes:jpg,jpeg,png,gif|max:4096',
        ], [
            'school_name.required' => 'Veuillez entrez le nom de l\'établissement',
            'school_motor.required' => 'Veuillez entrez la devise l\'établissement',
            'school_postal_box.required' => 'Veuillez entrez le code postal de l\'établissement',
            'school_postal_box.min' => 'Le code postal doit avoir minimum 4 caractères',
            'school_logo.required' => 'Veuillez selectionner un logo pour l\'établissement',
            'content.required' => 'Veuillez remplire la description de l\'établissement',
            'school_town.required' => 'Veuillez entrez le nom de la ville ou est situé de l\'établissement',
            'school_location.required' => 'Veuillez entrez le nom du quartier ou est situé de l\'établissement',
            'contact_phone_1.regex' => 'Le numero de téléphone 1 doit être au format XXX-XXX-XXX',
            'contact_phone_2.regex' => 'Le numero de téléphone 2 doit être au format XXX-XXX-XXX',
            'contact_phone_1.required' => 'Le numero de téléphone 1 de l\'établissement est requis',
            'school_image.required' => 'Veuillez selectionner une image de mise en avant',
            'school_image.mimes' => 'L\'image de mise en avant doit être du type (jpg, jpeg, png, gif)',
        ]);

        try {
            if($action === 'update' && isset($request->appconfigurationId)) {
                $appconfiguration = AppConfiguration::findOrFail($request->appconfigurationId);
                if($request->hasFile('school_logo')) {
                    $imagePath = $request->file('school_logo')->store('profiles', 'public');
                    if ($appconfiguration->school_logo) {
                        Storage::disk('public')->delete($appconfiguration->school_logo);
                    }
                    $appconfiguration->school_logo = $imagePath;
                }
                if($request->hasFile('school_image')) {
                    $schoolImagePath = $request->file('school_image')->store('images', 'public');
                    if ($appconfiguration->school_image) {
                        Storage::disk('public')->delete($appconfiguration->school_image);
                    }
                    $appconfiguration->school_image = $schoolImagePath;
                }
                $appconfiguration->update([
                    'school_name' => $request->school_name,
                    'school_motor' => $request->school_motor,
                    'school_postal_box' => $request->school_postal_box,
                    'description' => $request->content,
                    'school_town' => $request->school_town,
                    'school_location' => $request->school_location,
                    'contact_phone_1' => $request->contact_phone_1,
                    'contact_phone_2' => $request->contact_phone_2,
                    'school_email' => $request->school_email,
                ]);
                return response()->json(['success' => 'Configuration de l\'établissement mise à jour avec succès']);
            } else {
                $appconfiguration = AppConfiguration::create([
                    'school_name' => $request->school_name,
                    'school_motor' => $request->school_motor,
                    'school_postal_box' => $request->school_postal_box,
                    'school_logo' => $request->hasFile('school_logo') ? $request->file('school_logo')->store('profiles', 'public') : 'profiles/default/default-avatar.png',
                    'school_image' => $request->hasFile('school_image') ? $request->file('school_image')->store('images', 'public') : 'img/image-sign-in.jpg',
                    'description' => $request->content,
                    'school_town' => $request->school_town,
                    'school_location' => $request->school_location,
                    'contact_phone_1' => $request->contact_phone_1,
                    'contact_phone_2' => $request->contact_phone_2,
                    'school_email' => $request->school_email,
                ]);
                return response()->json(['success' => 'Configuration de l\'établissement enregistrée avec succès']);
            }
        } catch (Exception $ex) {
            dd($ex);
        }
    }

    /**
     * edit appconfiguration
     */
    public function edit($id) {
        $appconfiguration = AppConfiguration::findOrFail($id);
        return response()->json([
            'appconfiguration' => $appconfiguration,
            'content' => $appconfiguration->description
        ]);
    }
}
