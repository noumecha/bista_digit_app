<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\ActusController;
use App\Http\Controllers\AnneeScolaireController;
use App\Http\Controllers\AppConfigurationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\BoosterEvaluationController;
use App\Http\Controllers\BoosterNoteController;
use App\Http\Controllers\BullettinController;
use App\Http\Controllers\CategorieActualiteController;
use App\Http\Controllers\ClasseController;
use App\Http\Controllers\ClubConfigurationController;
use App\Http\Controllers\ClubController;
use App\Http\Controllers\CoefficientController;
use App\Http\Controllers\ConseilDisciplineController;
use App\Http\Controllers\DevoirController;
use App\Http\Controllers\DisciplineController;
use App\Http\Controllers\EleveController;
use App\Http\Controllers\EnseignantController;
use App\Http\Controllers\EnseignantMatiereModelController;
use App\Http\Controllers\EnseignantPrincipalController;
use App\Http\Controllers\EnseignementController;
use App\Http\Controllers\EpreuveController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\FonctionController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\IamLeaderController;
use App\Http\Controllers\MatiereController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\PersonnelController;
use App\Http\Controllers\ProgrammeController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\RemplissageController;
use App\Http\Controllers\ReponseController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\SliderController;
use App\Http\Controllers\SpecialiteController;
use App\Http\Controllers\TrimestreController;
use App\Http\Controllers\TypeEpreuveController;
use App\Models\Programme;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\HttpKernel\Profiler\ProfilerStorageInterface;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Actualites CRUD Routes :
Route::get('/actualites/create', [ActusController::class, 'index'])->name('actualites.index')->middleware('auth');
Route::post('/actualites/save', [ActusController::class, 'store'])->name('actualite.store')->middleware('auth');
Route::put('/actualites/update/{id}', [ActusController::class, 'update'])->name('actualite.update')->middleware('auth');
Route::get('/actualites/{id}/edit', [ActusController::class, 'edit'])->name('actualite.edit')->middleware('auth');
Route::delete('/actualites/{id}', [ActusController::class, 'destroy'])->name('actualite.destroy')->middleware('auth');

// primary routes for the front-website :
Route::get('/acceuil', [HomeController::class, 'index'])->name('home.index');
Route::get('/programmes', [HomeController::class, 'programmes'])->name('home.programmes');
Route::get('/clubs', [HomeController::class, 'clubs'])->name('home.clubs');
Route::get('/epreuves', [HomeController::class, 'epreuves'])->name('home.epreuves');
Route::get('/', [HomeController::class, 'index'])->name('home.index');
Route::get('/apropos', [AboutController::class, 'index'])->name('home.about');
Route::get('/actualites', [HomeController::class, 'actualites'])->name('home.actus');
Route::get('/actualites/categorie/{category}', [HomeController::class, 'showCategorie'])->name('home.showCategorie');
Route::get('/atouts/atout/{atout}', [HomeController::class, 'showAtout'])->name('home.showAtout');
Route::get('/actualite/{id}/read', [HomeController::class, 'showActualite'])->name('actualites.show');
Route::get('/epreuve/{id}/read', [HomeController::class, 'showEpreuve'])->name('home.showepreuve');

// Categories actualites CRUD Routes :
Route::get('/categories/actualites', [CategorieActualiteController::class, 'index'])->name('actualites.categories')->middleware('auth');
Route::post('/categories/actualites/save', [CategorieActualiteController::class, 'store'])->name('categorie.store')->middleware('auth');
Route::put('/categories/actualites/update/{id}', [CategorieActualiteController::class, 'update'])->name('categorie.update')->middleware('auth');
Route::get('/categories/actualites/{id}/edit', [CategorieActualiteController::class, 'edit'])->name('categorie.edit')->middleware('auth');
Route::delete('/categories/actualites/{id}', [CategorieActualiteController::class, 'destroy'])->name('categorie.destroy')->middleware('auth');

// Authentication routes :
Route::get('/sign-up', [RegisterController::class, 'create'])->middleware('guest')->name('sign-up');
Route::post('/sign-up', [RegisterController::class, 'store'])->middleware('guest');
Route::get('/sign-in', [LoginController::class, 'create'])->middleware('guest')->name('sign-in');
Route::post('/sign-in', [LoginController::class, 'store'])->middleware('guest');
Route::post('/logout', [LoginController::class, 'destroy'])->middleware('auth')->name('logout');
Route::get('/forgot-password', [ForgotPasswordController::class, 'create'])->middleware('guest')->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'store'])->middleware('guest')->name('password.email');
Route::get('/reset-password/{token}', [ResetPasswordController::class, 'create'])->middleware('guest')->name('password.reset');
Route::post('/reset-password', [ResetPasswordController::class, 'store'])->middleware('guest');
Route::get('/signin', function () {
    return view('account-pages.signin');
})->name('signin');
Route::get('/signup', function () {
    return view('account-pages.signup');
})->name('signup')->middleware('guest');

