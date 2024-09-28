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
    public function index(Request $request) {
        $user = User::find(Auth::id());
        $matieres = Matiere::all();
        $teachers = User::all()->where('typeUser', '=', 'enseignant');
        $searchTeacher = $request->input('searchTeacher');

        $query = User::where('typeUser', '=', 'enseignant');
        if(!empty($searchTeacher)) {
            $query->where(function($q) use ($searchTeacher) {
                $q->where('name', 'LIKE', "%{$searchTeacher}%")
                ->orWhere('surname', 'LIKE', "%{$searchTeacher}%")
                ->orWhere('email', 'LIKE', "%{$searchTeacher}%")
                ->orWhere('phone', 'LIKE', "%{$searchTeacher}%");
            });
        }

        $teachers = $query->paginate(7);

        return view('personnel.teachers', compact('matieres','teachers','user','searchTeacher'));
    }

     /**
     * saving administrators members
     * @param  \Illuminate\Http\Request  $request
     */
    public function store(Request $request) {
        $request->validate([
            'name' => 'required|min:3|max:255',
            'matricule' => 'max:255|unique:users,id',
            'surname' => 'min:3|max:255',
            'email' => 'max:255|unique:users,id',
            'password' => 'required|min:8|max:255',
            'phone' => 'required|min:9|max:255',
            'diplome1' => 'required|min:3|max:255',
            'diplome2' => 'max:255',
            'lieuNaiss' => 'max:255',
            'dateNaiss' => 'max:255',
            'location' => 'max:255',
            'numCni' => 'max:255',
            'sex' => ['required', Rule::in(['M','F'])],
            'profile' => 'image|mimes:jpeg,png,gif|max:4096',
        ], [
            'name.required' => 'Entrez votre nom',
            'matricule.unique' => 'Le matricule existe déja dans la base de données',
            'phone.required' => 'Entrez le numero de téléphone',
            'diplome1.required' => 'Entrez l\'intitulté du diplome 1',
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


    public function edit(Request $request, $id) {
        $matieres = Matiere::all();
        $teacherToEdit = User::findOrFail($id);
        $searchTeacher = $request->input('searchTeacher');

        $query = User::where('typeUser', '=', 'enseignant');
        if(!empty($searchTeacher) && !empty($categoryFilter)) {
            $query->where(function($q) use ($searchTeacher) {
                $q->where('name', 'LIKE', "%{$searchTeacher}%")
                ->orWhere('surname', 'LIKE', "%{$searchTeacher}%")
                ->orWhere('email', 'LIKE', "%{$searchTeacher}%")
                ->orWhere('phone', 'LIKE', "%{$searchTeacher}%");
            });
        }

        $teachers = $query->paginate(7);

        return view('personnel.teachers', compact('matieres','teachers','teacherToEdit','searchTeacher'));
    }

    public function update(Request $request, $id) {
        $request->validate([
            'name' => 'required|min:3|max:255',
            'matricule' => 'max:255',
            'surname' => 'min:3|max:255',
            'email' => 'max:255',
            'password' => 'required|min:8|max:255',
            'phone' => 'required|min:9|max:255',
            'diplome1' => 'required|min:3|max:255',
            'diplome2' => 'max:255',
            'lieuNaiss' => 'max:255',
            'dateNaiss' => 'max:255',
            'location' => 'max:255',
            'numCni' => 'max:255',
            'sex' => ['required', Rule::in(['M','F'])],
            'profile' => 'image|mimes:jpeg,png,gif|max:4096',
        ], [
                'name.required' => 'Entrez votre nom',
                'surname.required' => 'Entrez votre prenom',
                'phone.required' => 'Entrez le numero de téléphone',
                'diplome1.required' => 'Entrez l\'intitulté du diplome 1',
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
