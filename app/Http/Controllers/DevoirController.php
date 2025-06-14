<?php

namespace App\Http\Controllers;

use App\Models\AnneeScolaire;
use App\Models\Classe;
use App\Models\ClasseAnneeScolaireStudent;
use App\Models\Devoir;
use App\Models\DevoirAnneeScolaire;
use App\Models\DevoirResult;
use App\Models\Matiere;
use App\Models\Question;
use App\Models\QuestionUserReponse;
use App\Models\User;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class DevoirController extends Controller
{
    /**
     * devoir index
     */
    public function index(Request $request)
    {
        $devs = Devoir::all();
        foreach ($devs as $dev) {
            $endDate = new DateTime($dev->date_fin);
            $startDate = new DateTime($dev->date_debut);
            $currentDate = new DateTime();
            if ($currentDate >= $endDate && $dev->statut !== "terminé") {
                $dev->update(['statut' => 'terminé']);
            } elseif ($currentDate >= $startDate && $currentDate <= $endDate) {
                $dev->update(['statut' => 'en cours']);
            } elseif ($currentDate < $startDate) {
                $dev->update(['statut' => 'programmé']);
            }
        }
        // utils vars
        $teacher = User::find(Auth::id());
        $activeYear = AnneeScolaire::all()->where('statut','=', true)->first();
        if($teacher->typeUser === "enseignant") {
            $matieres = $teacher->teacherMatieres($activeYear->id);
            $classes = $teacher->teacherClasses($activeYear->id);
        } else if ($teacher->typeUser === "eleve") {
            $matieres = $teacher->studentClasseMatiere($activeYear->id, $teacher->getClasse()->id);
            $classes = Classe::all()->where('id', $teacher->getClasse()->id);
        } else {
            $matieres = Matiere::all();
            $classes = Classe::all();
        }
        // query vars :
        $migrateYears = AnneeScolaire::all()->where('created_at', '>', $activeYear->created_at);
        $devoirSchoolYears = DevoirAnneeScolaire::all()->where('annee_scolaire_id','=', $activeYear->id);
        $devoirSchoolYearsIds = $devoirSchoolYears->pluck('devoir_id');
        // filter vars :
        $searchDevoir = $request->input('searchDevoir');
        $classeFilter = $request->input('classeFilter');
        $matiereFilter = $request->input('matiereFilter');
        $statutFilter = $request->input('statutFilter');
        // querying :
        if($teacher->typeUser === "enseignant") {
            #filter by teacher matiere ids
            $teacherMatsIds = $teacher->teacherMatieres($activeYear->id)->pluck('id');
            #filter by teacher classe ids
            $teacherClassesIds = $teacher->teacherClasses($activeYear->id)->pluck('id');
        } else if ($teacher->typeUser === "eleve") {
            #filter by student classe matiere ids
            $teacherMatsIds = $teacher->studentClasseMatiere($activeYear->id, $teacher->getClasse()->id)->pluck('id');
            #filter by student classe
            $teacherClassesIds = Classe::all()->where('id', $teacher->getClasse()->id)->pluck('id');
        } else {
            $teacherMatsIds = Matiere::all()->pluck('id');
            $teacherClassesIds = Classe::all()->pluck('id');
        }
        if(isset($devoirSchoolYears)) {
            $query = Devoir::query()->whereIn('id', $devoirSchoolYearsIds)
            ->whereIn('matiere_id', $teacherMatsIds)
            ->whereIn('classe_id', $teacherClassesIds);
        }
        // filtering :
        if(!empty($searchDevoir)) {
            $query->where('titre_devoir', 'LIKE', "%{$searchDevoir}%")
            ->orWhere('description_devoir', 'LIKE', "%{$searchDevoir}%");
        }
        if(!empty($statutFilter)) {
            $query->where('statut',$statutFilter);
        }
        if(!empty($classeFilter)) {
            $query->whereHas('classe', function ($q) use ($classeFilter) {
                $q->where('id', $classeFilter);
            });
        }
        if(!empty($matiereFilter)) {
            $query->whereHas('matiere', function ($q) use ($matiereFilter) {
                $q->where('id', $matiereFilter);
            });
        }

        //dd($query);
        $devoirs = $query->paginate(10);

        if($request->ajax()) {
            return view('partials._devoirs_table', compact('matieres','classes','devoirs','teacher','activeYear','migrateYears'));
        } else {
            return view('enseignant.devoirs', compact('matieres','classes','devoirs','teacher','activeYear','migrateYears'));
        }

    }

    /**
     * create new devoir
     */
    public function store(Request $request) {
        $request->validate([
            'titre_devoir' => 'required|min:3|max:255|unique:devoirs,titre_devoir',
            'content' => 'required',
            'classe_id' => 'required|exists:classes,id',
            'matiere_id' => 'required|exists:matieres,id',
            'active_year_id' => 'required|exists:annee_scolaires,id',
            'duree' => 'required|numeric|integer',
            'date_debut' => [
                'required',
                'date',
                function ($attribute, $value, $fail) use ($request) {
                    $year = AnneeScolaire::all()->where('statut',true)->first();
                    $startDate = new DateTime($value);
                    $yearStart = new DateTime($year->dateDeDebut);
                    $yearEnd = new DateTime($year->dateDeFin);
                    if ($startDate < $yearStart || $startDate > $yearEnd) {
                        $fail('La date de début doit être comprise entre Septembre '
                        . $yearStart->format('Y') . ' et Juillet '
                        . $yearEnd->format('Y'));
                    }
                },
            ],
            'date_fin' => [
                'required',
                'date',
                'after_or_equal:date_debut',
                function ($attribute, $value, $fail) use ($request) {
                    $year = AnneeScolaire::all()->where('statut',true)->first();
                    $endDate = new DateTime($value);
                    $yearStart = new DateTime($year->dateDeDebut);
                    $yearEnd = new DateTime($year->dateDeFin);
                    if ($endDate < $yearStart || $endDate > $yearEnd) {
                        $fail('La date de fin doit être comprise entre Septembre '
                        . $yearStart->format('Y') . ' et Juillet '
                        . $yearEnd->format('Y'));
                    }
                },
            ],
        ], [
            'duree' => 'Veuillez définir la durée du devoir',
            'date_debut.required' => 'Veuillez définir la date debut du devoir',
            'date_fin.required' => 'Veuillez définir la date de fin du devoir',
            'titre_devoir.required' => 'Veuillez entrez un titre pour le devoir',
            'titre_devoir.unique' => 'Ce titre de devoir existe déja',
            'content.required' => 'Veuillez entrez la description du devoir',
            'classe_id.required' => 'Veuillez selectionnez la classe',
            'matiere_id.required' => 'Veuillez selectionnez la matière',
            'active_year_id.required' => 'Veuillez selectionnez une année scolaire',
        ]);

        if (isset($request->date_debut) && isset($request->date_fin)) {
            if(new DateTime($request->date_fin) <= new DateTime($request->date_debut)) {
                return response()->json([
                    'error' => 'La date de fin ne doit pas être inférieur ou égale à la date de debut'
                ]);
            }
        }

        $currentDate = new DateTime();
        $state = '';
        if($currentDate >= new DateTime($request->date_debut) && $currentDate <= new DateTime($request->date_fin)) {
            $state = 'en cours';
        } elseif ($currentDate < new DateTime($request->date_debut)) {
            $state = 'programmé';
        } else {
            $state = 'terminé';
        }

        $devoir = Devoir::create([
            'titre_devoir' => $request->titre_devoir,
            'description_devoir' => $request->content,
            'classe_id' => $request->classe_id,
            'matiere_id' => $request->matiere_id,
            'user_id' => Auth::id(),
            'duree' => $request->duree,
            'annee_scolaire_id' => $request->active_year_id,
            'date_debut' => $request->date_debut,
            'date_fin' => $request->date_fin,
            'statut' => $state,
        ]);

        $devoirYear = DevoirAnneeScolaire::create([
            'devoir_id' => $devoir->id,
            'annee_scolaire_id' => $request->active_year_id
        ]);

        if($devoir && $devoirYear) {
            return response()->json(['success' => 'Devoir ajouté avec succès!']);
        } else {
            return response()->json(['error' => 'Erreur inconue lors de l\'ajout du devoir']);
        }
    }

    /**
     * editing specific devoir
     */
    public function edit($id) {
        $devoirToEdit = Devoir::findOrFail($id);
        return response()->json([
            'devoirToEdit' => $devoirToEdit,
            'content' => $devoirToEdit->description_devoir
        ]);
    }

    /**
     *  get year dates
    */
    public function getCurrentYearDates() {
        return response()->json([
            'dateDeDebutYear' => getCurrentYear()->dateDeDebut,
            'dateDeFinYear' => getCurrentYear()->dateDeFin,
        ]);
    }

    /**
     * update specific devoir
     */
    public function update(Request $request, $id) {
        $request->validate([
            'titre_devoir' => 'required|min:3|max:255',Rule::unique('devoirs')->ignore($id),
            'content' => 'required',
            'classe_id' => 'required|exists:classes,id',
            'matiere_id' => 'required|exists:matieres,id',
            'active_year_id' => 'required|exists:annee_scolaires,id',
            'duree' => 'required|numeric|integer',
            'date_debut' => [
                'required',
                'date',
                function ($attribute, $value, $fail) use ($request) {
                    $year = AnneeScolaire::all()->where('statut',true)->first();
                    $startDate = new DateTime($value);
                    $yearStart = new DateTime($year->dateDeDebut);
                    $yearEnd = new DateTime($year->dateDeFin);
                    if ($startDate < $yearStart || $startDate > $yearEnd) {
                        $fail('La date de début doit être comprise entre Septembre '
                        . $yearStart->format('Y') . ' et Juillet '
                        . $yearEnd->format('Y'));
                    }
                },
            ],
            'date_fin' => [
                'required',
                'date',
                'after_or_equal:date_debut',
                function ($attribute, $value, $fail) use ($request) {
                    $year = AnneeScolaire::all()->where('statut',true)->first();
                    $endDate = new DateTime($value);
                    $yearStart = new DateTime($year->dateDeDebut);
                    $yearEnd = new DateTime($year->dateDeFin);
                    if ($endDate < $yearStart || $endDate > $yearEnd) {
                        $fail('La date de fin doit être comprise entre Septembre '
                        . $yearStart->format('Y') . ' et Juillet '
                        . $yearEnd->format('Y'));
                    }
                },
            ],
        ], [
            'date_debut.required' => 'Veuillez définir la date debut du devoir',
            'duree' => 'Veuillez définir la durée du devoir',
            'date_fin.required' => 'Veuillez définir la date de fin du devoir',
            'titre_devoir.required' => 'Veuillez entrez un titre pour le devoir',
            'titre_devoir.unique' => 'Ce titre de devoir existe déja',
            'titre_devoir.min' => 'Le titre doit contenir minimum 3 caractères',
            'titre_devoir.max' => 'Le titre doit contenir maximum 255 cractères',
            'content.required' => 'Veuillez entrez la description du devoir',
            'classe_id.required' => 'Veuillez selectionnez la classe',
            'matiere_id.required' => 'Veuillez selectionnez la matière',
            'active_year_id.required' => 'Veuillez selectionnez une année scolaire',
        ]);

        if (isset($request->date_debut) && isset($request->date_fin)) {
            if(new DateTime($request->date_fin) <= new DateTime($request->date_debut)) {
                return response()->json([
                    'error' => 'La date de fin ne doit pas être inférieur ou égale à la date de debut'
                ]);
            }
        }

        $currentDate = new DateTime();
        $state = '';
        if($currentDate >= new DateTime($request->date_debut) && $currentDate <= new DateTime($request->date_fin)) {
            $state = 'en cours';
        } elseif ($currentDate < new DateTime($request->date_debut)) {
            $state = 'programmé';
        } else {
            $state = 'terminé';
        }

        $devoir = Devoir::findOrFail($id);
        $devoir->update([
            'date_debut' => $request->date_debut,
            'date_fin' => $request->date_fin,
            'statut' => $state,
            'duree' => $request->duree,
            'titre_devoir' => $request->titre_devoir,
            'description_devoir' => $request->content,
            'classe_id' => $request->classe_id,
            'matiere_id' => $request->matiere_id
        ]);

        return response()->json(['success' => 'Devoir mis à jour avec succès']);
    }

    /**
     * delete a specific devoir
     */
    public function destroy($id) {
        $devoir = Devoir::findOrFail($id);
        $devoirYears = DevoirAnneeScolaire::all()->where('coeffiecient_id', $devoir->id);
        foreach ($devoirYears as $devoirYear) {
            $devoirYear->delete();
        }
        $devoir->delete();
        return redirect()->route('education.devoirs')->with('deleteSuccess', 'Devoir supprimé avec succès !');
    }

    /**
     * migrate a devoir to next year
     */
    public function migrate(Request $request) {
        $request->validate([
            'migrate_year_id' => 'required|exists:annee_scolaires,id',
            'migrate_devoir_id' => 'required|exists:coefficients,id',
        ], [
            'migrate_year_id.required' => 'Aucune année selectionnée',
            'migrate_devoir_id.required' => 'Veuillez selectionnez un devoir',
        ]);

        // getting data for evaluation
        $y = AnneeScolaire::findOrFail($request->migrate_year_id);

        // checking if the devoir already migrated:
        $devoirYear = DevoirAnneeScolaire::all()
            ->where('annee_scolaire_id',$request->migrate_year_id)
            ->where('devoir_id',$request->migrate_devoir_id)
            ->first();

        if($devoirYear) {
            return response()->json([
                'error' => 'Le devoir a déjà été inclu pour l\'année : '.$y->libelleAnneeScolaire
            ]);
        } else {
            $newDevoirYear = DevoirAnneeScolaire::create([
                'devoir_id' => $request->migrate_devoir_id,
                'annee_scolaire_id' => $request->migrate_year_id,
            ]);

            if($newDevoirYear) {
                return response()->json(['success' => 'Devoir migré avec succès']);
            } else {
                return response()->json(['error' => 'Impossible de faire migrer le devoir']);
            }
        }
    }

    /**
     * treating devoir
     */
    public function start(Devoir $devoir, $number = 1) {
        // Vérification disponibilité du devoir
        if (now() > $devoir->date_fin) {
            return redirect()->back()->with('error', 'Ce devoir n\'est plus disponible');
        }
        // verification si le devoir à déjà été traité
        $results = $devoir->studentResult(Auth::id());
        $hasCompleted = $results->isNotEmpty() && $results->first()->completed_at;
        if ($hasCompleted) {
            return redirect()->route('devoirs.results', $devoir->id);
        }
        $devoir = Devoir::with('questions.reponses')->findOrFail($devoir->id);
        $currentQuestionIndex = session("devoir_{$devoir->id}_step", 0);
        $questions = $devoir->questions;
        //
        $questionNumber = $currentQuestionIndex + 1;
        $question = $questions[$currentQuestionIndex];
        $totalQuestions = count($questions);
        $progress = ($questionNumber / $totalQuestions) * 100;
        // init the result
        $result = DevoirResult::updateOrCreate([
                'user_id' => Auth::id(),
                'devoir_id' => $devoir->id,
            ], [
                'started_at' => now(),
                'score' => 0,
                'percentage' => 0,
                'total_questions' => $devoir->questions->sum('points')
            ]);
        if ($number < 1 || $number > $totalQuestions) {
            return redirect()->route('devoirs.start', [$devoir, 1]);
        }
        if (!isset($questions[$currentQuestionIndex])) {
            return redirect()->route('devoirs.results', $devoir->id);
        }
        return view('eleves.start', compact(
            'devoir' ,
            'question',
            'progress',
            'questionNumber',
            'totalQuestions'
        ));
    }

    /**
     * go back to prec. question
     */
    public function previous(Devoir $devoir) {
        $current = session("devoir_{$devoir->id}_step", 0);
        if ($current > 0) {
            session(["devoir_{$devoir->id}_step" => $current - 1]);
        }
        return redirect()->route('devoirs.start', [$devoir]);
    }

    /**
     * when answer is submited
     */
    public function answer(Request $request, Devoir $devoir) {
        $request->validate([
            'question_id' => 'required|exists:questions,id',
            'answers' => 'required|min:1|array',
            'answers.*' => 'exists:reponses,id',
        ],
        [
            'answers.required' => 'Vous devez sélectionner au moins une réponse',
            'answers.min' => 'Vous devez sélectionner au moins une réponse'
        ]);
        // saving reponses
        $question = Question::findOrFail($request->question_id);
        foreach ($request->answers ?? [] as $responseId) {
            QuestionUserReponse::updateOrCreate([
                'user_id' => Auth::id(),
                'question_id' => $question->id,
                'reponse_id' => $responseId,
            ], []);
        }
        // at the end make calculation and save
        if ($request->finish) {
            $result = DevoirResult::where('user_id', Auth::id())
                ->where('devoir_id', $devoir->id)
                ->first();
            $result->calculateScore();
            $result->update(['completed_at' => now()]);
            session()->forget("devoir_{$devoir->id}_step");
            return redirect()->route('devoirs.results', $devoir);
        }

        $current = session("devoir_{$devoir->id}_step", 0);
        session(["devoir_{$devoir->id}_step" => $current + 1]);

        return redirect()->route('devoirs.start', $devoir);
    }

    /**
     * gettting devoir result after all done
     */
    public function result($id) {
        $user = User::findOrFail(Auth::id());
        $devoir = Devoir::with('questions.reponses')->findOrFail($id);
        // controls
        $result = DevoirResult::where('user_id', $user->id)
        ->where('devoir_id', $devoir->id)
        ->firstOrFail();
        if (!$result->completed_at) {
            return redirect()->route('devoirs.start', $devoir);
        }
        session()->forget("devoir_{$id}_step");
        //
        $answersByQuestion = [];
        foreach ($devoir->questions as $question) {
            $answersByQuestion[$question->id] = [
                'question' => $question,
                'user_answers' => $question->reponses()
                    ->whereIn('id',
                        QuestionUserReponse::where('user_id', Auth::id())
                            ->where('question_id', $question->id)
                            ->pluck('reponse_id')
                    )->get(),
                'status' => $this->isQuestionCorrect($question, Auth::id()),
                'points' => $question->points
            ];
        }
        return view('eleves.devoir_result', compact(
            'devoir',
            'result',
            'answersByQuestion'
        ));
    }

    /**
     * check if a question is correct by the user choice
     */
    private function isQuestionCorrect($question, $userId) {
        $userAnswers = QuestionUserReponse::where('user_id', $userId)
            ->where('question_id', $question->id)
            ->pluck('reponse_id')
            ->toArray();
        $correctAnswers = $question->reponses()
            ->where('status', true)
            ->pluck('id')
            ->toArray();
        return empty(array_diff($correctAnswers, $userAnswers)) &&
            empty(array_diff($userAnswers, $correctAnswers));
    }

    /**
     * teacher show devoir stats
     */
    public function teacherShow(Devoir $devoir) {
        // getting all students
        $classeId = $devoir->classe_id;
        $userClasseYearIds = ClasseAnneeScolaireStudent::all()
            ->where('annee_scolaire_id', getCurrentYear()->id)
            ->where('classe_id', $classeId)->pluck('user_id');
        $students = User::where('typeUser', 'eleve')->whereIn('id', $userClasseYearIds)->get();
        $results = DevoirResult::where('devoir_id', $devoir->id)
            ->with('user')->get()->keyBy('user_id');
        // Calculate statistics
        $totalStudents = $students->count();
        $completedCount = $students->filter(fn($s) =>
            $s->devoirResults->isNotEmpty() && $s->devoirResults->first()->completed_at
        )->count();
        $completionRate = $totalStudents > 0 ? ($completedCount / $totalStudents) * 100 : 0;
        return view('enseignant.devoir', compact(
            'devoir', 'students', 'results','totalStudents',
            'completedCount','completionRate'
        ));
    }

    /**
     * showing devoir result for one student to a teacher
     */
    public function individualResult(Devoir $devoir, $id) {
        $student = User::findOrFail($id);
        $result = DevoirResult::where('devoir_id', $devoir->id)
            ->where('user_id', $student->id)
            ->firstOrFail();
        $answersByQuestion = [];
        foreach ($devoir->questions as $question) {
            $answersByQuestion[$question->id] = [
                'question' => $question,
                'user_answers' => $question->reponses()
                    ->whereIn('id',
                        QuestionUserReponse::where('user_id', $student->id)
                            ->where('question_id', $question->id)
                            ->pluck('reponse_id')
                    )->get(),
                'status' => $this->isQuestionCorrect($question, $student->id),
                'points' => $question->points
            ];
        }
        return view('enseignant.devoir_result', [
            'devoir' => $devoir,
            'student' => $student,
            'result' => $result,
            'answersByQuestion' => $answersByQuestion
        ]);
    }


    /**
     * delete a devoir in a current year
     */
    public function deleteInCurrentYear(Request $request) {
        $devoirYear = DevoirAnneeScolaire::all()->where('annee_scolaire_id',$request->delusyear_year_id)->where('devoir_id',$request->delusyear_devoir_id)->first();

        if ($devoirYear->delete()) {
            return redirect()->route('education.devoirs')->with('deleteSuccess', 'Devoir supprimé avec succès pour l\'année courrante');
        } else {
            return redirect()->route('education.devoirs')->with('errorSuccess', 'Echec de surpression du devoir pour l\'année courrante');
        }
    }
}