// routes for the dashboard
#Route::middleware(['auth','admin'])->group(function() {
    Route::get('/admin', function () {
        return redirect('/dashboard');
    })->middleware('auth');

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard')->middleware('auth');

    Route::get('/tables', function () {
        return view('tables');
    })->name('tables')->middleware('auth');

    Route::get('/acceuil', function () {
        return view('acceuil');
    })->name('acceuil')->middleware('auth');

    Route::get('/wallet', function () {
        return view('wallet');
    })->name('wallet')->middleware('auth');

    Route::get('/RTL', function () {
        return view('RTL');
    })->name('RTL')->middleware('auth');

    Route::get('/profile', function () {
        return view('account-pages.profile');
    })->name('profile')->middleware('auth');

#});

# education routes
Route::get('/education/epreuves', [EpreuveController::class, 'index'])->name('education.epreuves')->middleware('auth');
Route::get('/education/type_epreuves', [TypeEpreuveController::class, 'index'])->name('education.type_epreuves')->middleware('auth');
Route::get('/education/discipline', [DisciplineController::class, 'index'])->name('education.discipline')->middleware('auth');
Route::get('/education/conseildiscipline', [ConseilDisciplineController::class, 'index'])->name('education.conseildiscipline')->middleware('auth');
Route::get('/education/matieres', [MatiereController::class, 'index'])->name('education.matiere')->middleware('auth');
Route::get('/education/classes', [ClasseController::class, 'index'])->name('education.classes')->middleware('auth');
Route::get('/education/sections', [SectionController::class, 'index'])->name('education.sections')->middleware('auth');
Route::get('/education/coefficients', [CoefficientController::class, 'index'])->name('education.coefficients')->middleware('auth');
Route::get('/education/devoirs', [DevoirController::class, 'index'])->name('education.devoirs')->middleware('auth');
Route::get('/education/questions', [QuestionController::class, 'index'])->name('education.questions')->middleware('auth');
Route::get('/education/reponses', [ReponseController::class, 'index'])->name('education.reponses')->middleware('auth');
Route::get('/education/discipline', [DisciplineController::class, 'index'])->name('education.discipline')->middleware('auth');
Route::get('/education/enseignement', [EnseignementController::class, 'index'])->name('education.enseignement')->middleware('auth');
Route::get('/education/enseignantMatiere', [EnseignantMatiereModelController::class, 'index'])->name('education.enseignantMatiere')->middleware('auth');

## education - epreuves routes
Route::post('/education/epreuves/save', [EpreuveController::class, 'store'])->name('epreuves.store')->middleware('auth');
Route::get('/education/epreuves/{id}/edit', [EpreuveController::class, 'edit'])->name('epreuves.edit')->middleware('auth');
Route::put('/education/epreuves/update/{id}', [EpreuveController::class, 'update'])->name('epreuves.update')->middleware('auth');
Route::delete('/education/epreuves/{id}', [EpreuveController::class, 'destroy'])->name('epreuves.destroy')->middleware('auth');

## education - typeepreuves routes
Route::get('/education/epreuves/typeepreuves', [TypeEpreuveController::class, 'index'])->name('epreuves.typeepreuves')->middleware('auth');
Route::post('/education/epreuves/typeepreuves/save', [TypeEpreuveController::class, 'store'])->name('epreuves.typeepreuves.store')->middleware('auth');
Route::get('/education/epreuves/typeepreuves/{id}/edit', [TypeEpreuveController::class, 'edit'])->name('epreuves.typeepreuves.edit')->middleware('auth');
Route::put('/education/epreuves/typeepreuves/update/{id}', [TypeEpreuveController::class, 'update'])->name('epreuves.typeepreuves.update')->middleware('auth');
Route::delete('/education/epreuves/typeepreuves/{id}', [TypeEpreuveController::class, 'destroy'])->name('epreuves.typeepreuves.destroy')->middleware('auth');

## education -> type epreuves CRUD routes
Route::post('/typeEpreuve/save', [TypeEpreuveController::class, 'store'])->name('typeEpreuve.store')->middleware('auth');
Route::put('/typeEpreuve/{id}', [TypeEpreuveController::class, 'update'])->name('typeEpreuve.update')->middleware('auth');
Route::get('/typeEpreuve/{id}/edit', [TypeEpreuveController::class, 'edit'])->name('typeEpreuve.edit')->middleware('auth');
Route::delete('/typeEpreuve/{id}', [TypeEpreuveController::class, 'destroy'])->name('typeEpreuve.destroy')->middleware('auth');

## education -> epreuves CRUD routes
Route::post('/epreuve/save', [EpreuveController::class, 'store'])->name('epreuve.store')->middleware('auth');
Route::put('/epreuve/{id}', [EpreuveController::class, 'update'])->name('epreuve.update')->middleware('auth');
Route::get('/epreuve/{id}/edit', [EpreuveController::class, 'edit'])->name('epreuve.edit')->middleware('auth');
Route::delete('/epreuve/{id}', [EpreuveController::class, 'destroy'])->name('epreuve.destroy')->middleware('auth');

