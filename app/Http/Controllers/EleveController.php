<?php

namespace App\Http\Controllers;

use App\Models\Classe;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class EleveController extends Controller
{
    /**
     *
     */
    public function index() {
        $user = User::find(Auth::id());
        $classes = Classe::all();
        $students = User::all()->where('typeUser', '=', 'eleve');

        return view('personnel.students', compact('classes','students','user'));
    }

    /**
    * saving administrators members
    * @param  \Illuminate\Http\Request  $request
    */
    public function store(Request $request) {

        $students = User::all()->where('typeUser', '=', 'eleve');

        $request->validate([
            'name' => 'required|min:3|max:255',
            'matricule' => 'required|min:3|max:255|unique:users',
            'surname' => 'required|min:3|max:255',
            'email' => 'email|max:255|unique:users',
            'password' => 'required|min:8|max:255',
            'phone' => 'required|min:9|max:255',
            'lieuNaiss' => 'required|min:3|max:255',
            'dateNaiss' => 'required|max:255',
            'location' => 'required|min:3|max:255',
            'classe_id' => 'required',
            'numCni' => 'max:255',
            'sex' => ['required', Rule::in(['M','F'])],
            'profile' => 'image|mimes:jpeg,png,gif|max:4096',
        ], [
            'name.required' => 'Entrez votre nom',
            'surname.required' => 'Entrez votre prenom',
            'phone.required' => 'Entrez le numero de téléphone de l\'élève ou du parent',
            'location.required' => 'Entrez le lieu de résidence',
            'lieuNaiss.required' => 'Entrez le lieu de naissance',
            'dateNaiss.required' => 'Entrez la date de naissance',
            'matricule.required' => 'Entrez le matricule de l\'élève',
            'classe_id.required' => 'Veuillez selectionnez une classe',
            'sex.required' => 'Choisissez le sexe',
            'classe_id.required' => 'Veuillez chosir une classe',
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
            'numCni' => $request->numCni,
            'classe_id' => $request->classe_id,
            'profile' => $request->hasFile('profile') ? $request->file('profile')->store('profiles', 'public') : '',
            'typeUser' => 'eleve',
            'password' => Hash::make($request->password),
            'sex' => $request->sex,
        ]);

        return view('personnel.students', compact('students'));
    }

    public function edit($id) {
        $classes = Classe::all();
        $students = User::all()->where('typeUser', '=', 'eleve');
        $studentToEdit = User::findOrFail($id);

        return view('personnel.students', compact('classes','students','studentToEdit'));
    }

    public function update(Request $request, $id) {
        $request->validate([
            'name' => 'required|min:3|max:255',
            'matricule' => 'required|min:3|max:255',
            'surname' => 'required|min:3|max:255',
            'email' => 'email|max:255',
            'password' => 'required|min:8|max:255',
            'phone' => 'required|min:9|max:255',
            'lieuNaiss' => 'required|min:3|max:255',
            'dateNaiss' => 'required|max:255',
            'location' => 'required|min:3|max:255',
            'classe_id' => 'required',
            'numCni' => 'max:255',
            'sex' => ['required', Rule::in(['M','F'])],
            'profile' => 'image|mimes:jpeg,png,gif|max:4096',
        ], [
            'name.required' => 'Entrez votre nom',
            'surname.required' => 'Entrez votre prenom',
            'phone.required' => 'Entrez le numero de téléphone de l\'élève ou du parent',
            'location.required' => 'Entrez le lieu de résidence',
            'lieuNaiss.required' => 'Entrez le lieu de naissance',
            'dateNaiss.required' => 'Entrez la date de naissance',
            'classe_id.required' => 'Veuillez selectionnez une classe',
            'matricule.required' => 'Entrez le matricule de l\'élève',
            'sex.required' => 'Choisissez le sexe',
            'classe_id.required' => 'Veuillez chosir une classe',
        ]);


        $student = User::findOrFail($id);

        if($request->hasFile('profile')) {
            $imagePath = $request->file('profile')->store('profiles', 'public');
            if ($student->profile) {
                Storage::disk('public')->delete($student->profile);
            }
            $student->profile = $imagePath;
        }
        dd($imagePath);
        $student->update($request->except('profile'));

        return redirect()->route('utilisateur.students')->with('success', 'Eleve mis à jour avec succès');
    }

    public function destroy($id) {
        $student = User::findOrFail($id);
        $student->delete();

        return redirect()->route('utilisateur.students')->with('deleteSuccess', 'Elève supprimer avec succès');
    }

}
