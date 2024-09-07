<?php

namespace App\Http\Controllers;

use App\Models\Matiere;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MatiereController extends Controller
{
    /**
     * Matiere controller implmentation
     */
    public function index()
    {
        $user = User::find(Auth::id());
        $matieres = Matiere::all();

        return view('education.matiere',['matieres' => $matieres], compact('user'));
    }

    /**
     *
     */
    public function store(Request $request) {
        $request->validate([
            'libelleMatiere' => 'required|min:3|max:255',
            'codeMatiere' => 'required|email|max:255|unique:users',
        ], [
                'libelleMatiere.required' => 'Entrez le libellé de la matière',
                'codeMatiere.required' => 'Entrez le code de la matière',
         ]);

        Matiere::create([
            'libelleMatiere' => $request->libelleMatiere,
            'codeMatiere' => $request->codeMatiere,
        ]);

        return view('dashboard');
    }

    /**
     *
     */
    public function edit() {

    }

    /**
     *
     */
    public function delete() {

    }
}
