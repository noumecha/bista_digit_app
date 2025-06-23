<?php

// primary routes for the front-website :

use App\Http\Controllers\AboutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\StatisticsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Front Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/acceuil', [HomeController::class, 'index'])->name('home.index');
# statistics routes
Route::get('/statistiques', [StatisticsController::class, 'stats'])->name('home.stats');
Route::get('/statistiques/{id}', [StatisticsController::class, 'showDetails'])->name('front.statistics.details');
# redirection to home
Route::get('/', function () { return redirect('/acceuil'); })->middleware('auth');
Route::get('/programmes', [HomeController::class, 'programmes'])->name('home.programmes');
Route::get('/programmes/leader', [HomeController::class, 'leaderPage'])->name('home.leaderPage');
Route::get('/programmes/booster', [HomeController::class, 'boosterPage'])->name('home.boosterPage');
Route::get('/clubs', [HomeController::class, 'clubs'])->name('home.clubs');
Route::get('/epreuves', [HomeController::class, 'epreuves'])->name('home.epreuves');
Route::get('/contact', [HomeController::class, 'contact'])->name('home.contact');
Route::get('/apropos', [AboutController::class, 'index'])->name('home.about');
Route::get('/actualites', [HomeController::class, 'actualites'])->name('home.actus');
Route::get('/actualites/categorie/{category}', [HomeController::class, 'showCategorie'])->name('home.showCategorie');
Route::get('/atouts/atout/{id}', [HomeController::class, 'specialitePage'])->name('home.specialitePage');
Route::get('/clubs/club/{id}', [HomeController::class, 'clubPage'])->name('home.clubPage');
Route::get('/actualite/{id}/read', [HomeController::class, 'showActualite'])->name('actualites.show');
Route::get('/epreuve/{id}/read', [HomeController::class, 'showEpreuve'])->name('home.showepreuve');