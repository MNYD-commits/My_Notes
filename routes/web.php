<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

Route::get('/dashboard', [AuthController::class, 'showDashboard'])->name('dashboard');
Route::post('/dashboard', [AuthController::class, 'showDashboard'])->name('dashboard.post');

Route::middleware('auth')->group(function () {
    Route::get('/notes', [AuthController::class, 'showNotes'])->name('notes');
    Route::post('/notes', [AuthController::class, 'showNotes'])->name('notes.post');
    Route::post('/notes/store', [AuthController::class, 'storeNote'])->name('notes.store');
    Route::get('/notes/{id}', [AuthController::class, 'getNote'])->name('notes.get');
    Route::post('/notes/{id}/update', [AuthController::class, 'updateNote'])->name('notes.update');
    Route::delete('/notes/{id}/delete', [AuthController::class, 'deleteNote'])->name('notes.delete');
    
    Route::get('/user', [AuthController::class, 'showUser'])->name('user');
    Route::post('/user/store', [AuthController::class, 'storeUser'])->name('user.store');
    Route::get('/user/{id}/edit', [AuthController::class, 'editUser'])->name('user.edit');
    Route::post('/user/{id}/edit', [AuthController::class, 'updateUser'])->name('user.update');
    Route::delete('/user/{id}/delete', [AuthController::class, 'deleteUser'])->name('user.delete');
    
    Route::get('/dashboard', [AuthController::class, 'showDashboard'])->name('dashboard');
    Route::post('/dashboard', [AuthController::class, 'showDashboard'])->name('dashboard.post');
    Route::get('/profile', [AuthController::class, 'showProfile'])->name('profile');
    Route::post('/profile', [AuthController::class, 'updateProfile'])->name('profile.update');
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::get('/profile', [AuthController::class, 'showProfile'])->name('profile');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');