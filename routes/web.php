<?php

use App\Http\Controllers\Settings\SchoolSettingsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\DashboardController;



Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->name('settings.school.')->prefix('settings/school')->group(function (): void {
    Route::get('/', [SchoolSettingsController::class, 'edit'])->name('edit');
    Route::post('/', [SchoolSettingsController::class, 'update'])->name('update');
});

// ── Authentification ──────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])
        ->name('login.store');
});

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

// ── Application (authentifié) ─────────────────────────────────────────────
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Paramètres école (Directeur uniquement)
    Route::middleware('role:Directeur')->group(function () {
        Route::get('/parametres/ecole', [SchoolSettingsController::class, 'edit'])
            ->name('settings.school.edit');
        Route::patch('/parametres/ecole', [SchoolSettingsController::class, 'update'])
            ->name('settings.school.update');
    });

    // TODO — les autres modules seront ajoutés ici au fil du développement :
    // Route::resource('students',   StudentController::class);
    // Route::resource('classrooms', ClassroomController::class);
    // Route::resource('staff',      StaffController::class);
    // Route::resource('grades',     GradeController::class);
    // Route::resource('absences',   AbsenceController::class);
    // Route::resource('payments',   PaymentController::class);
    // Route::resource('timetable',  TimetableController::class);
});
