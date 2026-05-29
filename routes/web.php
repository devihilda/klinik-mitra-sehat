<?php

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
    Route::get('/dashboard', [\App\Http\Controllers\Patient\DashboardController::class, 'index'])->name('patients.dashboard');
});

// Grup Rute Petugas / Admin (Terproteksi Middleware Role mode rentan)
Route::middleware(['auth', 'role:petugas'])->prefix('petugas')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Officer\DashboardController::class, 'index'])->name('officers.dashboard');

    Route::resource('patients', \App\Http\Controllers\Patient\PatientController::class);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
