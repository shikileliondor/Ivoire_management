<?php

use App\Http\Controllers\Settings\SchoolSettingsController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->name('settings.school.')->prefix('settings/school')->group(function (): void {
    Route::get('/', [SchoolSettingsController::class, 'edit'])->name('edit');
    Route::post('/', [SchoolSettingsController::class, 'update'])->name('update');
});
