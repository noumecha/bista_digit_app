<?php

namespace App\Http\Controllers;

use App\Models\Classe;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClasseController extends Controller
{
    /**
     * Classe controller implmentation
     */
    public function index()
    {
        $user = User::find(Auth::id());
        $classes = Classe::All();

        return view('education.classes',['classes' => $classes], compact('user'));
    }

    /**
     *
     */
    public function store(Request $request) {
        $request->validate([
            'libelleClasse' => 'required|min:3|max:255',
            'effectifClasse' => 'required',
            'cycleClasse' => 'required|min:3|max:255',
        ], [
                'libelleClasse.required' => 'Entrez le libellé de la classe',
                'effectifClasse.required' => 'Entrez l\'effectif de la classe',
                'cycleClasse.required' => 'Choisissez le cycle de la classe',
         ]);

        Classe::create([
            'libelleClasse' => $request->libelleClasse,
            'effectifClasse' => $request->effectifClasse,
            'cycleClasse' => $request->cycleClasse,
        ]);

        return redirect()->route('education.classes')->with('success', 'Classe créée avec succès !');
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
