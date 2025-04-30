<?php

namespace App\Http\Controllers;

use App\Models\Club;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ClubConfigurationController extends Controller
{
    /**
     * index functions to manage club configuration
     * when the presient of the club is connected
     */
    public function index() {
        $clubconfiguration = Club::all()->where('president_id',Auth::id());
        return view('configurations.club_configuration', compact('clubconfiguration'));
    }

     /**
     * index function to create or update configuration
     */
    public function update(Request $request, $action) {
        $request->validate([
            'club_name' => 'required|min:3|max:255|unique:clubs,club_name',
            'content' => 'required',
            'club_image' => 'required|image|mimes:jpg,jpeg,png,gif|max:4096',
        ], [
            'club_name.required' => 'Veuillez entrez le nom du club',
            'club_name.min' => 'Le nom du club doit contenir minimum 3 caractères',
            'club_name.max' => 'Le nom du club doit contenir au maximum 255 caractères',
            'club_name.unique' => 'Ce nom de club existe déja',
            'content.required' => 'Veuillez remplir la description du club',
            'club_image.required' => 'Veuillez selectionner une image de mise en avant',
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
                    'contenu' => $request->content,
                    'club_image' => $imagePath,
                ]);
                return response()->json(['success' => 'Configuration du club mis à jour avec succès']);
            }
        } catch (Exception $ex) {
            dd($ex);
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
