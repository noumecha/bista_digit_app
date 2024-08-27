<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;


Route::prefix('admin')->group(function() {
    Route::get('/', [AdminController::class, 'index'])->name('admin.home');
    Route::get('/admin', [AdminController::class, 'dashboard'])->name('admin.dashboard');
});
