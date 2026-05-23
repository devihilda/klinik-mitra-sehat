<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', [AuthController::class, 'showLoginForm']);
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// Halaman Dashboard Pasien
Route::get('/dashboard-pasien', function () {
    if (!session()->has('user_id') || session('role') !== 'pasien') {
        return redirect('/login')->with('error', 'Silakan login sebagai pasien terlebih dahulu.');
    }
    return view('dashboard.pasien');
});

// Halaman Dashboard Petugas
Route::get('/dashboard-petugas', function () {
    if (!session()->has('user_id') || session('role') !== 'petugas') {
        return redirect('/login')->with('error', 'Silakan login sebagai petugas terlebih dahulu.');
    }
    return view('dashboard.petugas');
});