## education -> attribution_matieres routes
Route::post('/education/enseignantMatiere/save', [EnseignantMatiereModelController::class, 'store'])->name('enseignantMatiere.store')->middleware('auth');
Route::put('/education/enseignantMatiere/update/{id}', [EnseignantMatiereModelController::class, 'update'])->name('enseignantMatiere.update')->middleware('auth');
Route::get('/education/enseignantMatiere/{id}/edit/{yearId}', [EnseignantMatiereModelController::class, 'edit'])->name('enseignantMatiere.edit')->middleware('auth');
Route::delete('/education/enseignantMatiere/{id}', [EnseignantMatiereModelController::class, 'destroy'])->name('enseignantMatiere.destroy')->middleware('auth');
Route::post('/education/enseignantMatiere/delete-ensmat-in-year', [EnseignantMatiereModelController::class, 'deleteEnsMatCurrentYear'])->name('enseignantMatiere.deleteEnsMatCurrentYear')->middleware('auth');

## education -> subjects routes
Route::post('/education/matieres/save', [MatiereController::class, 'store'])->name('matiere.store')->middleware('auth');
Route::put('/education/matieres/update/{id}', [MatiereController::class, 'update'])->name('matiere.update')->middleware('auth');
Route::get('/education/matieres/{id}/edit', [MatiereController::class, 'edit'])->name('matiere.edit')->middleware('auth');
Route::delete('/education/matieres/{id}', [MatiereController::class, 'destroy'])->name('matiere.destroy')->middleware('auth');

## education -> sections routes
Route::post('/education/section/save', [SectionController::class, 'store'])->name('section.store')->middleware('auth');
Route::put('/education/section/update/{id}', [SectionController::class, 'update'])->name('section.update')->middleware('auth');
Route::get('/education/section/{id}/edit', [SectionController::class, 'edit'])->name('section.edit')->middleware('auth');
Route::delete('/education/section/{id}', [SectionController::class, 'destroy'])->name('section.destroy')->middleware('auth');

## education -> classes routes
Route::post('/education/classe/save', [ClasseController::class, 'store'])->name('classe.store')->middleware('auth');
Route::put('/education/classe/update/{id}', [ClasseController::class, 'update'])->name('classe.update')->middleware('auth');
Route::get('/education/classe/{id}/edit', [ClasseController::class, 'edit'])->name('classe.edit')->middleware('auth');
Route::delete('/education/classe/{id}', [ClasseController::class, 'destroy'])->name('classe.destroy')->middleware('auth');

## education -> coefficient routes
Route::post('/education/coefficient/save', [CoefficientController::class, 'store'])->name('coefficient.store')->middleware('auth');
Route::put('/education/coefficient/update/{id}', [CoefficientController::class, 'update'])->name('coefficient.update')->middleware('auth');
Route::get('/education/coefficient/{id}/edit/{yearId}', [CoefficientController::class, 'edit'])->name('coefficient.edit')->middleware('auth');
Route::delete('/education/coefficient/{id}', [CoefficientController::class, 'destroy'])->name('coefficient.destroy')->middleware('auth');
Route::post('/education/coefficient/migrate', [CoefficientController::class, 'migrate'])->name('coefficient.migrate')->middleware('auth');
Route::post('/education/coefficient/delete-coef-in-year', [CoefficientController::class, 'deleteCoefCurrentYear'])->name('coefficient.deleteCoefCurrentYear')->middleware('auth');

## education -> discipline routes
Route::post('/education/discipline/save', [DisciplineController::class, 'store'])->name('discipline.store')->middleware('auth');
Route::put('/education/discipline/update/{id}', [DisciplineController::class, 'update'])->name('discipline.update')->middleware('auth');
Route::get('/education/discipline/{id}/edit', [DisciplineController::class, 'edit'])->name('discipline.edit')->middleware('auth');
Route::delete('/education/discipline/{id}', [DisciplineController::class, 'destroy'])->name('discipline.destroy')->middleware('auth');
Route::get('/education/discipline/students/{classe_id}', [DisciplineController::class, 'getStudents'])->middleware('auth');
Route::get('/education/discipline/student/{user_id}', [DisciplineController::class, 'getStudent'])->middleware('auth');
Route::get('/education/discipline/month/{evaluation_id}', [DisciplineController::class, 'getMonths'])->middleware('auth');

## educations -> conseilsdisciplines routes
Route::post('/education/conseildiscipline/save', [ConseilDisciplineController::class, 'store'])->name('conseildiscipline.store')->middleware('auth');
Route::put('/education/conseildiscipline/update/{id}', [ConseilDisciplineController::class, 'update'])->name('conseildiscipline.update')->middleware('auth');
Route::get('/education/conseildiscipline/{id}/edit', [ConseilDisciplineController::class, 'edit'])->name('conseildiscipline.edit')->middleware('auth');
Route::delete('/education/conseildiscipline/{id}', [ConseilDisciplineController::class, 'destroy'])->name('conseildiscipline.destroy')->middleware('auth');
Route::get('/education/conseildiscipline/students/{classe_id}', [ConseilDisciplineController::class, 'getStudents'])->middleware('auth');
Route::get('/education/conseildiscipline/student/{user_id}', [ConseilDisciplineController::class, 'getStudent'])->middleware('auth');
Route::get('/education/conseildisciplines/conseildate/{evalId}', [ConseilDisciplineController::class, 'getTrimsDate'])->middleware('auth');

