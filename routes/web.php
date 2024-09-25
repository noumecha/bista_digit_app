<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\ActusController;
use App\Http\Controllers\AnneeScolaireController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\BullettinController;
use App\Http\Controllers\CategorieActualiteController;
use App\Http\Controllers\ClasseController;
use App\Http\Controllers\CoefficientController;
use App\Http\Controllers\DevoirController;
use App\Http\Controllers\DisciplineController;
use App\Http\Controllers\EleveController;
use App\Http\Controllers\EnseignantController;
use App\Http\Controllers\EnseignantMatiereModelController;
use App\Http\Controllers\EnseignementController;
use App\Http\Controllers\EpreuveController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MatiereController;
use App\Http\Controllers\PersonnelController;
use App\Http\Controllers\ProgrammeController;
use App\Http\Controllers\TypeEpreuveController;
use App\Http\Controllers\UtilisateurController;
use App\Models\CategorieActualite;
use App\Models\Personnel;
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

// primary routes for the front-website :
Route::get('/acceuil', [HomeController::class, 'index'])->name('home.index');
Route::get('/programmes', [HomeController::class, 'programmes'])->name('home.programmes');
Route::get('/clubs', [HomeController::class, 'clubs'])->name('home.clubs');
Route::get('/epreuves', [HomeController::class, 'epreuves'])->name('home.epreuves');
Route::get('/', [HomeController::class, 'index'])->name('home.index');
Route::get('/apropos', [AboutController::class, 'index'])->name('home.about');
Route::get('/actualites', [HomeController::class, 'actualites'])->name('home.actus');
Route::get('/actualite/{id}/read', [HomeController::class, 'showActualite'])->name('actualites.show');
Route::get('/epreuve/{id}/read', [HomeController::class, 'showEpreuve'])->name('home.showepreuve');

// Actualites CRUD Routes :
Route::get('/actualites/create', [ActusController::class, 'index'])->name('actualites.index')->middleware('auth');
Route::post('/actualites/save', [ActusController::class, 'store'])->name('actualite.store')->middleware('auth');
Route::put('/actualites/{id}', [ActusController::class, 'update'])->name('actualite.update')->middleware('auth');
Route::get('/actualites/{id}/edit', [ActusController::class, 'edit'])->name('actualite.edit')->middleware('auth');
Route::delete('/actualites/{id}', [ActusController::class, 'destroy'])->name('actualite.destroy')->middleware('auth');

// Categories actualites CRUD Routes :
Route::get('/categories/actualites', [CategorieActualiteController::class, 'index'])->name('actualites.categories')->middleware('auth');
Route::post('/categories/save', [CategorieActualiteController::class, 'store'])->name('categorie.store')->middleware('auth');
Route::put('/categories/{id}', [CategorieActualiteController::class, 'update'])->name('categorie.update')->middleware('auth');
Route::get('/categories/{id}/edit', [CategorieActualiteController::class, 'edit'])->name('categorie.edit')->middleware('auth');
Route::delete('/categories/{id}', [CategorieActualiteController::class, 'destroy'])->name('categorie.destroy')->middleware('auth');


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
Route::get('/education/devoir', [DevoirController::class, 'index'])->name('education.devoir')->middleware('auth');
Route::get('/education/epreuves', [EpreuveController::class, 'index'])->name('education.epreuves')->middleware('auth');
Route::get('/education/type_epreuves', [TypeEpreuveController::class, 'index'])->name('education.type_epreuves')->middleware('auth');
Route::get('/education/discipline', [DisciplineController::class, 'index'])->name('education.discipline')->middleware('auth');
Route::get('/education/matieres', [MatiereController::class, 'index'])->name('education.matiere')->middleware('auth');
Route::get('/education/classes', [ClasseController::class, 'index'])->name('education.classes')->middleware('auth');
Route::get('/education/coefficients', [CoefficientController::class, 'index'])->name('education.coefficients')->middleware('auth');
Route::get('/education/enseignement', [EnseignementController::class, 'index'])->name('education.enseignement')->middleware('auth');
Route::get('/education/enseignantMatiere', [EnseignantMatiereModelController::class, 'index'])->name('education.enseignantMatiere')->middleware('auth');
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
Route::post('/enseignantMatiere/save', [EnseignantMatiereModelController::class, 'store'])->name('enseignantMatiere.store')->middleware('auth');
Route::put('/enseignantMatiere/{id}', [EnseignantMatiereModelController::class, 'update'])->name('enseignantMatiere.update')->middleware('auth');
Route::get('/enseignantMatiere/{id}/edit', [EnseignantMatiereModelController::class, 'edit'])->name('enseignantMatiere.edit')->middleware('auth');
Route::delete('/enseignantMatiere/{id}', [EnseignantMatiereModelController::class, 'destroy'])->name('enseignantMatiere.destroy')->middleware('auth');
## education -> course routes
Route::post('/matieres/save', [MatiereController::class, 'store'])->name('matiere.store')->middleware('auth');
Route::put('/matieres/{id}', [MatiereController::class, 'update'])->name('matiere.update')->middleware('auth');
Route::get('/matieres/{id}/edit', [MatiereController::class, 'edit'])->name('matiere.edit')->middleware('auth');
Route::delete('/matieres/{id}', [MatiereController::class, 'destroy'])->name('matiere.destroy')->middleware('auth');
## education -> classes routes
Route::post('/classe/save', [ClasseController::class, 'store'])->name('classe.store')->middleware('auth');
Route::put('/classe/{id}', [ClasseController::class, 'update'])->name('classe.update')->middleware('auth');
Route::get('/classe/{id}/edit', [ClasseController::class, 'edit'])->name('classe.edit')->middleware('auth');
Route::delete('/classe/{id}', [ClasseController::class, 'destroy'])->name('classe.destroy')->middleware('auth');
## education -> coefficient routes
Route::post('/coefficient/save', [CoefficientController::class, 'store'])->name('coefficient.store')->middleware('auth');
Route::put('/coefficient/{id}', [CoefficientController::class, 'update'])->name('coefficient.update')->middleware('auth');
Route::get('/coefficient/{id}/edit', [CoefficientController::class, 'edit'])->name('coefficient.edit')->middleware('auth');
Route::delete('/coefficient/{id}', [CoefficientController::class, 'destroy'])->name('coefficient.destroy')->middleware('auth');
## education -> enseignement routes
Route::post('/enseignement/save', [EnseignementController::class, 'store'])->name('enseignement.store')->middleware('auth');
Route::put('/enseignement/{id}', [EnseignementController::class, 'update'])->name('enseignement.update')->middleware('auth');
Route::get('/enseignement/{id}/edit', [EnseignementController::class, 'edit'])->name('enseignement.edit')->middleware('auth');
Route::delete('/enseignement/{id}', [EnseignementController::class, 'destroy'])->name('enseignement.destroy')->middleware('auth');

