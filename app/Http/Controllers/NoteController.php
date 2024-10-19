<?php

namespace App\Http\Controllers;

use App\Models\Classe;
use App\Models\Evaluation;
use App\Models\Matiere;
use App\Models\Note;
use App\Models\Remplissage;
use App\Models\User;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    /**
     *
     */
    public function index(Request $request) {
        $notes = Note::all();
        $matieres = Matiere::all();
        $remplissages = Remplissage::all()->where('statut','=','activé');
        $evaluations = Evaluation::all();
        $classes = Classe::all();
        $searchNote = $request->input('searchNote');
        $classeFilter = $request->input('classeFilter');
        $matiereFilter = $request->input('matiereFilter');
        $remplissageFilter = $request->input('remplissageFilter');
        $students = User::query()->where('typeUser','=','eleve')->paginate(10);

        $query = Note::query();

        if(!empty($searchNote)) {
            $query->whereHas('user', function($q) use ($searchNote) {
                $q->where('name','LIKE',"%{$searchNote}%");
            });
        }
        if (!empty($classeFilter)) {
            $query->where('classe_id', $classeFilter);
        }
        if (!empty($matiereFilter)) {
            $query->where('matiere_id', $matiereFilter);
        }
        if (!empty($remplissageFilter)) {
            $query->where('remplissage_id',$remplissageFilter);
        }
        $notes = $query->paginate(10);

        if($request->ajax()) {
            return view('partials._note_table', compact('students','notes'))->render();
        } else {
            return view('evaluation.notes', compact('remplissages','searchNote','classeFilter','matiereFilter','remplissageFilter','classes','evaluations','matieres','students','evaluations','notes'));
        }
    }

    /**
     *
     */
    public function store(Request $request) {

    }

    /**
     *
     */
    public function edit($id) {

    }

    /**
     *
     */
    public function update(Request $request, $id) {

    }


    /**
     *
     */
    public function destroy($id) {

    }
}
