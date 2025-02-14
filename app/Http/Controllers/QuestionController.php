<?php

namespace App\Http\Controllers;

use App\Models\Devoir;
use App\Models\Question;
use App\Models\Reponse;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuestionController extends Controller
{
    /**
     *
     */
    public function index(Request $request)
    {
        // utils vars
        $user = User::find(Auth::id());
        // query vars :
        $devoirs = Devoir::all();

        // filter vars :
        $searchQuestion = $request->input('searchQuestion');
        $devoirFilter = $request->input('devoirFilter');
        // querying :
        $query = Question::query();

        // filtering :
        if(!empty($searchQuestion)) {
            $query->where('question', 'LIKE', "%{$searchQuestion}%");
        }
        if(!empty($devoirFilter)) {
            $query->whereHas('devoir', function ($q) use ($devoirFilter) {
                $q->where('id', $devoirFilter);
            });
        }

        //dd($query);
        $questions = $query->paginate(10);

        if($request->ajax()) {
            return view('partials._questions_table', compact('devoirs','questions','user'));
        } else {
            return view('education.questions', compact('devoirs','questions','user'));
        }
    }

    /**
     *
     */
    public function store(Request $request) {

        $request->validate([
            'content' => 'required',
            'devoir_id' => 'required|exists:devoirs,id',
        ], [
            'content.required' => 'Veuillez entrez le contenu de la question',
            'devoir_id.required' => 'Veuillez selectionnez le devoir pour la question',
        ]);

        $question = Question::create([
            'question' => $request->content,
            'devoir_id' => $request->devoir_id,
        ]);

        if($question) {
            return response()->json(['success' => 'Question ajoutée avec succès!']);
        } else {
            return response()->json(['error' => 'Erreur inconue lors de l\'ajout de la question']);
        }

    }

    /**
     * editing specific question
     */
    public function edit($id) {
        $questionToEdit = Devoir::findOrFail($id);
        return response()->json([
            'questionToEdit' => $questionToEdit,
            'content' => $questionToEdit->question
        ]);
    }
    /**
     *
     */
    public function update(Request $request, $id) {
        $request->validate([
            'content' => 'required',
            'devoir_id' => 'required|exists:devoirs,id',
        ], [
            'content.required' => 'Veuillez entrez la description de la question',
            'devoir_id.required' => 'Veuillez selectionnez le devoir',
        ]);

        $question = Question::findOrFail($id);
        $question->update([
            'question' => $request->content,
            'devoir_id' => $request->devoir_id,
        ]);

        return response()->json(['success' => 'Question mise à jour avec succès']);
    }
    /**
     *
     */
    public function destroy($id) {
        $question = Question::findOrFail($id);
        $question->delete();
        return redirect()->route('education.questions')->with('deleteSuccess', 'Question supprimée avec succès !');
    }
}