## education -> devoirs routes
Route::post('/education/devoirs/save', [DevoirController::class, 'store'])->name('devoir.store')->middleware('auth');
Route::put('/education/devoirs/update/{id}', [DevoirController::class, 'update'])->name('devoir.update')->middleware('auth');
Route::get('/education/devoirs/{id}/edit/{yearId}', [DevoirController::class, 'edit'])->name('devoir.edit')->middleware('auth');
Route::delete('/education/devoirs/{id}', [DevoirController::class, 'destroy'])->name('devoir.destroy')->middleware('auth');
Route::post('/education/devoirs/migrate', [DevoirController::class, 'migrate'])->name('devoir.migrate')->middleware('auth');
Route::post('/education/devoirs/delete-in-year', [DevoirController::class, 'deleteInCurrentYear'])->name('devoir.deleteInCurrentYear')->middleware('auth');

## education -> questions routes
Route::post('/education/questions/save', [QuestionController::class, 'store'])->name('question.store')->middleware('auth');
Route::put('/education/questions/update/{id}', [QuestionController::class, 'update'])->name('question.update')->middleware('auth');
Route::get('/education/questions/{id}/edit', [QuestionController::class, 'edit'])->name('question.edit')->middleware('auth');
Route::delete('/education/questions/{id}', [QuestionController::class, 'destroy'])->name('question.destroy')->middleware('auth');
Route::post('/education/questions/migrate', [QuestionController::class, 'migrate'])->name('question.migrate')->middleware('auth');
Route::post('/education/questions/delete-in-year', [QuestionController::class, 'deleteInCurrentYear'])->name('question.deleteInCurrentYear')->middleware('auth');

## education -> reponses routes
Route::post('/education/reponses/save', [ReponseController::class, 'store'])->name('reponse.store')->middleware('auth');
Route::put('/education/reponses/update/{id}', [ReponseController::class, 'update'])->name('reponse.update')->middleware('auth');
Route::get('/education/reponses/{id}/edit/{yearId}', [ReponseController::class, 'edit'])->name('reponse.edit')->middleware('auth');
Route::delete('/education/reponses/{id}', [ReponseController::class, 'destroy'])->name('reponse.destroy')->middleware('auth');
Route::post('/education/reponses/migrate', [ReponseController::class, 'migrate'])->name('reponse.migrate')->middleware('auth');
Route::post('/education/reponses/delete-in-year', [ReponseController::class, 'deleteInCurrentYear'])->name('reponse.deleteInCurrentYear')->middleware('auth');

## education -> enseignement routes
Route::post('education/enseignement/save', [EnseignementController::class, 'store'])->name('enseignement.store')->middleware('auth');
Route::put('education/enseignement/update/{edit}', [EnseignementController::class, 'update'])->name('enseignement.update')->middleware('auth');
Route::get('education/enseignement/{id}/edit/{yearId}', [EnseignementController::class, 'edit'])->name('enseignement.edit')->middleware('auth');
Route::delete('education/enseignement/{id}', [EnseignementController::class, 'destroy'])->name('enseignement.destroy')->middleware('auth');
Route::post('education/enseignement/migrate', [EnseignementController::class, 'migrate'])->name('enseignement.migrate')->middleware('auth');
Route::post('education/enseignement/delete-ens-in-year', [EnseignementController::class, 'deleteEnsCurrentYear'])->name('enseignement.deleteEnsCurrentYear')->middleware('auth');

## education -> enseignement principale routes
Route::get('/education/enseignantprincipal', [EnseignantPrincipalController::class, 'index'])->name('education.enseignantprincipal')->middleware('auth');
Route::post('education/enseignantprincipal/save', [EnseignantPrincipalController::class, 'store'])->name('enseignantprincipal.store')->middleware('auth');
Route::put('education/enseignantprincipal/update/{edit}', [EnseignantPrincipalController::class, 'update'])->name('enseignantprincipal.update')->middleware('auth');
Route::get('education/enseignantprincipal/{id}/edit/{yearId}', [EnseignantPrincipalController::class, 'edit'])->name('enseignantprincipal.edit')->middleware('auth');
Route::delete('education/enseignantprincipal/{id}', [EnseignantPrincipalController::class, 'destroy'])->name('enseignantprincipal.destroy')->middleware('auth');
Route::post('education/enseignantprincipal/migrate', [EnseignantPrincipalController::class, 'migrate'])->name('enseignantprincipal.migrate')->middleware('auth');
Route::post('education/enseignantprincipal/delete-ensprinc-in-year', [EnseignantPrincipalController::class, 'deleteEnsPrincCurrentYear'])->name('enseignantprincipal.deleteEnsPrincCurrentYear')->middleware('auth');
Route::get('/enseignantprincipals/teachers/{classeId}', [EnseignantPrincipalController::class, 'getTeachers'])->name('enseignantprincipals.teachers')->middleware('auth');

