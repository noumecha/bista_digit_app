<?php

namespace App\Http\Controllers;

use App\Models\Devoir;
use App\Models\Question;
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
    public function store() {

    }
    /**
     *
     */
    public function update() {

    }
    /**
     *
     */
    public function destroy() {

    }
    /**
     *
     */
    public function migrate() {

    }
    /**
     *
     */
    public function deleteInCurrentYear() {

    }
}
