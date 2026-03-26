<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Settings\SchoolSettingsController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [AuthController::class, 'create'])->name('login');
    Route::post('/login', [AuthController::class, 'store'])->name('login.store');
});

Route::middleware('auth')->group(function (): void {
    Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');
    Route::get('/dashboard', fn () => Inertia::render('Dashboard'));
});

Route::middleware('auth')->name('settings.school.')->prefix('settings/school')->group(function (): void {
    Route::get('/', [SchoolSettingsController::class, 'edit'])->name('edit');
    Route::post('/', [SchoolSettingsController::class, 'update'])->name('update');
});
