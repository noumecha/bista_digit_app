<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClasseController;
use App\Http\Controllers\CoefficientController;
use App\Http\Controllers\ConseilDisciplineController;
use App\Http\Controllers\DevoirController;
use App\Http\Controllers\DisciplineController;
use App\Http\Controllers\EnseignantMatiereModelController;
use App\Http\Controllers\EnseignantPrincipalController;
use App\Http\Controllers\EnseignementController;
use App\Http\Controllers\EpreuveController;
use App\Http\Controllers\MatiereController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\ReponseController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\TrimestreController;
use App\Http\Controllers\TypeEpreuveController;

/*
|--------------------------------------------------------------------------
| Education Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::middleware(['can:access-discipline'])->group(function () {
    Route::get('/education/discipline', [DisciplineController::class, 'index'])->name('education.discipline')->middleware('auth');
    Route::get('/education/conseildiscipline', [ConseilDisciplineController::class, 'index'])->name('education.conseildiscipline')->middleware('auth');

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
});

Route::middleware(['can:access-admin'])->group(function () {
    Route::get('/education/type_epreuves', [TypeEpreuveController::class, 'index'])->name('education.type_epreuves')->middleware('auth');
    Route::get('/education/matieres', [MatiereController::class, 'index'])->name('education.matiere')->middleware('auth');
    Route::get('/education/classes', [ClasseController::class, 'index'])->name('education.classes')->middleware('auth');
    Route::get('/education/sections', [SectionController::class, 'index'])->name('education.sections')->middleware('auth');
    Route::get('/education/coefficients', [CoefficientController::class, 'index'])->name('education.coefficients')->middleware('auth');
    Route::get('/education/enseignement', [EnseignementController::class, 'index'])->name('education.enseignement')->middleware('auth');
    Route::get('/education/enseignantMatiere', [EnseignantMatiereModelController::class, 'index'])->name('education.enseignantMatiere')->middleware('auth');

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
});

Route::middleware(['can:access-student-devoir'])->group(function () {
    Route::get('/education/devoirs/student/{devoir}/start/{number?}', [DevoirController::class, 'start'])->name('devoirs.start')->middleware('auth');
    Route::get('/education/devoirs/student/{devoir}/previous/{number?}', [DevoirController::class, 'previous'])->name('devoirs.previous')->middleware('auth');
    Route::post('/education/devoirs/student/{devoir}/answer', [DevoirController::class, 'answer'])->name('devoirs.answer')->middleware('auth');
    Route::get('/education/devoirs/result/{id}', [DevoirController::class, 'result'])->name('devoirs.results')->middleware('auth');
});

Route::middleware(['can:access-devoirs'])->group(function () {
    Route::get('/education/devoirs', [DevoirController::class, 'index'])->name('education.devoirs')->middleware('auth');
});

Route::middleware(['can:access-teacher'])->group(function () {
    # education routes
    Route::get('/education/epreuves', [EpreuveController::class, 'index'])->name('education.epreuves')->middleware('auth');
    Route::get('/education/questions', [QuestionController::class, 'index'])->name('education.questions')->middleware('auth');
    Route::get('/education/reponses', [ReponseController::class, 'index'])->name('education.reponses')->middleware('auth');

    ## education - epreuves routes
    Route::post('/education/epreuves/save', [EpreuveController::class, 'store'])->name('epreuves.store')->middleware('auth');
    Route::get('/education/epreuves/{id}/edit', [EpreuveController::class, 'edit'])->name('epreuves.edit')->middleware('auth');
    Route::put('/education/epreuves/update/{id}', [EpreuveController::class, 'update'])->name('epreuves.update')->middleware('auth');
    Route::delete('/education/epreuves/{id}', [EpreuveController::class, 'destroy'])->name('epreuves.destroy')->middleware('auth');

    ## education -> epreuves CRUD routes
    Route::post('/epreuve/save', [EpreuveController::class, 'store'])->name('epreuve.store')->middleware('auth');
    Route::put('/epreuve/{id}', [EpreuveController::class, 'update'])->name('epreuve.update')->middleware('auth');
    Route::get('/epreuve/{id}/edit', [EpreuveController::class, 'edit'])->name('epreuve.edit')->middleware('auth');
    Route::delete('/epreuve/{id}', [EpreuveController::class, 'destroy'])->name('epreuve.destroy')->middleware('auth');

    ## education -> devoirs routes
    Route::post('/education/devoirs/save', [DevoirController::class, 'store'])->name('devoir.store')->middleware('auth');
    Route::put('/education/devoirs/update/{id}', [DevoirController::class, 'update'])->name('devoir.update')->middleware('auth');
    Route::get('/education/devoirs/{id}/edit/{yearId}', [DevoirController::class, 'edit'])->name('devoir.edit')->middleware('auth');
    Route::delete('/education/devoirs/{id}', [DevoirController::class, 'destroy'])->name('devoir.destroy')->middleware('auth');
    Route::post('/education/devoirs/migrate', [DevoirController::class, 'migrate'])->name('devoir.migrate')->middleware('auth');
    Route::post('/education/devoirs/delete-in-year', [DevoirController::class, 'deleteInCurrentYear'])->name('devoir.deleteInCurrentYear')->middleware('auth');
    Route::get('/education/devoirs/yeardates', [DevoirController::class, 'getCurrentYearDates'])->middleware('auth');

    ## controles devois routes - teacher + admin
    Route::get('/education/devoirs/controle/{devoir}', [DevoirController::class, 'teacherShow'])->name('devoirs.teacher.show')->middleware('auth');
    Route::get('/education/devoirs/teacher/result/{devoir}/{userId?}', [DevoirController::class, 'individualResult'])->name('devoirs.teacher.results')->middleware('auth');

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
});