<?php

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
use App\Http\Controllers\CategorieActualiteController;
use App\Http\Controllers\ClubConfigurationController;
use App\Http\Controllers\ClubController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EleveController;
use App\Http\Controllers\EnseignantController;
use App\Http\Controllers\FonctionController;
use App\Http\Controllers\IamLeaderController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PersonnelController;
use App\Http\Controllers\ProgrammeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SliderController;
use App\Http\Controllers\SpecialiteController;
use App\Http\Controllers\StatisticsController;
use Illuminate\Support\Facades\Artisan;

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
Route::get('/admin', function () { return redirect('/dashboard');})->middleware('auth');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware('auth');

# configuration routes - club configuration
Route::middleware(['can:access-club'])->group(function () {
    Route::get('/configurations/club_configuration', [ClubConfigurationController::class, 'index'])->name('club_configuration.index')->middleware('auth');
    Route::get('/configurations/club_configuration/{id}/edit', [ClubConfigurationController::class, 'edit'])->name('club_configuration.edit')->middleware('auth');
    Route::post('/configurations/club_configuration/{action}', [ClubConfigurationController::class, 'update'])->name('club_configuration.update')->middleware('auth');
});

# configurations routes -  profil configuration
Route::get('/configurations/profile', function () {
    return redirect('/configurations/profile');
})->middleware('auth');
Route::get('/configurations/profile', [ProfileController::class, 'index'])->name('profile.index')->middleware('auth');
Route::get('/configurations/profile/{id}/edit', [ProfileController::class, 'edit'])->name('profile.edit')->middleware('auth');
Route::post('/configurations/profile/{action}', [ProfileController::class, 'update'])->name('profile.update')->middleware('auth');
Route::post('/configurations/profile/password/update', [ProfileController::class, 'updatePassword'])->name('profile.password')->middleware('auth');

Route::middleware(['can:access-teacher'])->group(function () {
    # configuration routes - booster notes routes
    Route::get('/programme/booster/notes', [BoosterNoteController::class, 'index'])->name('booster.notes')->middleware('auth');
    Route::get('/programme/booster/notes/controles', [BoosterNoteController::class, 'boosterRemplissageTrace'])->name('booster.notes_controles')->middleware('auth');
    Route::get('/programme/booster/notes/modifications', [BoosterNoteController::class, 'boosterNoteHistories'])->name('booster.notes_modifications')->middleware('auth');
    Route::post('/programme/booster/note/save', [BoosterNoteController::class, 'store'])->name('booster.noteStore')->middleware('auth');
    Route::put('/programme/booster/note/update/{id}', [BoosterNoteController::class, 'update'])->name('booster.noteUpdate')->middleware('auth');
    Route::delete('/programme/booster/notes/delete/{id}', [BoosterNoteController::class, 'destroy'])->name('booster.notesDestroy')->middleware('auth');
    Route::get('/programme/booster/notes/matieres/{classId}', [BoosterNoteController::class, 'getBoosterMatieres'])->middleware('auth');
});

Route::middleware(['can:access-actus'])->middleware(['can:access-personnel'])->group(function () {
    // Actualites CRUD Routes :
    Route::get('/actualites/create', [ActusController::class, 'index'])->name('actualites.index')->middleware('auth');
    Route::post('/actualites/save', [ActusController::class, 'store'])->name('actualite.store')->middleware('auth');
    Route::put('/actualites/update/{id}', [ActusController::class, 'update'])->name('actualite.update')->middleware('auth');
    Route::get('/actualites/{id}/edit', [ActusController::class, 'edit'])->name('actualite.edit')->middleware('auth');
    Route::delete('/actualites/{id}', [ActusController::class, 'destroy'])->name('actualite.destroy')->middleware('auth');
});
# every body can see his notifications
Route::get('/notifications/show', [NotificationController::class, 'index'])->name('notification.index')->middleware('auth');
Route::get('/notifications/read/{id}', [NotificationController::class, 'show'])->name('notifications.show');
Route::group(['prefix' => 'api'], function() {
    Route::get('/notifications/latest', [NotificationController::class, 'latest'])->middleware('auth');
    Route::get('/notifications/mark-as-read/{id}', [NotificationController::class, 'markAsRead'])->name('notifications.mark-all-as-read')->middleware('auth');
    Route::get('/notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-as-read')->middleware('auth');
});

Route::middleware(['can:access-personnel'])->group(function () {
    // Categories actualites CRUD Routes :
    Route::get('/categories/actualites', [CategorieActualiteController::class, 'index'])->name('actualites.categories')->middleware('auth');
    Route::post('/categories/actualites/save', [CategorieActualiteController::class, 'store'])->name('categorie.store')->middleware('auth');
    Route::put('/categories/actualites/update/{id}', [CategorieActualiteController::class, 'update'])->name('categorie.update')->middleware('auth');
    Route::get('/categories/actualites/{id}/edit', [CategorieActualiteController::class, 'edit'])->name('categorie.edit')->middleware('auth');
    Route::delete('/categories/actualites/{id}', [CategorieActualiteController::class, 'destroy'])->name('categorie.destroy')->middleware('auth');

    // notifications routes :
    Route::get('/notifications/view/{id}', [NotificationController::class, 'view'])->name('notification.view')->middleware('auth');
    Route::get('/notifications/create', [NotificationController::class, 'create'])->name('notification.create')->middleware('auth');
    Route::post('/notifications/save', [NotificationController::class, 'store'])->name('notification.send')->middleware('auth');
    Route::delete('/notifications/delete/{id}', [NotificationController::class, 'destroy'])->name('notification.destroy')->middleware('auth');
    Route::get('/notifications/users/{type}', [NotificationController::class, 'getUsers'])->name('notification.users');
});

