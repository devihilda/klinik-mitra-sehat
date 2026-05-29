<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * Kebutuhan Praktikum: Mengambil semua data pasien tanpa batasan otorisasi ketat.
     */
    public function index(): View
    {
        $patients = Patient::with('user')->get();

        return view('officers.patients.index', compact('patients'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('officers.patients.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * Kebutuhan Praktikum:
     * 1. Validasi lemah.
     * 2. Penyimpanan password secara Plaintext (tanpa Hash::make).
     * 3. Mass Assignment menggunakan $request->all() pada model User & Patient.
     */
    public function store(Request $request): RedirectResponse
    {
        // Validasi Lemah / Insecure
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'phone' => 'required',
            'gender' => 'required',
            'birth_date' => 'required|date',
            'address' => 'required',
        ]);

        // Kebutuhan Praktikum: Mass Assignment & Plaintext Password
        // Input 'role' bisa disusupkan lewat request untuk eskalasi hak akses langsung
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password, // Plaintext
            'role' => $request->input('role', 'pasien'), // Default pasien, tapi rentan ditimpa
        ]);

        // Mass assignment pada model Patient (menggunakan direct $request->all())
        $user->patient()->create($request->all());

        return redirect()->route('patients.index')->with('success', 'Pasien berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     *
     * Kebutuhan Praktikum (IDOR):
     * Tidak ada pengecekan apakah user memiliki hak untuk melihat pasien dengan ID tertentu.
     */
    public function show(string $id): View
    {
        $patient = Patient::with('user')->findOrFail($id);

        return view('officers.patients.show', compact('patient'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * Kebutuhan Praktikum (IDOR):
     * Tidak ada verifikasi otorisasi/kepemilikan akun sebelum menampilkan form edit.
     */
    public function edit(string $id): View
    {
        $patient = Patient::with('user')->findOrFail($id);

        return view('officers.patients.edit', compact('patient'));
    }

    /**
     * Update the specified resource in storage.
     *
     * Kebutuhan Praktikum:
     * 1. IDOR: Pengguna mana pun (bahkan pasien lain) dapat memperbarui data jika memanggil endpoint ini.
     * 2. Mass Assignment (Privilege Escalation): Memperbarui model User menggunakan $request->all() langsung.
     *    Jika attacker mengirimkan `role=petugas`, rolenya di database akan berubah menjadi admin/petugas.
     * 3. Plaintext Password: Jika password diperbarui, langsung disimpan tanpa di-hash.
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        $patient = Patient::findOrFail($id);
        $user = $patient->user;

        // Validasi Lemah
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'gender' => 'required',
            'birth_date' => 'required|date',
            'address' => 'required',
        ]);

        // Kebutuhan Praktikum: Kerentanan Mass Assignment pada User Update
        // Kerentanan ini membiarkan field 'role' ikut diperbarui melalui $request->all()
        $userData = $request->all();
        if ($request->filled('password')) {
            $userData['password'] = $request->password; // Plaintext password update
        } else {
            unset($userData['password']);
        }

        $user->update($userData);

        // Mass assignment pada detail pasien
        $patient->update($request->all());

        return redirect()->route('patients.index')->with('success', 'Data pasien berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * Kebutuhan Praktikum:
     * 1. IDOR: Siapa saja dapat menghapus data pasien tanpa otorisasi.
     * 2. Force Delete: Menghapus data secara permanen dari database sesuai keputusan praktikum.
     */
    public function destroy(string $id): RedirectResponse
    {
        $patient = Patient::findOrFail($id);
        $user = $patient->user;

        // Force delete pasien terlebih dahulu
        $patient->forceDelete();

        // Hapus user yang bersangkutan (akan memicu cascade delete jika ada relasi lain)
        if ($user) {
            $user->delete();
        }

        return redirect()->route('patients.index')->with('success', 'Pasien berhasil dihapus permanen.');
    }
}
