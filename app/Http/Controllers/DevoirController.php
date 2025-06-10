<?php

namespace App\Http\Controllers;

use App\Models\AnneeScolaire;
use App\Models\Classe;
use App\Models\Devoir;
use App\Models\DevoirAnneeScolaire;
use App\Models\DevoirAnswer;
use App\Models\DevoirResult;
use App\Models\Matiere;
use App\Models\Question;
use App\Models\User;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class DevoirController extends Controller
{
    /**
     *
     */
    public function index(Request $request)
    {
        $devs = Devoir::all();
        foreach ($devs as $dev) {
            $endDate = new DateTime($dev->dateDeDebut);
            $startDate = new DateTime($dev->dateDeFin);
            $currentDate = new DateTime();
            if ($currentDate >= $endDate && $dev->statut !== 'terminé') {
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
        $teacher->typeUser === "enseignant" ?
            $matieres = $teacher->teacherMatieres($activeYear->id) : $matieres = Matiere::all();
        $teacher->typeUser === "enseignant" ?
            $classes = $teacher->teacherClasses($activeYear->id) : $classes = Classe::all();
        // query vars :
        $migrateYears = AnneeScolaire::all()->where('created_at', '>', $activeYear->created_at);
        $devoirSchoolYears = DevoirAnneeScolaire::all()->where('annee_scolaire_id','=', $activeYear->id);
        //dd($devoirSchoolYears);
        $devoirSchoolYearsIds = $devoirSchoolYears->pluck('devoir_id');
        // filter vars :
        $searchDevoir = $request->input('searchDevoir');
        $classeFilter = $request->input('classeFilter');
        $matiereFilter = $request->input('matiereFilter');
        $statutFilter = $request->input('statutFilter');
        // querying :
        $teacher->typeUser === "enseignant" ?
            $teacherMatsIds = $teacher->teacherMatieres($activeYear->id)->pluck('id')
            : $teacherMatsIds = Matiere::all()->pluck('id'); #filter by teacher matiere ids
        $teacher->typeUser === "enseignant" ?
            $teacherClassesIds = $teacher->teacherClasses($activeYear->id)->pluck('id')
            : $teacherClassesIds = Classe::all()->pluck('id'); #filter by teacher classe ids
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
            'date_fin.required' => 'Veuillez définir la date de fin du devoir',
            'titre_devoir.required' => 'Veuillez entrez un titre pour le devoir',
            'titre_devoir.unique' => 'Ce titre de devoir existe déja',
            'content.required' => 'Veuillez entrez la description du devoir',
            'classe_id.required' => 'Veuillez selectionnez la classe',
            'matiere_id.required' => 'Veuillez selectionnez la matière',
            'active_year_id.required' => 'Veuillez selectionnez une année scolaire',
        ]);

        if (isset($request->dateDeDebut) && isset($request->dateDeFin)) {
            if(new DateTime($request->dateDeFin) <= new DateTime($request->dateDeDebut)) {
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
            'annee_scolaire_id' => $request->active_year_id,
            'dateDeDebut' => $request->date_debut,
            'dateDeFin' => $request->date_fin,
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

        if (isset($request->dateDeDebut) && isset($request->dateDeFin)) {
            if(new DateTime($request->dateDeFin) <= new DateTime($request->dateDeDebut)) {
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
            'dateDeDebut' => $request->date_debut,
            'dateDeFin' => $request->date_fin,
            'statut' => $state,
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
    public function take(Devoir $devoir, $questionNumber = 1) {
        // Check if devoir is available
        if (!$devoir->is_published || now() > $devoir->dateDeFin) {
            return redirect()->back()->with('error', 'Ce devoir n\'est plus disponible');
        }

        // Check if student already completed
        if ($devoir->results()->where('user_id', Auth::id())->exists()) {
            return redirect()->route('devoirs.results', $devoir);
        }

        $totalQuestions = $devoir->questions()->count();
        $question = $devoir->questions()->orderBy('id')->skip($questionNumber - 1)->first();

        return view(
            'eleves.devoir', compact('devoir', 'question', 'questionNumber', 'totalQuestions')
        );
    }

    /**
     * getting students answers
     */
    public function answer(Request $request, Devoir $devoir) {
        $request->validate([
            'question_id' => 'required|exists:questions,id',
            'answers' => 'nullable|array',
            'answers.*' => 'exists:reponses,id',
        ]);

        // Get or create devoir result
        $result = DevoirResult::firstOrCreate([
            'devoir_id' => $devoir->id,
            'user_id' => Auth::id(),
        ], [
            'score' => 0,
            'total_questions' => $devoir->questions()->count(),
            'percentage' => 0,
        ]);

        // Save answer
        $question = Question::find($request->question_id);
        $correctAnswers = $question->reponses()->where('status', 1)->pluck('id')->toArray();
        $selectedAnswers = $request->answers ?? [];
        $isCorrect = empty(array_diff($correctAnswers, $selectedAnswers)) &&
                    empty(array_diff($selectedAnswers, $correctAnswers));

        DevoirAnswer::updateOrCreate([
            'result_id' => $result->id,
            'question_id' => $question->id,
        ], [
            'selected_answers' => $selectedAnswers,
            'is_correct' => $isCorrect,
            'points_earned' => $isCorrect ? $question->points : 0,
        ]);

        // Update result if finishing
        if ($request->finish) {
            $totalScore = $result->answers()->sum('points_earned');
            $percentage = ($totalScore / ($devoir->questions()->sum('points'))) * 100;

            $result->update([
                'score' => $totalScore,
                'percentage' => $percentage,
                'completed_at' => now(),
            ]);

            return redirect()->route('devoirs.results', $devoir);
        }

        // Go to next question
        $nextQuestionNumber = $devoir->questions()
            ->where('id', '>', $question->id)
            ->orderBy('id')
            ->first()
            ?->getQuestionNumber(); // You'd need to implement this method

        return redirect()->route('devoirs.take', [
            'devoir' => $devoir,
            'questionNumber' => $nextQuestionNumber ?? 1
        ]);
    }

    /**
     * showing devoir result for student
     */
    public function results(Devoir $devoir) {
        $result = $devoir->results()->where('user_id', Auth::id())->firstOrFail();
        $answers = $result->answers()->with('question')->get();

        return view('eleves.devoir_result', compact('devoir', 'result', 'answers'));
    }

    /**
     * showing individual devoir
     */
    public function teacherShow(Devoir $devoir) {
        $this->authorize('view', $devoir);

        return view('enseignant.devoir', compact('devoir'));
    }

    /**
     * showing devoir result for teacher
     */
    public function teacherResults(Devoir $devoir, DevoirResult $result) {
        $this->authorize('view', $devoir);

        $answers = $result->answers()
            ->with(['question', 'question.reponses'])
            ->get();

        return view('enseignant.devoir_result', compact('devoir', 'result', 'answers'));
    }

    /**
     * devoirs traces
     */
    public function devoirsTrace(Request $request) {
        // utils vars
        $teacher = User::find(Auth::id());
        $teacher->typeUser === "enseignant" ?
            $matieres = $teacher->teacherMatieres(getCurrentYear()->id) : $matieres = Matiere::all();
        $teacher->typeUser === "enseignant" ?
            $classes = $teacher->teacherClasses(getCurrentYear()->id) : $classes = Classe::all();
        if($request->ajax()) {
            return view('partials._controles_devoirs_table', compact('matieres', 'classes'));
        } else {
            return view('enseignant.controles_devoirs', compact('matieres', 'classes'));
        }
    }

    /**
     * students devois
     */
    public function studentsDevoirs() {
        return view('eleves.devoirs');
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
