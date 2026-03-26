<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Settings\SchoolSettingsController;
use Illuminate\Support\Facades\Route;

// ── Page d'accueil → login ────────────────────────────────────────────────
Route::get('/', fn () => redirect()->route('login'));

// ── Authentification (invités uniquement) ─────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login',  [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.store');
});

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

// ── Application (authentifié) ─────────────────────────────────────────────
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Paramètres école (Directeur uniquement)
    Route::middleware('role:Directeur')->group(function () {
        Route::get('/parametres/ecole',   [SchoolSettingsController::class, 'edit'])
            ->name('settings.school.edit');
        Route::patch('/parametres/ecole', [SchoolSettingsController::class, 'update'])
            ->name('settings.school.update');
    });

    // ── Stubs modules (en cours de développement) ─────────────────────────
    $comingSoon = fn () => redirect()->route('dashboard')
        ->with('info', 'Ce module est en cours de développement.');

    Route::get('/eleves',          $comingSoon)->name('students.index');
    Route::get('/classes',         $comingSoon)->name('classrooms.index');
    Route::get('/personnel',       $comingSoon)->name('staff.index');
    Route::get('/emploi-du-temps', $comingSoon)->name('timetable.index');
    Route::get('/notes',           $comingSoon)->name('grades.index');
    Route::get('/paiements',       $comingSoon)->name('payments.index');
    Route::get('/absences',        $comingSoon)->name('absences.index');
    Route::get('/documents',       $comingSoon)->name('documents.index');
});