# evaluation - trimestres routes
Route::get('/evaluation/trimestres', [TrimestreController::class, 'index'])->name('evaluation.trimestres')->middleware('auth');
Route::post('/evaluation/trimestres/save', [TrimestreController::class, 'store'])->name('trimestre.store')->middleware('auth');
Route::put('/evaluation/trimestres/update/{id}', [TrimestreController::class, 'update'])->name('trimestre.update')->middleware('auth');
Route::get('/evaluation/trimestres/{id}/edit', [TrimestreController::class, 'edit'])->name('trimestre.edit')->middleware('auth');
Route::delete('/evaluation/trimestres/{id}', [TrimestreController::class, 'destroy'])->name('trimestre.destroy')->middleware('auth');
Route::get('/evaluation/trimestres/years/{yearId}', [TrimestreController::class, 'getYearsDate'])->middleware('auth');

# evaluation - notes routes
Route::get('/notes', function () {
    return redirect('/evaluation/notes');
})->middleware('auth');
Route::get('/evaluation/notes', [NoteController::class, 'index'])->name('evaluation.notes')->middleware('auth');
Route::get('/evaluation/notes/controles', [NoteController::class, 'remplissageTrace'])->name('evaluation.notes_controles')->middleware('auth');
Route::get('/evaluation/notes/modifications', [NoteController::class, 'noteHistories'])->name('evaluation.notes_modifications')->middleware('auth');
Route::post('/evaluation/note/save', [NoteController::class, 'store'])->name('evaluation.noteStore')->middleware('auth');
Route::put('/evaluation/note/update/{id}', [NoteController::class, 'update'])->name('evaluation.noteUpdate')->middleware('auth');
Route::delete('/evaluation/notes/delete/{id}', [NoteController::class, 'destroy'])->name('evaluation.notesDestroy')->middleware('auth');
Route::get('/evaluation/notes/matieres/{classId}', [NoteController::class, 'getMatieres'])->middleware('auth');

# evaluation - bulleting routes

Route::get('/bulletins', function () {
    return redirect('/bulletins/list');
})->middleware('auth');
Route::get('/bulletins/list', [BullettinController::class, 'index'])->name('bulletins.list')->middleware('auth');
Route::get('/bulletins/configuration', [BullettinController::class, 'configs'])->name('bulletins.configuration')->middleware('auth');
Route::get('/bulletins/students/{classeId}', [BullettinController::class, 'getStudents'])->name('bulletins.students')->middleware('auth');
Route::get('/bulletins/evaluations/{trimestreId}', [BullettinController::class, 'getEvaluations'])->name('bulletins.evaluations')->middleware('auth');
Route::put('/bulletins/update/{id}', [BullettinController::class, 'update'])->name('bulletins.update')->middleware('auth');
Route::post('/bulletins/save', [BullettinController::class, 'generate'])->name('bulletins.generate')->middleware('auth');
Route::delete('/bulletins/{id}/delete', [BullettinController::class, 'destroy'])->name('bulletins.destroy')->middleware('auth');
Route::get('/bulletins/preview/{id}', [BullettinController::class, 'preview'])->name('bulletins.preview');

# configuration routes - app configuration
Route::get('/configurations', function () {
    return redirect('/configurations/app_configuration');
})->middleware('auth');
Route::get('/configurations/app_configuration', [AppConfigurationController::class, 'index'])->name('app_configuration.index')->middleware('auth');
Route::get('/configurations/app_configuration/{id}/edit', [AppConfigurationController::class, 'edit'])->name('app_configuration.edit')->middleware('auth');
Route::post('/configurations/app_configuration/{action}', [AppConfigurationController::class, 'update'])->name('app_configuration.update')->middleware('auth');

# configuration routes - slider configuration
Route::get('/configurations/sliders/list', [SliderController::class, 'index'])->name('sliders.index')->middleware('auth');
Route::post('/configurations/sliders/save', [SliderController::class, 'store'])->name('sliders.store')->middleware('auth');
Route::put('/configurations/sliders/update/{id}', [SliderController::class, 'update'])->name('sliders.update')->middleware('auth');
Route::get('/configurations/sliders/{id}/edit', [SliderController::class, 'edit'])->name('sliders.edit')->middleware('auth');
Route::delete('/configurations/sliders/{id}', [SliderController::class, 'destroy'])->name('sliders.destroy')->middleware('auth');

# configuration routes - club configuration
Route::get('/configurations/club_configuration', [ClubConfigurationController::class, 'index'])->name('club_configuration.index')->middleware('auth');
Route::get('/configurations/club_configuration/{id}/edit', [ClubConfigurationController::class, 'edit'])->name('club_configuration.edit')->middleware('auth');
Route::post('/configurations/club_configuration/{action}', [ClubConfigurationController::class, 'update'])->name('club_configuration.update')->middleware('auth');

# configurations routes -  profil configuration
Route::get('/configurations/profile', function () {
    return redirect('/configurations/profile');
})->middleware('auth');
Route::get('/configurations/profile', [ProfileController::class, 'index'])->name('profile.index')->middleware('auth');
Route::get('/configurations/profile/{id}/edit', [ProfileController::class, 'edit'])->name('profile.edit')->middleware('auth');
Route::post('/configurations/profile/{action}', [ProfileController::class, 'update'])->name('profile.update')->middleware('auth');
Route::post('/configurations/profile/password/update', [ProfileController::class, 'updatePassword'])->name('profile.password')->middleware('auth');

