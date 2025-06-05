<?php

use App\Http\Controllers\BullettinController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\RemplissageController;
use Illuminate\Support\Facades\Route;

Route::middleware(['can:access-teacher'])->group(function () {
    # evaluation - notes routes
    Route::get('/notes', function () {return redirect('/evaluation/notes');})->middleware('auth');
    Route::get('/evaluation/notes', [NoteController::class, 'index'])->name('evaluation.notes')->middleware('auth');
    Route::post('/evaluation/note/save', [NoteController::class, 'store'])->name('evaluation.noteStore')->middleware('auth');
    Route::put('/evaluation/note/update/{id}', [NoteController::class, 'update'])->name('evaluation.noteUpdate')->middleware('auth');
    Route::delete('/evaluation/notes/delete/{id}', [NoteController::class, 'destroy'])->name('evaluation.notesDestroy')->middleware('auth');
});

Route::middleware(['can:access-admin'])->group(function () {
    Route::get('/evaluation/notes/controles', [NoteController::class, 'remplissageTrace'])->name('evaluation.notes_controles')->middleware('auth');
    Route::get('/evaluation/notes/modifications', [NoteController::class, 'noteHistories'])->name('evaluation.notes_modifications')->middleware('auth');
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

});