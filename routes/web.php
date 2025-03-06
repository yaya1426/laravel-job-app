<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JobApplicationsController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\JobVacancyController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/job/{id}', [JobVacancyController::class, 'show'])->name('job-vacancy.show');
    Route::get('/job/{id}/apply', [JobVacancyController::class, 'applyJob'])->name('job.apply');
    Route::post('/job/{id}/apply', [JobVacancyController::class, 'storeJobApplication'])->name('job.store-application');
    Route::get('/job-applications', [JobApplicationsController::class, 'index'])->name('job-applications.index');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