# programme configuration - routes leader
Route::get('/configurations/page_configuration/leader', [IamLeaderController::class, 'index'])->name('page_configuration.leader.index')->middleware('auth');
Route::get('/configurations/page_configuration/leader/{id}/edit', [IamLeaderController::class, 'edit'])->name('page_configuration.leader.edit')->middleware('auth');
Route::post('/configurations/page_configuration/leader/{action}', [IamLeaderController::class, 'update'])->name('page_configuration.leader.update')->middleware('auth');

# programme configuration - booster page config
Route::get('/configurations/page_configuration/booster', [ProgrammeController::class, 'index'])->name('page_configuration.booster.index')->middleware('auth');
Route::get('/configurations/page_configuration/booster/{id}/edit', [ProgrammeController::class, 'edit'])->name('page_configuration.booster.edit')->middleware('auth');
Route::post('/configurations/page_configuration/booster/{action}', [ProgrammeController::class, 'update'])->name('page_configuration.booster.update')->middleware('auth');
# programme routes - booster teachers routes
Route::get('/programme/booster/teachers', [ProgrammeController::class, 'teachers'])->name('booster.teachers')->middleware('auth');
Route::post('/programme/booster/teachers/save', [ProgrammeController::class, 'teacherSave'])->name('booster.teacherSave')->middleware('auth');
Route::get('/programme/booster/teachers/{id}/edit', [ProgrammeController::class, 'teacherEdit'])->name('booster.teachersEdit')->middleware('auth');
Route::delete('/programme/booster/teachers/{id}/delete', [ProgrammeController::class, 'teacherDelete'])->name('booster.teachersDelete')->middleware('auth');
Route::get('/booster/teachers/matieres/{userId}', [ProgrammeController::class, 'getTeacherMatieres'])->name('booster.teacherMatieres')->middleware('auth');
# programme routes - booster evaluations routes
Route::get('/programme/booster/evaluations', [BoosterEvaluationController::class, 'evaluations'])->name('booster.evaluations')->middleware('auth');
Route::post('/programme/booster/evaluations/save', [BoosterEvaluationController::class, 'evaluationSave'])->name('booster.evaluationSave')->middleware('auth');
Route::get('/programme/booster/evaluations/{id}/edit', [BoosterEvaluationController::class, 'evaluationEdit'])->name('booster.evaluationEdit')->middleware('auth');
Route::delete('/programme/booster/evaluations/{id}/delete', [BoosterEvaluationController::class, 'evaluationDelete'])->name('booster.evaluationDelete')->middleware('auth');
Route::put('/programme/booster/evaluations/update/{id}', [BoosterEvaluationController::class, 'evaluationUpdate'])->name('booster.evaluationUpdate')->middleware('auth');
# programme routes - booster matieres routes
Route::get('/programme/booster/matieres', [ProgrammeController::class, 'matieres'])->name('booster.matieres')->middleware('auth');
Route::post('/programme/booster/matieres/save', [ProgrammeController::class, 'matiereSave'])->name('booster.matiereSave')->middleware('auth');
Route::get('/programme/booster/matieres/{id}/edit', [ProgrammeController::class, 'matiereEdit'])->name('booster.matiereEdit')->middleware('auth');
Route::delete('/programme/booster/matieres/{id}/delete', [ProgrammeController::class, 'matiereDelete'])->name('booster.matiereDelete')->middleware('auth');
# programme routes - booster students routes
Route::get('/programme/booster/students', [ProgrammeController::class, 'students'])->name('booster.students')->middleware('auth');
Route::post('/programme/booster/students/save', [ProgrammeController::class, 'studentSave'])->name('booster.studentSave')->middleware('auth');
Route::get('/programme/booster/students/{id}/edit', [ProgrammeController::class, 'studentEdit'])->name('booster.studentEdit')->middleware('auth');
Route::delete('/programme/booster/students/{id}/delete', [ProgrammeController::class, 'studentDelete'])->name('booster.studentDelete')->middleware('auth');
Route::get('/programme/booster/classe/students/{classId}', [ProgrammeController::class, 'getClasseStudents'])->middleware('auth');
# configuration routes - booster classes routes
Route::get('/programme/booster/classes', [ProgrammeController::class, 'classes'])->name('booster.classes')->middleware('auth');
Route::post('/programme/booster/classes/save', [ProgrammeController::class, 'classeSave'])->name('booster.classeSave')->middleware('auth');
Route::get('/programme/booster/classes/{id}/edit', [ProgrammeController::class, 'classeEdit'])->name('booster.classeEdit')->middleware('auth');
Route::delete('/programme/booster/classes/{id}/delete', [ProgrammeController::class, 'classeDelete'])->name('booster.classeDelete')->middleware('auth');
# configuration routes - booster notes routes
Route::get('/programme/booster/notes', [BoosterNoteController::class, 'index'])->name('booster.notes')->middleware('auth');
Route::get('/programme/booster/notes/controles', [BoosterNoteController::class, 'boosterRemplissageTrace'])->name('booster.notes_controles')->middleware('auth');
Route::get('/programme/booster/notes/modifications', [BoosterNoteController::class, 'boosterNoteHistories'])->name('booster.notes_modifications')->middleware('auth');
Route::post('/programme/booster/note/save', [BoosterNoteController::class, 'store'])->name('booster.noteStore')->middleware('auth');
Route::put('/programme/booster/note/update/{id}', [BoosterNoteController::class, 'update'])->name('booster.noteUpdate')->middleware('auth');
Route::delete('/programme/booster/notes/delete/{id}', [BoosterNoteController::class, 'destroy'])->name('booster.notesDestroy')->middleware('auth');
Route::get('/programme/booster/notes/matieres/{classId}', [BoosterNoteController::class, 'getBoosterMatieres'])->middleware('auth');

