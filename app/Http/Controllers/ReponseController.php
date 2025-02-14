<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Reponse;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReponseController extends Controller
{
    /**
     *
     */
    public function index(Request $request)
    {
        // utils vars
        $user = User::find(Auth::id());
        // query vars :
        $questions = Question::all();

        // filter vars :
        $searchReponse = $request->input('searchQuestion');
        $questionFilter = $request->input('questionFilter');
        // querying :
        $query = Reponse::query();

        // filtering :
        if(!empty($searchQuestion)) {
            $query->where('reponse', 'LIKE', "%{$searchQuestion}%");
        }
        if(!empty($questionFilter)) {
            $query->whereHas('question', function ($q) use ($questionFilter) {
                $q->where('id', $questionFilter);
            });
        }

        //dd($query);
        $reponses = $query->paginate(10);

        if($request->ajax()) {
            return view('partials._reponses_table', compact('reponses','questions','user'));
        } else {
            return view('education.reponses', compact('reponses','questions','user'));
        }
    }

    /**
     *
     */
    public function store(Request $request) {
        $request->validate([
            'content' => 'required',
            'question_id' => 'required|exists:questions,id',
            'status' => 'required|boolean',
        ], [
            'content.required' => 'Veuillez entrez le contenu de la reponse',
            'question_id.required' => 'Veuillez selectionnez la question pour la réponse',
            'status.required' => 'Veuillez définir le status de la réponse (vrai ou faux)'
        ]);

        $reponse = Reponse::create([
            'reponse' => $request->content,
            'question_id' => $request->question_id,
            'status' => $request->status
        ]);

        if($reponse) {
            return response()->json(['success' => 'Repone ajoutée avec succès!']);
        } else {
            return response()->json(['error' => 'Erreur inconue lors de l\'ajout de la reponse']);
        }
    }

    /**
     *
     */
    public function edit($id) {
        $reponseToEdit = Reponse::findOrFail($id);
        return response()->json([
            'reponseToEdit' => $reponseToEdit,
            'content' => $reponseToEdit->reponse
        ]);
    }

    /**
     *
     */
    public function update(Request $request, $id) {
        $request->validate([
            'content' => 'required',
            'question_id' => 'required|exists:questions,id',
            'status' => 'required|boolean',
        ], [
            'content.required' => 'Veuillez entrez la réponse',
            'question_id.required' => 'Veuillez selectionnez la question',
            'status.required' => 'Veuillez définir le status de la réponse (vrai ou faux)'
        ]);

        $reponse = Question::findOrFail($id);
        $reponse->update([
            'reponse' => $request->content,
            'question_id' => $request->question_id,
            'status' => $request->status
        ]);

        return response()->json(['success' => 'Réponse mise à jour avec succès']);
    }

    /**
     *
     */
    public function destroy($id) {
        $reponse = Question::findOrFail($id);
        $reponse->delete();
        return redirect()->route('education.reponses')->with('deleteSuccess', 'Réponse supprimée avec succès !');
    }
}
