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
        $request->validate([
            'club_name' => 'required|min:3|max:255',Rule::unique('clubs')->ignore($request->clubconfigurationId),
            'content' => 'required',
        ], [
            'club_name.required' => 'Veuillez entrez un nom de club',
            'club_name.min' => 'Le nom du club doit contenir au minimum 3 caractères',
            'club_name.max' => 'Le nom du club doit contenir au maximum 255 caractères',
            'club_name.unique' => 'Ce nom de club existe déja',
            'content.required' => 'Veuillez remplir la description du club',
        ]);
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
                $clubconfiguration->update([
                    'club_name' => $request->club_name,
                    'contenu' => $request->content
                ]);
                return response()->json(['success' => 'Configuration du club mis à jour avec succès']);
            }
        } catch (Exception $ex) {
            dd($ex->getMessage());
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