# configuration routes - specialite routes
Route::get('/configurations/specialite', function () {
    return redirect('/configurations/specialite/list');
})->middleware('auth');
Route::get('/configurations/specialite/list', [SpecialiteController::class, 'index'])->name('specialite.index')->middleware('auth');
Route::post('/configurations/specialite/save', [SpecialiteController::class, 'store'])->name('specialite.store')->middleware('auth');
Route::put('/configurations/specialite/update/{id}', [SpecialiteController::class, 'update'])->name('specialite.update')->middleware('auth');
Route::get('/configurations/specialite/{id}/edit', [SpecialiteController::class, 'edit'])->name('specialite.edit')->middleware('auth');
Route::delete('/configurations/specialite/{id}', [SpecialiteController::class, 'destroy'])->name('specialite.destroy')->middleware('auth');

# configuratioun routes - clubs routes
Route::get('/configurations/clubs', function () {
    return redirect('/configurations/clubs/list');
})->middleware('auth');
Route::get('/configurations/clubs/list', [ClubController::class, 'index'])->name('clubs.index')->middleware('auth');
Route::post('/configurations/clubs/save', [ClubController::class, 'store'])->name('clubs.store')->middleware('auth');
Route::put('/configurations/clubs/update/{id}', [ClubController::class, 'update'])->name('clubs.update')->middleware('auth');
Route::get('/configurations/clubs/{id}/edit', [ClubController::class, 'edit'])->name('clubs.edit')->middleware('auth');
Route::delete('/configurations/clubs/{id}', [ClubController::class, 'destroy'])->name('clubs.destroy')->middleware('auth');

#evaluation - evaluations routes
Route::get('/evaluation/evaluations', [EvaluationController::class, 'index'])->name('evaluation.evaluations')->middleware('auth');
Route::post('/evaluation/evaluations/save', [EvaluationController::class, 'store'])->name('evaluation.store')->middleware('auth');
Route::get('/evaluation/evaluations/{id}/edit', [EvaluationController::class, 'edit'])->name('evaluation.edit')->middleware('auth');
Route::put('/evaluation/evaluations/update/{id}', [EvaluationController::class, 'update'])->name('evaluation.update')->middleware('auth');
Route::delete('/evaluation/evaluations/{id}', [EvaluationController::class, 'destroy'])->name('evaluation.destroy')->middleware('auth');
Route::get('/evaluation/evaluations/trimsdate/{trimId}', [EvaluationController::class, 'getTrimsDate'])->middleware('auth');

# evaluations - remplissage :
Route::get('/remplissages', function () {
    return redirect('/evaluation/remplissages');
})->middleware('auth');
Route::get('/evaluation/remplissages', [RemplissageController::class, 'index'])->name('evaluation.remplissages')->middleware('auth');
Route::post('/evaluation/remplissages/save', [RemplissageController::class, 'store'])->name('evaluation.remplissagesStore')->middleware('auth');
Route::get('/evaluation/remplissages/{id}/edit', [RemplissageController::class, 'edit'])->name('evaluation.remplissagesEdit')->middleware('auth');
Route::put('/evaluation/remplissages/update/{id}', [RemplissageController::class, 'update'])->name('evaluation.remplissagesUpdate')->middleware('auth');
Route::delete('/evaluation/remplissages/{id}', [RemplissageController::class, 'destroy'])->name('evaluation.remplissagesDestroy')->middleware('auth');
Route::get('/evaluation/remplissages/evalsdate/{evalId}', [RemplissageController::class, 'getEvalsDate'])->middleware('auth');

# users routes
Route::get('/utilisateur/teachers', [EnseignantController::class, 'index'])->name('utilisateur.teachers')->middleware('auth');
Route::get('/utilisateur/students', [EleveController::class, 'index'])->name('utilisateur.students')->middleware('auth');
Route::get('/utilisateur/personnels', [PersonnelController::class, 'index'])->name('utilisateur.personnels')->middleware('auth');

