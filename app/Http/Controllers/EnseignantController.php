<?php

namespace App\Http\Controllers;

use App\Models\Matiere;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class EnseignantController extends Controller
{
    /**
     *
     */
    public function index() {
        $user = User::find(Auth::id());
        $matieres = Matiere::all();
        $teachers = User::all()->where('typeUser', '=', 'enseignant');

        return view('personnel.teachers', compact('matieres','teachers','user'));
    }

     /**
     * saving administrators members
     * @param  \Illuminate\Http\Request  $request
     */
    public function store(Request $request) {
        $request->validate([
            'name' => 'required|min:3|max:255',
            'matricule' => 'required|max:255',
            'surname' => 'required|min:3|max:255',
            'email' => 'email|max:255|unique:users',
            'password' => 'required|min:8|max:255',
            'phone' => 'required|min:9|max:255',
            'diplome1' => 'required|min:3|max:255',
            'diplome2' => 'required|min:3|max:255',
            'lieuNaiss' => 'required|min:3|max:255',
            'dateNaiss' => 'required|max:255',
            'location' => 'required|min:3|max:255',
            'numCni' => 'required|max:255',
            'sex' => ['required', Rule::in(['M','F'])],
            'profile' => 'image|mimes:jpeg,png,gif|max:4096',
        ], [
                'name.required' => 'Entrez votre nom',
                'matricule.required' => 'Entrez le matricule',
                'surname.required' => 'Entrez votre prenom',
                'phone.required' => 'Entrez le numero de téléphone',
                'location.required' => 'Entrez le lieu de résidence',
                'lieuNaiss.required' => 'Entrez le lieu de naissance',
                'dateNaiss.required' => 'Entrez la date de naissance',
                'diplome1.required' => 'Entrez l\'intitulté du diplome 1',
                'diplome2.required' => 'Entrez l\'intitulté du diplome 2',
                'numCni.required' => 'Entrez le numero de la CNI',
                'sex.required' => 'Choisissez le sexe',
         ]);

        User::create([
            'name' => $request->name,
            'matricule' => $request->matricule,
            'email' => $request->email,
            'surname' => $request->surname,
            'phone' => $request->phone,
            'location' => $request->location,
            'lieuNaiss' => $request->lieuNaiss,
            'dateNaiss' => $request->dateNaiss,
            'diplome1' => $request->diplome1,
            'diplome2' => $request->diplome2,
            'numCni' => $request->numCni,
            'profile' => $request->hasFile('profile') ? $request->file('profile')->store('profiles', 'public') : '',
            'typeUser' => 'enseignant',
            'password' => Hash::make($request->password),
            'sex' => $request->sex,
        ]);

        return redirect()->route('utilisateur.teachers')->with('success', 'Enseignant ajouté avec succès');
    }


    public function edit($id) {
        $matieres = Matiere::all();
        $teachers = User::all()->where('typeUser', '=', 'enseignant');
        $teacherToEdit = User::findOrFail($id);

        return view('personnel.teachers', compact('matieres','teachers','teacherToEdit'));
    }

    public function update(Request $request, $id) {
        $request->validate([
            'name' => 'required|min:3|max:255',
            'matricule' => 'required|max:255',
            'surname' => 'required|min:3|max:255',
            'email' => 'required|email|max:255',
            'password' => 'required|min:8|max:255',
            'phone' => 'required|min:9|max:255',
            'diplome1' => 'required|min:3|max:255',
            'diplome2' => 'required|min:3|max:255',
            'lieuNaiss' => 'required|min:3|max:255',
            'dateNaiss' => 'required|max:255',
            'location' => 'required|min:3|max:255',
            'numCni' => 'required|max:255',
            'sex' => ['required', Rule::in(['M','F'])],
            'profile' => 'image|mimes:jpeg,png,gif|max:4096',
        ], [
                'name.required' => 'Entrez votre nom',
                'matricule.required' => 'Entrez le matricule',
                'surname.required' => 'Entrez votre prenom',
                'email.required' => 'Entrez l\'adresse email',
                'phone.required' => 'Entrez le numero de téléphone',
                'location.required' => 'Entrez le lieu de résidence',
                'lieuNaiss.required' => 'Entrez le lieu de naissance',
                'dateNaiss.required' => 'Entrez la date de naissance',
                'diplome1.required' => 'Entrez l\'intitulté du diplome 1',
                'diplome2.required' => 'Entrez l\'intitulté du diplome 2',
                'numCni.required' => 'Entrez le numero de la CNI',
                'sex.required' => 'Choisissez le sexe',
         ]);

        $teacher = User::findOrFail($id);

        if($request->hasFile('profile')) {
            $imagePath = $request->file('profile')->store('profiles', 'public');
            if ($teacher->profile) {
                Storage::disk('public')->delete($teacher->profile);
            }
            $teacher->profile = $imagePath;
        }
        $teacher->update($request->except('profile'));

        return redirect()->route('utilisateur.teachers')->with('success', 'Enseignant mis à jour avec succès');
    }

    public function destroy($id) {
        $teacher = User::findOrFail($id);
        $teacher->delete();

        return redirect()->route('utilisateur.teachers')->with('deleteSuccess', 'Enseignant supprimer avec succès');
    }
}
