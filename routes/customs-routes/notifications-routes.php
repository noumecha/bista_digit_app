<?php

use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;

Route::get('/notifications/view/{id}', [NotificationController::class, 'view'])->name('notification.view')->middleware('auth');
Route::get('/notifications/create', [NotificationController::class, 'create'])->name('notification.create')->middleware('auth');
Route::get('/notifications/index', [NotificationController::class, 'index'])->name('notification.index')->middleware('auth');
Route::post('/notifications/save', [NotificationController::class, 'store'])->name('notification.send')->middleware('auth');
Route::delete('/notifications/delete/{id}', [NotificationController::class, 'destroy'])->name('notification.destroy')->middleware('auth');
Route::get('/notifications/users/{type}', [NotificationController::class, 'getUsers'])->name('notification.users');