Route::middleware(['can:access-admin'])->group(function () {
    // notifications controles or traces routes :
    // Route::get('/notifications/controles', [NotificationController::class, 'controles'])->name('notification.controles');

    # configuratioun routes - clubs routes
    Route::get('/configurations/clubs', function () {
        return redirect('/configurations/clubs/list');
    })->middleware('auth');
    Route::get('/configurations/clubs/list', [ClubController::class, 'index'])->name('clubs.index')->middleware('auth');
    Route::post('/configurations/clubs/save', [ClubController::class, 'store'])->name('clubs.store')->middleware('auth');
    Route::put('/configurations/clubs/update/{id}', [ClubController::class, 'update'])->name('clubs.update')->middleware('auth');
    Route::get('/configurations/clubs/{id}/edit', [ClubController::class, 'edit'])->name('clubs.edit')->middleware('auth');
    Route::delete('/configurations/clubs/{id}', [ClubController::class, 'destroy'])->name('clubs.destroy')->middleware('auth');

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

    # configuration routes - specialite routes
    Route::get('/configurations/specialite', function () {
        return redirect('/configurations/specialite/list');
    })->middleware('auth');
    Route::get('/configurations/specialite/list', [SpecialiteController::class, 'index'])->name('specialite.index')->middleware('auth');
    Route::post('/configurations/specialite/save', [SpecialiteController::class, 'store'])->name('specialite.store')->middleware('auth');
    Route::put('/configurations/specialite/update/{id}', [SpecialiteController::class, 'update'])->name('specialite.update')->middleware('auth');
    Route::get('/configurations/specialite/{id}/edit', [SpecialiteController::class, 'edit'])->name('specialite.edit')->middleware('auth');
    Route::delete('/configurations/specialite/{id}', [SpecialiteController::class, 'destroy'])->name('specialite.destroy')->middleware('auth');

    # users routes
    Route::get('/utilisateur/teachers', [EnseignantController::class, 'index'])->name('utilisateur.teachers')->middleware('auth');
    Route::get('/utilisateur/students', [EleveController::class, 'index'])->name('utilisateur.students')->middleware('auth');
    Route::get('/utilisateur/personnels', [PersonnelController::class, 'index'])->name('utilisateur.personnels')->middleware('auth');

    # stats routes
    Route::get('/configuration/statistiques', function () { return redirect('/configuration/statistiques/trimestres');})->middleware('auth');
    Route::post('/configuration/statistiques/publish', [StatisticsController::class, 'publish'])->name('statistics.publish');
    Route::get('/configuration/statistiques/trimestres', [StatisticsController::class, 'index'])->name('statistics.index')->middleware('auth');
    Route::post('/configuration/statistiques/pdf', [StatisticsController::class, 'exportPDF'])->name('statistics.exportPDF')->middleware('auth');
    Route::get('/configuration/statistiques/obc', [StatisticsController::class, 'obc'])->name('statistics.obc')->middleware('auth');
    Route::post('/configuration/statistiques/obc/save', [StatisticsController::class, 'storeOBC'])->name('statistics.obc.store');
    Route::get('/configuration/statistiques/obc/{id}/edit', [StatisticsController::class, 'editOBC'])->name('statistics.obc.edit')->middleware('auth');
    Route::put('/configuration/statistiques/obc/update/{id}', [StatisticsController::class, 'updateOBC'])->name('statistics.obc.update')->middleware('auth');
    Route::delete('/configuration/statistiques/obc/delete/{id}', [StatisticsController::class, 'destroyOBC'])->name('statistics.obc.destroy')->middleware('auth');

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

    ## users -> roles routes
    Route::get('/utilisateur/roles', [RoleController::class, 'index'])->name('utilisateur.roles')->middleware('auth');
    Route::post('/utilisateur/role/save', [RoleController::class, 'store'])->name('utilisateur.roleStore')->middleware('auth');
    Route::put('/utilisateur/role/update/{id}', [RoleController::class, 'update'])->name('utilisateur.roleUpdate')->middleware('auth');
    Route::get('/utilisateur/roles/{id}/edit', [RoleController::class, 'edit'])->name('utilisateur.roleEdit')->middleware('auth');
    Route::delete('/utilisateur/roles/{id}', [RoleController::class, 'destroy'])->name('utilisateur.roleDestroy')->middleware('auth');

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
});
# actualites routes
Route::get('/laravel-examples/user-profile', [ProfileController::class, 'index'])->name('users.profile')->middleware('auth');
Route::put('/laravel-examples/user-profile/update', [ProfileController::class, 'update'])->name('users.update')->middleware('auth');
Route::get('/laravel-examples/users-management', [UserController::class, 'index'])->name('users-management')->middleware('auth');


# special for storage link
Route::get('/linkstorage', function() {
    Artisan::call('storage:link');
});

// route for uploading image from ckeditor :
Route::post('/upload', [App\Http\Controllers\ImageUploadController::class, 'storeImage'])->name('upload.image');
