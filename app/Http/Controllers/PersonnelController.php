<?php

namespace App\Http\Controllers;

use App\Models\Personnel;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class PersonnelController extends Controller
{
     /**
     * saving administrators members
     * @param  \Illuminate\Http\Request  $request
     */
    public function store(Request $request) {
        $request->validate([
            'name' => 'required|min:3|max:255',
            'surname' => 'required|min:3|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:8|max:255',
            'phone' => 'required|min:9|max:255',
            'diplome1' => 'required|min:3|max:255',
            'diplome2' => 'required|min:3|max:255',
            'lieuNaiss' => 'required|min:3|max:255',
            'dateNaiss' => 'required|max:255',
            'location' => 'required|min:3|max:255',
            'numCni' => 'required|max:255',
            'sex' => ['required', Rule::in(['M','F'])],
            'fonction' => ['required', Rule::in(['SG','DE','Principale','DET','DEC'])],
            'profile' => 'required|image|mimes:jpeg,png,gif|max:4096',
        ], [
                'name.required' => 'Entrez votre nom',
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
                'fonction.required' => 'Choisisssez la fonction',
                'profile.required' => 'Selectionner une image',
         ]);

        $imagePath = $request->file('profile')->store('profiles', 'public');

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'surname' => $request->surname,
            'phone' => $request->phone,
            'location' => $request->location,
            'lieuNaiss' => $request->lieuNaiss,
            'dateNaiss' => $request->dateNaiss,
            'diplome1' => $request->diplone1,
            'diplome2' => $request->diplome2,
            'numCni' => $request->numCni,
            'profile' => $imagePath,
            'typeUser' => 'personnel',
            'password' => Hash::make($request->password),
            'sex' => $request->sex,
        ]);

        return view('dashboard');
    }
}