# evaluation routes
Route::get('/evaluation/trimestres', [EvaluationController::class, 'index'])->name('evaluation.trimestres')->middleware('auth');
Route::get('/evaluation/notes', [EvaluationController::class, 'index'])->name('evaluation.notes')->middleware('auth');
Route::get('/evaluation/bulletins', [EvaluationController::class, 'index'])->name('evaluation.bulletins')->middleware('auth');

# personnel routes
Route::get('/utilisateur/administrators', [PersonnelController::class, 'index'])->name('utilisateur.administrators')->middleware('auth');
Route::get('/utilisateur/teachers', [EnseignantController::class, 'index'])->name('utilisateur.teachers')->middleware('auth');
Route::get('/utilisateur/students', [EleveController::class, 'index'])->name('utilisateur.students')->middleware('auth');
## personnel -> teacher routes
Route::post('/teacher/save', [EnseignantController::class, 'store'])->name('teacher.store')->middleware('auth');
Route::put('/teacher/{id}', [EnseignantController::class, 'update'])->name('teacher.update')->middleware('auth');
Route::get('/teacher/{id}/edit', [EnseignantController::class, 'edit'])->name('teacher.edit')->middleware('auth');
Route::delete('/teacher/{id}', [EnseignantController::class, 'destroy'])->name('teacher.destroy')->middleware('auth');
## personnel -> student routes
Route::post('/student/save', [EleveController::class, 'store'])->name('student.store')->middleware('auth');
Route::put('/student/{id}', [EleveController::class, 'update'])->name('student.update')->middleware('auth');
Route::get('/student/{id}/edit', [EleveController::class, 'edit'])->name('student.edit')->middleware('auth');
Route::delete('/student/{id}', [EleveController::class, 'destroy'])->name('student.destroy')->middleware('auth');
## personnel -> student routes
Route::post('/personnel/save', [PersonnelController::class, 'store'])->name('personnel.store')->middleware('auth');
Route::put('/personnel/{id}', [PersonnelController::class, 'update'])->name('personnel.update')->middleware('auth');
Route::get('/personnel/{id}/edit', [PersonnelController::class, 'edit'])->name('personnel.edit')->middleware('auth');
Route::delete('/personnel/{id}', [PersonnelController::class, 'destroy'])->name('personnel.destroy')->middleware('auth');


# annee_scolaire routes
Route::get('/annee_scolaire/list', [AnneeScolaireController::class, 'show'])->name('annee_scolaire.show')->middleware('auth');
Route::post('/annee_scolaire/save', [AnneeScolaireController::class, 'store'])->name('annee_scolaire.store')->middleware('auth');


# actualites routes

# programme routes
Route::get('/programme/booster', [ProgrammeController::class, 'index'])->name('programme.booster')->middleware('auth');
Route::get('/programme/leader', [ProgrammeController::class, 'index'])->name('programme.leader')->middleware('auth');


Route::get('/laravel-examples/user-profile', [ProfileController::class, 'index'])->name('users.profile')->middleware('auth');
Route::put('/laravel-examples/user-profile/update', [ProfileController::class, 'update'])->name('users.update')->middleware('auth');
Route::get('/laravel-examples/users-management', [UserController::class, 'index'])->name('users-management')->middleware('auth');

# special for storage link
Route::get('/linkstorage', function() {
    Artisan::call('storage:link');
});

// route for uploading image from ckeditor :
    Route::post('/upload/image', 'App\Http\Controllers\Admin\ImageUploadController@upload');//->name('upload.image');
    Route::post('/upload', [App\Http\Controllers\ImageUploadController::class, 'storeImage'])->name('upload.image');
