<?php

use App\Http\Controllers\AboutController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\BullettinController;
use App\Http\Controllers\DevoirController;
use App\Http\Controllers\EpreuveController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PersonnelController;
use App\Http\Controllers\ProgrammeController;
use App\Http\Controllers\UtilisateurController;
use App\Models\Personnel;

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
Route::get('/home', [HomeController::class, 'index'])->name('home.index');
Route::get('/', [HomeController::class, 'index'])->name('home.index');
Route::get('/about', [AboutController::class, 'index'])->name('home.about');

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
Route::get('/education/epreuves', [EpreuveController::class, 'index'])->name('education.epreuve')->middleware('auth');
Route::get('/education/discipline', [EpreuveController::class, 'index'])->name('education.discipline')->middleware('auth');
Route::get('/education/bulletin', [BullettinController::class, 'index'])->name('education.bulletin')->middleware('auth');

# evaluation routes
Route::get('/evaluation/trimestres', [EvaluationController::class, 'index'])->name('evaluation.trimestres')->middleware('auth');
Route::get('/evaluation/notes', [EvaluationController::class, 'index'])->name('evaluation.notes')->middleware('auth');
Route::get('/evaluation/bulletins', [EvaluationController::class, 'index'])->name('evaluation.bulletins')->middleware('auth');

# personnel routes
Route::get('/utilisateur/administrators', [UtilisateurController::class, 'administrators'])->name('utilisateur.administrators')->middleware('auth');
Route::get('/utilisateur/teachers', [UtilisateurController::class, 'teachers'])->name('utilisateur.teachers')->middleware('auth');
Route::get('/utilisateur/students', [UtilisateurController::class, 'students'])->name('utilisateur.students')->middleware('auth');
Route::post('/personnel/save', [PersonnelController::class, 'store'])->name('personnel.store')->middleware('auth');


# actualites routes

# programme routes
Route::get('/programme/booster', [ProgrammeController::class, 'index'])->name('programme.booster')->middleware('auth');
Route::get('/programme/leader', [ProgrammeController::class, 'index'])->name('programme.leader')->middleware('auth');


Route::get('/laravel-examples/user-profile', [ProfileController::class, 'index'])->name('users.profile')->middleware('auth');
Route::put('/laravel-examples/user-profile/update', [ProfileController::class, 'update'])->name('users.update')->middleware('auth');
Route::get('/laravel-examples/users-management', [UserController::class, 'index'])->name('users-management')->middleware('auth');
