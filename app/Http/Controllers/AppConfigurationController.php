<?php

namespace App\Http\Controllers;

use App\Models\AppConfiguration;
use Illuminate\Http\Request;

class AppConfigurationController extends Controller
{
    /**
     * index functions to manage app confiuration
     */
    public function index() {
        $appconfiguration = AppConfiguration::all()->first();
        return view('configurations.app_configuration', compact('appconfiguration'));
    }

    /**
     * index function to create or update configuration
     */
    public function update(Request $request) {
        $request->validate([
            'school_name' => 'required|string|min:26|max:255',
            'school_motor' => 'required|string|min:26|max:255',
            'school_postal_box' => 'required|integer|min:4|max:255',
            'school_logo' => 'image|mimes:jpeg,png,gif|max:4096',
            'content' => 'required',
            'school_town' => 'required|min:3|max:255',
            'school_location' => 'required|min:3|max:255',
            'contact_phone_1' => ['required', 'regex:/^[0-9]{3}-[0-9]{3}-[0-9]{3}$/'],
            'contact_phone_2' => ['required', 'regex:/^[0-9]{3}-[0-9]{3}-[0-9]{3}$/'],
            'school_email' => 'nullable|email|max:255',
        ], [
            'school_name.required' => 'Veuillez entrez le nom de l\'établissement',
            'school_motor.required' => 'Veuillez entrez la devise l\'établissement',
            'school_postal_box.required' => 'Veuillez entrez le code postal de l\'établissement',
            'school_logo.required' => 'Veuillez selectionner un logo pour l\'établissement',
            'content.required' => 'Veuillez remplire la description de l\'établissement',
            'school_town.required' => 'Veuillez entrez le nom de la ville ou est situé de l\'établissement',
            'school_location.required' => 'Veuillez entrez le nom du quartier ou est situé de l\'établissement',
            'contact_phone_1.regex' => 'Le numero de téléphone 1 doit être au format XXX-XXX-XXX',
            'contact_phone_2.regex' => 'Le numero de téléphone 2 doit être au format XXX-XXX-XXX',
            'contact_phone_1.required' => 'Le numero de téléphone 1 de l\'établissement est requis',
        ]);

        $appconfiguration = AppConfiguration::createOrUpdate([
            'school_name' => $request->school_name,
            'school_motor' => $request->school_motor,
            'school_postal_box' => $request->school_postal_box,
            'school_logo' => $request->hasFile('school_logo') ? $request->file('school_logo')->store('profiles', 'public') : 'profiles/default/default-avatar.png',
            'description' => $request->content,
            'school_town' => $request->school_town,
            'school_location' => $request->school_location,
            'contact_phone_1' => $request->contact_phone_1,
            'contact_phone_2' => $request->contact_phone_2,
            'school_email' => $request->school_email,
        ]);

        if ($appconfiguration) {
            return response()->json(['success' => 'Configuration de l\'établissement mis à jour avec succès']);
        }
    }
}
