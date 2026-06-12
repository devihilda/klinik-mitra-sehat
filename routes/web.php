<?php

use App\Http\Controllers\Officer\DoctorController;
use App\Http\Controllers\Officer\DoctorScheduleController;
use App\Http\Controllers\Officer\MedicalRecordController;
use App\Http\Controllers\Officer\PatientController;
use App\Http\Controllers\Officer\PolyclinicController;
use App\Http\Controllers\Officer\QueueController as OfficerQueueController;
use App\Http\Controllers\Patient\DashboardController;
use App\Http\Controllers\Patient\QueueController as PatientQueueController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Redirector /dashboard based on User Role (with commented-out secure and vulnerable logic)
Route::get('/dashboard', function () {
    $user = auth()->user();

    // Redirect sesuai role
    if ($user->role === 'petugas') {
        return redirect()->route('officers.dashboard');
    }

    return redirect()->route('patients.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Grup Rute Pasien (Terproteksi Middleware Role mode rentan)
Route::middleware(['auth', 'role:pasien'])->prefix('pasien')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('patients.dashboard');
    Route::resource('queues', PatientQueueController::class)->except(['edit', 'update'])->names('patients.queues');
});

// Grup Rute Petugas / Admin (Terproteksi Middleware Role mode rentan)
Route::middleware(['auth', 'role:petugas'])->prefix('petugas')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Officer\DashboardController::class, 'index'])->name('officers.dashboard');

    Route::resource('patients', PatientController::class);
    Route::resource('medical-records', MedicalRecordController::class);
    Route::resource('polyclinics', PolyclinicController::class);
    Route::resource('doctors', DoctorController::class);
    Route::resource('doctor-schedules', DoctorScheduleController::class);
    Route::resource('queues', OfficerQueueController::class);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
