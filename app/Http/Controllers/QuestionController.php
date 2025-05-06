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
        // getting custom id of question to perform error UX
        $customAttributes = [];
        if ($request->has('reponses')) {
            foreach ($request->input('reponses') as $index => $value) {
                $repId = $index + 1;
                $customAttributes["reponses.$index"] = "réponse $repId";
            }
        }
        // validates entries
        $request->validate([
            'content' => 'required',
            'devoir_id' => 'required|exists:devoirs,id',
            'reponses' => 'required|array',
            'reponses.*' => 'required|string',
            'status_checkbox' => [
                'required',
                function ($attribute, $fail, $value) use ($request) {
                    // check if aleast one question is true
                    if(!in_array('1', $request->input('status_checkbox', []))) {
                        $fail('Au moins une réponse doit être défini comme vraie');
                    }
                }
            ]
        ], [
            'content.required' => 'Veuillez entrez le contenu de la question',
            'reponses.required' => 'Veuillez ajouter au moins une réponse à la question',
            'devoir_id.required' => 'Veuillez selectionnez le devoir pour la question',
            'status_checkbox.required' => 'Veuillez définir au moins une réponse comme correcte.',
            'reponses.*.required' => 'Veuillez remplir la :attribute',
        ], $customAttributes);

        $question = Question::create([
            'question' => $request->content,
            'devoir_id' => $request->devoir_id,
        ]);

        // adding reponses with her status
        foreach ($request->input('reponses') as $index => $reponse) {
            $question->reponses()->create([
                'reponse' => $reponse,
                'question_id' => $question->id,
                'status' => $request->input('status')[$index],
            ]);
        }

        if($question) {
            return response()->json(['success' => 'Question et ses réponses ajoutée avec succès!']);
        } else {
            return response()->json(['error' => 'Erreur inconue lors de l\'ajout de la question']);
        }

    }

    /**
     * editing specific question
     */
    public function edit($id) {
        $questionToEdit = Question::findOrFail($id);
        $reponses = Reponse::all()->where('question_id' ,$questionToEdit->id);
        return response()->json([
            'questionToEdit' => $questionToEdit,
            'content' => $questionToEdit->question,
            'reponses' => $reponses
        ]);
    }

    /**
     *
     */
    public function update(Request $request, $id) {
        $customAttributes = [];
        if ($request->has('reponses')) {
            foreach ($request->input('reponses') as $index => $value) {
                $repId = $index + 1;
                $customAttributes["reponses.$index"] = "réponse $repId";
            }
        }
        // make validations
        $request->validate([
            'content' => 'required',
            'devoir_id' => 'required|exists:devoirs,id',
            'reponses' => 'required|array',
            'reponses.*' => 'required|string',
            'status_checkbox' => [
                'required',
                function ($attribute, $fail, $value) use ($request) {
                    // check if aleast one question is true
                    if(!in_array('1', $request->input('status_checkbox', []))) {
                        $fail('Au moins une réponse doit être défini comme vraie');
                    }
                }
            ]
        ], [
            'content.required' => 'Veuillez entrez la description de la question',
            'reponses.required' => 'Veuillez ajouter au moins une réponse à la question',
            'devoir_id.required' => 'Veuillez selectionnez le devoir',
            'status_checkbox.required' => 'Veuillez définir au moins une réponse comme correcte.',
            'reponses.*.required' => 'Veuillez remplir la :attribute',
        ], $customAttributes);
        //dd($request);
        $question = Question::findOrFail($id);
        $question->update([
            'question' => $request->content,
            'devoir_id' => $request->devoir_id,
        ]);
        // delete old reponses
        Reponse::where('question_id', $question->id)->delete();
        // add new reponses with them status
        foreach ($request->reponses as $index => $reponse) {
            Reponse::create([
                'reponse' => $reponse,
                'question_id' => $question->id,
                'status' => $request->status[$index],
            ]);
        }
        return response()->json(['success' => 'Question et réponses mises à jour avec succès']);
    }

    /**
     *
     */
    public function destroy($id) {
        $question = Question::findOrFail($id);
        Reponse::where('question_id', $question->id)->delete();
        $question->delete();
        return redirect()->route('education.questions')->with('deleteSuccess', 'Question et réponses supprimée avec succès !');
    }
}
