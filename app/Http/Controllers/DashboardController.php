<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Matiere;
use App\Models\Classe;
use App\Models\Note;
use App\Models\Discipline;
use App\Models\Devoir;
use App\Models\AnneeScolaire;
use App\Models\Bulletin;
use App\Models\Club;
use App\Models\DevoirAnneeScolaire;
use App\Models\Evaluation;
use App\Models\Remplissage;
use App\Models\Trimestre;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = User::findOrFail(Auth::id());
        $currentYear = AnneeScolaire::where('statut', true)->first();
        // Données communes à tous les utilisateurs
        $data = [
            'user' => $user,
            'currentYear' => $currentYear,
            //'unreadNotifications' => $user->unreadNotifications()
        ];
        // Personnalisation par rôle
        if ($user->isAdmin()) {
            return $this->adminDashboard($data);
        } elseif ($user->isTeacher()) {
            return $this->teacherDashboard($data);
        } elseif ($user->isStudent()) {
            return $this->studentDashboard($data, $request);
        } elseif ($user->isDisciplineMaster()) {
            return $this->disciplineDashboard($data);
        }

        return view('dashboard', $data);
    }

    /**
     * admin dashboard
     */
    protected function adminDashboard($baseData)
    {
        // Statistiques globales
        $stats = [
            'students' => User::where('typeUser', 'eleve')->count(),
            'teachers' => User::where('typeUser', 'enseignant')->count(),
            'staff' => User::where('typeUser', 'personnel')->count(),
            'classes' => Classe::count(),
            'matieres' => Matiere::count(),
            'clubs' => Club::count(),
            'active_evaluations' => Evaluation::where('statut', 'en cours')->count()
        ];

        // Alertes critiques
        $alerts = [
            'exclusions' => Discipline::where('decision', 'exclusion')
                ->where('annee_scolaire_id', $baseData['currentYear']->id)
                ->count(),
            'warnings' => Discipline::where('avertissement', true)
                ->where('annee_scolaire_id', $baseData['currentYear']->id)
                ->count(),
            'lateDevoirs' => Devoir::where('date_fin', '<', now())
                ->where('statut', '!=', 'terminé')
                ->count(),
            'lateRemplissages' => Remplissage::where('date_fin', '<', now())
                ->where('statut', '!=', 'terminé')
                ->count()
        ];
        // Prepare data for charts
        $chartData = [
            'studentDistribution' => $this->getStudentDistribution(),
            'disciplineTrends' => $this->getDisciplineTrends($baseData['currentYear']),
            'evaluationStats' => $this->getEvaluationStats($baseData['currentYear'])
        ];
        return view('dashboard.admin', array_merge($baseData, [
            'stats' => $stats,
            'alerts' => $alerts,
            'chartData' => $chartData
        ]));
    }
    protected function getStudentDistribution()
    {
        return Classe::withCount('students')->get()->map(function($classe) {
            return [
                'classe' => $classe->libClasse,
                'count' => $classe->students_count
            ];
        });
    }

    protected function getDisciplineTrends($currentYear)
    {
        return [
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            'absences' => Discipline::where('annee_scolaire_id', $currentYear->id)
                ->selectRaw('MONTH(created_at) as month, SUM(heures_absence) as total')
                ->groupBy('month')
                ->pluck('total', 'month')
                ->toArray(),
            'warnings' => Discipline::where('avertissement', true)
                ->where('annee_scolaire_id', $currentYear->id)
                ->selectRaw('MONTH(created_at) as month, COUNT(*) as total')
                ->groupBy('month')
                ->pluck('total', 'month')
                ->toArray()
        ];
    }
    protected function getEvaluationStats($currentYear)
    {
        $trimestresIds = Trimestre::where('annee_scolaire_id', $currentYear->id)
            ->pluck('id');
        return Evaluation::whereIn('trimestre_id', $trimestresIds)
            ->withCount('notes')
            ->with(['trimestre'])
            ->get()
            ->groupBy('trimestre.libelleTrimestre')
            ->map(function($evaluations, $trimestre) {
                return [
                    'count' => $evaluations->count(),
                    'average' => $evaluations->avg(function($eval) {
                        return $eval->notes->avg('note');
                    })
                ];
            });
    }

    /**
     * teacher dashboard function
     */
    protected function teacherDashboard($baseData)
    {
        $teacher = $baseData['user'];
        $matieres = $teacher->teacherMatieres($baseData['currentYear']->id);
        $classes = $teacher->teacherClasses($baseData['currentYear']->id);
        // Prochains devoirs à corriger
        $devoirSchoolYearsIds = DevoirAnneeScolaire::all()
            ->where('annee_scolaire_id','=', $baseData['currentYear']->id)->pluck('devoir_id');
        $teacherMatsIds = $teacher->teacherMatieres($baseData['currentYear']->id)->pluck('id');
        $teacherClassesIds = $teacher->teacherClasses($baseData['currentYear']->id)->pluck('id');
        $devoirs = Devoir::whereIn('id', $devoirSchoolYearsIds)
            ->whereIn('matiere_id', $teacherMatsIds)
            ->whereIn('classe_id', $teacherClassesIds)
            ->where('statut', 'en cours')
            ->orderBy('date_fin', 'asc')
            ->limit(5)
            ->get();
        // Notes récentes attribuées
        $recentNotes = Note::whereHas('evaluation', function($q) use ($teacher) {
                        $q->where('user_id', $teacher->id);
                    })
                    ->with(['eleve', 'matiere'])
                    ->orderBy('created_at', 'desc')
                    ->limit(5)
                    ->get();
         // Prepare data for charts
        $chartData = [
            'matiereStats' => $this->getMatiereStats($teacher, $baseData['currentYear']),
            'classeStats' => $this->getClasseStats($teacher, $baseData['currentYear']),
            'devoirStats' => $this->getDevoirStats($teacher, $baseData['currentYear'])
        ];

        return view('dashboard.enseignant', array_merge($baseData, [
            'matieres' => $matieres,
            'classes' => $classes,
            'devoirs' => $devoirs,
            'recentNotes' => $recentNotes,
            'chartData' => $chartData
        ]));
    }
    // teacher dashboard datas
    protected function getMatiereStats($teacher, $currentYear)
    {
        return $teacher->teacherMatieres($currentYear->id)->map(function($matiere) use ($teacher, $currentYear) {
            $notes = Note::where('matiere_id', $matiere->id)
                ->where('annee_scolaire_id', $currentYear->id)
                ->get();
            return [
                'matiere' => $matiere->libelleMatiere,
                'average' => $notes->avg('note'),
                'count' => $notes->count(),
                'passed' => $notes->where('note', '>=', 10)->count()
            ];
        });
    }

    protected function getClasseStats($teacher, $currentYear)
    {
        return $teacher->teacherClasses($currentYear->id)->map(function($classe) use ($currentYear) {
            $notes = Note::where('classe_id', $classe->id)
                    ->where('annee_scolaire_id', $currentYear->id)
                    ->get();
            return [
                'classe' => $classe->libClasse,
                'average' => $notes->avg('note'),
                'studentCount' => $classe->getStudents()->count(),
                'devoirCount' => Devoir::where('classe_id', $classe->id)
                                    ->where('annee_scolaire_id', $currentYear->id)
                                    ->count()
            ];
        });
    }

    protected function getDevoirStats($teacher, $currentYear)
    {
        $devoirSchoolYearsIds = DevoirAnneeScolaire::all()
            ->where('annee_scolaire_id','=', $currentYear->id)->pluck('devoir_id');
        $teacherMatsIds = $teacher->teacherMatieres($currentYear->id)->pluck('id');
        $teacherClassesIds = $teacher->teacherClasses($currentYear->id)->pluck('id');
        return [
            'total' => Devoir::whereIn('id', $devoirSchoolYearsIds)
                ->whereIn('matiere_id', $teacherMatsIds)
                ->whereIn('classe_id', $teacherClassesIds)
                ->count(),
            'completed' => Devoir::whereIn('id', $devoirSchoolYearsIds)
                ->whereIn('matiere_id', $teacherMatsIds)
                ->whereIn('classe_id', $teacherClassesIds)
                ->where('statut', 'terminé')
                ->count(),
            'pending' => Devoir::whereIn('id', $devoirSchoolYearsIds)
                ->whereIn('matiere_id', $teacherMatsIds)
                ->whereIn('classe_id', $teacherClassesIds)
                ->where('statut', 'en cours')
                ->count()
        ];
    }

    /***
     * student dashboard function
     */
    protected function studentDashboard($baseData, $request)
    {
        $student = $baseData['user'];
        $classe = $student->studentCurrentClasse($baseData['currentYear']->id);
        $matieres = $student->studentClasseMatiere($baseData['currentYear']->id, $classe->id);
        // Dernières notes
        $latestNotes = $student->notes()
                         ->with(['matiere', 'evaluation'])
                         ->orderBy('created_at', 'desc')
                         ->limit(5)
                         ->get();
        // Prochains devoirs
        $upcomingDevoirs = Devoir::where('classe_id', $classe->id)
                            ->where('date_fin', '>', now())
                            ->with('matiere')
                            ->orderBy('date_fin', 'asc')
                            ->limit(5)
                            ->get();
        // Statistiques disciplinaires
        $disciplineStats = [
            'absences' => $student->disciplines()
                             ->where('annee_scolaire_id', $baseData['currentYear']->id)
                             ->sum('heures_absence'),
            'retards' => $student->disciplines()
                            ->where('annee_scolaire_id', $baseData['currentYear']->id)
                            ->sum('heures_retards'),
            'warnings' => $student->disciplines()
                             ->where('annee_scolaire_id', $baseData['currentYear']->id)
                             ->where('avertissement', true)
                             ->count()
        ];
        // Get bulletins with filters
        //Get bulletins data
        if($request->input('trimestre_id') || $request->input('evalution_id')) {
            $bulletins = Bulletin::where('user_id', $student->id)
                ->where('annee_scolaire_id', $baseData['currentYear']->id)
                ->when($request->trimestre_id, function($query) use ($request) {
                    return $query->where('trimestre_id', $request->trimestre_id);
                })
                ->when($request->evaluation_id, function($query) use ($request) {
                    return $query->where('evaluation_id', $request->evaluation_id);
                })
                ->with(['trimestre', 'evaluation', 'annee_scolaire'])
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            $bulletins = Bulletin::where('user_id', $student->id)
                ->where('annee_scolaire_id', $baseData['currentYear']->id)
                ->with(['trimestre', 'evaluation'])
                ->orderBy('created_at', 'desc')
                ->get();
        }
        // Prepare data for charts
        $bulletinStats = $this->prepareBulletinChartData($bulletins);
        // Get filter options
        $trimestres = Trimestre::where('annee_scolaire_id', $baseData['currentYear']->id)->get();
        $trimestresIds = Trimestre::where('annee_scolaire_id', $baseData['currentYear']->id)
            ->pluck('id');
        $evaluations = Evaluation::whereIn('trimestre_id', $trimestresIds)
            ->where('type','normal-evaluation')->get();
        return view('dashboard.eleve', array_merge($baseData, [
            'classe' => $classe,
            'matieres' => $matieres,
            'latestNotes' => $latestNotes,
            'upcomingDevoirs' => $upcomingDevoirs,
            'disciplineStats' => $disciplineStats,
            'bulletins' => $bulletins,
            'bulletinStats' => $bulletinStats,
            'trimestres' => $trimestres,
            'evaluations' => $evaluations
        ]));
    }
    // bulletins stats
    protected function prepareBulletinChartData($bulletins) {
        $data = [
            'labels' => [],
            'averages' => [],
            'classAverages' => [],
            'minAverages' => [],
            'maxAverages' => [],
            'ranks' => []
        ];
        foreach ($bulletins as $bulletin) {
            $label = ($bulletin->type_bulletin === "annuel" ? "Bulletin Annuel" :
            $bulletin->type_bulletin === "trimestre" ) ? "Bulletin Trimestriel" :
            $bulletin->evaluation->libelleEvaluation . ' - ' . $bulletin->trimestre->libelleTrimestre;
            $data['labels'][] = $label;
            $data['averages'][] = $bulletin->average;
            $data['classAverages'][] = $bulletin->general_average;
            $data['minAverages'][] = $bulletin->min_average;
            $data['maxAverages'][] = $bulletin->max_average;
            $data['ranks'][] = $bulletin->range;
        }
        return $data;
    }

    /**
     * surveillant dashboard controller
     */
    protected function disciplineDashboard($baseData)
    {
        // Récupérer les incidents disciplinaires récents
        $recentDisciplines = Discipline::with(['eleve', 'classe'])
                                 ->where('annee_scolaire_id', $baseData['currentYear']->id)
                                 ->orderBy('created_at', 'desc')
                                 ->limit(10)
                                 ->get();
        // Get students without discipline records this month
        $currentYear = $baseData['currentYear'];
        $studentsWithoutDiscipline = User::where('typeUser', 'eleve')
            ->whereDoesntHave('disciplines', function($q) use ($currentYear) {
                $q->where('annee_scolaire_id', $currentYear->id)
                ->whereMonth('created_at', now()->month);
            })
            ->count();
        // Statistiques disciplinaires
        $stats = [
            'total' => Discipline::where('annee_scolaire_id', $baseData['currentYear']->id)->count(),
            'warnings' => Discipline::where('avertissement', true)
                ->where('annee_scolaire_id', $baseData['currentYear']->id)
                ->count(),
            'exclusions' => Discipline::where('decision', 'exclusion')
                ->where('annee_scolaire_id', $baseData['currentYear']->id)
                ->count(),
            'studentsWithoutDiscipline' => $studentsWithoutDiscipline,
            'retards' => Discipline::where('annee_scolaire_id', $currentYear->id)
                ->sum('heures_retards')
        ];
        //Prepare data for charts
        $chartData = [
            'monthlyTrends' => $this->getMonthlyDisciplineTrends($currentYear),
            'byClasse' => $this->getDisciplineByClasse($currentYear),
            'byType' => [
                'Exclusions' => $stats['exclusions'],
                'Avertissements' => $stats['warnings'],
                'Retards' => $stats['retards']
            ]
        ];
        return view('dashboard.surveillant', array_merge($baseData, [
            'recentDisciplines' => $recentDisciplines,
            'stats' => $stats,
            'chartData' => $chartData,
            'currentYear' => $currentYear
        ]));
    }
    protected function getMonthlyDisciplineTrends($currentYear)
    {
        $data = Discipline::where('annee_scolaire_id', $currentYear->id)
                ->selectRaw('MONTH(created_at) as month, COUNT(*) as count')
                ->groupBy('month')
                ->orderBy('month')
                ->get();

        return [
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            'data' => $data->pluck('count', 'month')->toArray()
        ];
    }

    protected function getDisciplineByClasse($currentYear)
    {
        return Classe::withCount(['disciplines' => function($q) use ($currentYear) {
                $q->where('annee_scolaire_id', $currentYear->id);
            }])
            ->orderBy('disciplines_count', 'desc')
            ->limit(8)
            ->get()
            ->map(function($classe) {
                return [
                    'classe' => $classe->libClasse,
                    'count' => $classe->disciplines_count
                ];
            });
    }
}