## users -> teacher routes
Route::post('/utilisateur/teacher/save', [EnseignantController::class, 'store'])->name('teacher.store')->middleware('auth');
Route::put('/utilisateur/teacher/update/{id}', [EnseignantController::class, 'update'])->name('teacher.update')->middleware('auth');
Route::delete('/utilisateur/teacher/delete/{id}', [EnseignantController::class, 'destroy'])->name('teacher.destroy')->middleware('auth');
Route::get('/utilisateur/teacher/{id}/edit/{yearId}', [EnseignantController::class, 'edit'])->name('teacher.edit')->middleware('auth');
Route::post('/utilisateur/teacher/migrate', [EnseignantController::class, 'migrate'])->name('teacher.teacherMigrate')->middleware('auth');
Route::post('/utilisateur/teacher/delete-user-in-year', [EnseignantController::class, 'deleteUserCurrentYear'])->name('teacher.teacherDeleteUserCurrentYear')->middleware('auth');

## users -> student routes
Route::post('/utilisateur/student/save', [EleveController::class, 'store'])->name('student.store')->middleware('auth');
Route::put('/utilisateur/student/update/{id}', [EleveController::class, 'update'])->name('student.update')->middleware('auth');
Route::delete('/utilisateur/student/{id}', [EleveController::class, 'destroy'])->name('student.destroy')->middleware('auth');
Route::get('/utilisateur/student/{id}/edit/{yearId}', [EleveController::class, 'edit'])->name('student.edit')->middleware('auth');
Route::post('/utilisateur/student/migrate', [EleveController::class, 'migrate'])->name('student.studentMigrate')->middleware('auth');
Route::post('/utilisateur/student/delete-user-in-year', [EleveController::class, 'deleteUserCurrentYear'])->name('student.studentDeleteUserCurrentYear')->middleware('auth');


## users -> fonctions routes
Route::get('/utilisateur/fonctions', [FonctionController::class, 'index'])->name('utilisateur.fonctions')->middleware('auth');
Route::post('/utilisateur/fonction/save', [FonctionController::class, 'store'])->name('utilisateur.fonctionStore')->middleware('auth');
Route::put('/utilisateur/fonction/update/{id}', [FonctionController::class, 'update'])->name('utilisateur.fonctionUpdate')->middleware('auth');
Route::get('/utilisateur/fonctions/{id}/edit', [FonctionController::class, 'edit'])->name('utilisateur.fonctionEdit')->middleware('auth');
Route::delete('/utilisateur/fonctions/{id}', [FonctionController::class, 'destroy'])->name('utilisateur.fonctionDestroy')->middleware('auth');

## users -> personnel routes
Route::post('/utilisateur/personnel/save', [PersonnelController::class, 'store'])->name('utilisateur.personnelStore')->middleware('auth');
Route::put('/utilisateur/personnel/update/{id}', [PersonnelController::class, 'update'])->name('utilisateur.personnelUpdate')->middleware('auth');
Route::get('/utilisateur/personnels/{id}/edit/{yearId}', [PersonnelController::class, 'edit'])->name('utilisateur.personnelEdit')->middleware('auth');
Route::delete('/utilisateur/personnels/{id}', [PersonnelController::class, 'destroy'])->name('utilisateur.personnelDestroy')->middleware('auth');
Route::post('/utilisateur/personnels/migrate', [PersonnelController::class, 'migrate'])->name('utilisateur.personnelMigrate')->middleware('auth');
Route::post('/utilisateur/personnels/delete-user-in-year', [PersonnelController::class, 'deleteUserCurrentYear'])->name('utilisateur.personnelDeleteUserCurrentYear')->middleware('auth');

# annee_scolaire routes
Route::get('/anneescolaire', function () {
    return redirect('/anneescolaire/years');
})->middleware('auth');
Route::get('/anneescolaire/years', [AnneeScolaireController::class, 'index'])->name('anneescolaire.years')->middleware('auth');
Route::post('/anneescolaire/save', [AnneeScolaireController::class, 'store'])->name('anneescolaire.store')->middleware('auth');
Route::put('/anneescolaire/update/{id}', [AnneeScolaireController::class, 'update'])->name('anneescolaire.update')->middleware('auth');
Route::get('/anneescolaire/edit/{id}', [AnneeScolaireController::class, 'edit'])->name('anneescolaire.edit')->middleware('auth');
Route::delete('/anneescolaire/delete/{id}', [AnneeScolaireController::class, 'destroy'])->name('anneescolaire.destroy')->middleware('auth');
Route::put('/anneescolaire/activate/{id}', [AnneeScolaireController::class, 'activate'])->name('anneescolaire.activate')->middleware('auth');
Route::put('/anneescolaire/desactivate/{id}', [AnneeScolaireController::class, 'desactivate'])->name('anneescolaire.desactivate')->middleware('auth');


# actualites routes

Route::get('/laravel-examples/user-profile', [ProfileController::class, 'index'])->name('users.profile')->middleware('auth');
Route::put('/laravel-examples/user-profile/update', [ProfileController::class, 'update'])->name('users.update')->middleware('auth');
Route::get('/laravel-examples/users-management', [UserController::class, 'index'])->name('users-management')->middleware('auth');


# special for storage link
Route::get('/linkstorage', function() {
    Artisan::call('storage:link');
});

// route for uploading image from ckeditor :
    #Route::post('/upload/image', 'App\Http\Controllers\Admin\ImageUploadController@upload');//->name('upload.image');
    Route::post('/upload', [App\Http\Controllers\ImageUploadController::class, 'storeImage'])->name('upload.image');
