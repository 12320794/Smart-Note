<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\TrashController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// ─── Public Landing Page ────────────────────────────────────────────────────
Route::get('/', function () {
    return redirect()->route('login');
})->name('home');

// ─── Guest-only routes (login / register) ───────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login',    [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login',   [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register',[AuthController::class, 'register']);
    
    // Forgot / Reset Password
    Route::get('/forgot-password',  [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
    Route::post('/reset-password',  [AuthController::class, 'resetPassword'])->name('password.update');
});

// ─── Authenticated routes ────────────────────────────────────────────────────
Route::middleware('auth')->group(function () {

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Notes (full resource + extras)
    Route::resource('notes', NoteController::class);
    Route::post('/notes/{note}/pin', [NoteController::class, 'togglePin'])->name('notes.pin');

    // Folders / Categories
    Route::get('/folders',              [CategoryController::class, 'index'])->name('categories.index');
    Route::post('/folders',             [CategoryController::class, 'store'])->name('categories.store');
    Route::put('/folders/{category}',   [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/folders/{category}',[CategoryController::class, 'destroy'])->name('categories.destroy');

    // Tags
    Route::get('/tags',           [TagController::class, 'index'])->name('tags.index');
    Route::post('/tags',          [TagController::class, 'store'])->name('tags.store');
    Route::put('/tags/{tag}',     [TagController::class, 'update'])->name('tags.update');
    Route::delete('/tags/{tag}',  [TagController::class, 'destroy'])->name('tags.destroy');

    // Trash
    Route::get('/trash',                   [TrashController::class, 'index'])->name('trash.index');
    Route::post('/trash/{id}/restore',     [TrashController::class, 'restore'])->name('trash.restore');
    Route::delete('/trash/{id}/force',     [TrashController::class, 'forceDelete'])->name('trash.force-delete');
    Route::delete('/trash/empty',          [TrashController::class, 'emptyTrash'])->name('trash.empty');

    // Profile
    Route::get('/profile',              [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile',              [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password',     [ProfileController::class, 'updatePassword'])->name('profile.password');
});
