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
    public function index(Request $request) {
        $user = User::find(Auth::id());
        $classes = Classe::all();
        $searchStudent = $request->input('searchStudent');
        $classeFilter = $request->input('classFilter');

        $query = User::where('typeUser', '=', 'eleve');
        if(!empty($searchStudent) && !empty($classeFilter)) {
            $query->where(function ($q) use ($searchStudent) {
                $q->where('name', 'LIKE', "%{$searchStudent}%")
                ->orWhere('surname', 'LIKE', "%{$searchStudent}%")
                ->orWhere('email', 'LIKE', "%{$searchStudent}%")
                ->orWhere('phone', 'LIKE', "%{$searchStudent}%");
            })->where('classe_id', $classeFilter);
        } elseif(!empty($classeFilter)) {
            $query->where('classe_id', $classeFilter)
            ->where('typeUser', '=', 'eleve');
        } elseif (!empty($searchStudent)) {
            $query->where(function ($q) use ($searchStudent) {
                $q->where('name', 'LIKE', "%{$searchStudent}%")
                ->orWhere('surname', 'LIKE', "%{$searchStudent}%")
                ->orWhere('email', 'LIKE', "%{$searchStudent}%")
                ->orWhere('phone', 'LIKE', "%{$searchStudent}%");
            });
        }

        $students = $query->paginate(10);
        if($request->ajax()) {
            return view('', compact('classes','students','user','searchStudent','classeFilter'));
        } else {
            return view('utilisateurs.students', compact('classes','students','user','searchStudent','classeFilter'));
        }
    }

    /**
    * saving students
    */
    public function store(Request $request) {

        $request->validate([
            'name' => 'required|min:3|max:255',
            'matricule' => 'required|min:3|max:255|unique:users',
            'surname' => 'required|min:3|max:255',
            'email' => 'nullable|email|max:255|unique:users',
            'password' => 'required|min:8|max:255',
            'phone' => ['required', 'regex:/^[0-9]{3}-[0-9]{3}-[0-9]{3}$/'],
            'lieuNaiss' => 'required|min:3|max:255',
            'dateNaiss' => 'required|max:255',
            'location' => 'required|min:3|max:255',
            'classe_id' => 'required|exists:classes,id',
            'numCni' => 'max:255',
            'sex' => ['required', Rule::in(['M','F'])],
            'profile' => 'image|mimes:jpeg,png,gif|max:4096',
        ], [
            'name.required' => 'Entrez le nom de l\'élève',
            'email.email' => 'Entrez une adresse email valide',
            'email.unique' => 'Un utilisateur avec cette adresse email existe déjà',
            'surname.required' => 'Entrez le prenom de l\'élève',
            'phone.required' => 'Entrez le numero de téléphone de l\'élève ou du parent',
            'phone.regex' => 'Le numero de téléphone doit être au format XXX-XXX-XXX',
            'name.min' => 'Le nom doit contenir au moins 3 caractères',
            'surname.min' => 'Le prenom doit contenir au moins 3 caractères',
            'location.required' => 'Entrez le lieu de résidence',
            'lieuNaiss.required' => 'Entrez le lieu de naissance',
            'dateNaiss.required' => 'Entrez la date de naissance',
            'matricule.required' => 'Entrez le matricule de l\'élève',
            'matricule.unique' => 'Ce matricule existe déja dans la base de données',
            'classe_id.required' => 'Veuillez selectionnez une classe',
            'sex.required' => 'Choisissez le sexe',
        ]);

        //dd($request);
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

        return redirect()->route('utilisateur.students')->with('success', 'Eleve ajouté avec succès');
    }

    public function edit(Request $request, $id) {
        $classes = Classe::all();
        $studentToEdit = User::findOrFail($id);
        $searchStudent = $request->input('searchStudent');
        $classeFilter = $request->input('classFilter');

        $query = User::where('typeUser', '=', 'eleve');
        if(!empty($searchStudent) && !empty($classeFilter)) {
            $query->where(function ($q) use ($searchStudent) {
                $q->where('name', 'LIKE', "%{$searchStudent}%")
                ->orWhere('surname', 'LIKE', "%{$searchStudent}%")
                ->orWhere('email', 'LIKE', "%{$searchStudent}%")
                ->orWhere('phone', 'LIKE', "%{$searchStudent}%");
            })->where('classe_id', $classeFilter);
        } elseif(!empty($classeFilter)) {
            $query->where('classe_id', $classeFilter)
            ->where('typeUser', '=', 'eleve');
        } elseif (!empty($searchStudent)) {
            $query->where(function ($q) use ($searchStudent) {
                $q->where('name', 'LIKE', "%{$searchStudent}%")
                ->orWhere('surname', 'LIKE', "%{$searchStudent}%")
                ->orWhere('email', 'LIKE', "%{$searchStudent}%")
                ->orWhere('phone', 'LIKE', "%{$searchStudent}%");
            });
        }

        $students = $query->paginate(7);

        return view('personnel.students', compact('classes','students','studentToEdit','searchStudent','classeFilter'));
    }

    public function update(Request $request, $id) {
        $request->validate([
            'name' => 'required|min:3|max:255',
            'matricule' => 'required|min:3|max:255',
            'surname' => 'required|min:3|max:255',
            'email' => ['nullable','email','max:255',Rule::unique('users')->ignore($id)],
            'password' => 'required|min:8|max:255',
            'phone' => ['required', 'regex:/^[0-9]{3}-[0-9]{3}-[0-9]{3}$/'],
            'lieuNaiss' => 'required|min:3|max:255',
            'dateNaiss' => 'required|max:255',
            'location' => 'required|min:3|max:255',
            'classe_id' => 'required|exists:classes,id',
            'numCni' => 'max:255',
            'sex' => ['required', Rule::in(['M','F'])],
            'profile' => 'image|mimes:jpeg,png,gif|max:4096',
        ], [
            'name.required' => 'Entrez le nom de l\'élève',
            'surname.required' => 'Entrez le prenom de l\'élève',
            'email.email' => 'Entrez une adresse email valide',
            'email.unique' => 'Un utilisateur avec cette adresse email existe déjà',
            'phone.required' => 'Entrez le numero de téléphone de l\'élève ou du parent',
            'phone.regex' => 'Le numero de téléphone doit être au format XXX-XXX-XXX',
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
        $student->update($request->except('profile'));

        return redirect()->route('utilisateur.students')->with('success', 'Eleve mis à jour avec succès');
    }

    public function destroy($id) {
        $student = User::findOrFail($id);
        $student->delete();

        return redirect()->route('utilisateur.students')->with('deleteSuccess', 'Elève supprimer avec succès');
    }

}
