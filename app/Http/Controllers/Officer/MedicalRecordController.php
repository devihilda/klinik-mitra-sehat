<?php

namespace App\Http\Controllers\Officer;

use App\Http\Controllers\Controller;
use App\Models\MedicalRecord;
use App\Models\Patient;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MedicalRecordController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        // Kebutuhan Praktikum: Mengambil semua rekam medis tanpa batasan otorisasi
        $records = MedicalRecord::with(['patient.user', 'officer'])->latest()->get();

        return view('officers.medical_records.index', compact('records'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $patients = Patient::with('user')->get();

        return view('officers.medical_records.create', compact('patients'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * Kebutuhan Praktikum:
     * 1. Validasi lemah/insecure.
     * 2. Menyimpan data langsung ke database tanpa melakukan sanitasi input (mempersiapkan Stored XSS).
     * 3. Mass Assignment menggunakan $request->all() langsung ke model.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'visit_date' => 'required|date',
            'diagnosis' => 'required',
            'treatment' => 'required',
        ]);

        // Input disimpan mentah-mentah ke DB
        MedicalRecord::create([
            'patient_id' => $request->patient_id,
            'officer_id' => auth()->id(), // Mencatat petugas yang login
            'visit_date' => $request->visit_date,
            'diagnosis' => $request->diagnosis,
            'treatment' => $request->treatment,
            'notes' => $request->notes,
        ]);

        return redirect()->route('medical-records.index')->with('success', 'Rekam medis berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     *
     * Kebutuhan Praktikum (IDOR & Stored XSS):
     * Tidak ada proteksi kepemilikan/otorisasi rekam medis.
     */
    public function show(string $id): View
    {
        $record = MedicalRecord::with(['patient.user', 'officer'])->findOrFail($id);

        return view('officers.medical_records.show', compact('record'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): View
    {
        $record = MedicalRecord::findOrFail($id);
        $patients = Patient::with('user')->get();

        return view('officers.medical_records.edit', compact('record', 'patients'));
    }

    /**
     * Update the specified resource in storage.
     *
     * Kebutuhan Praktikum:
     * 1. IDOR: Siapa saja petugas/pasien (jika middleware dilewati) bisa mengubah data rekam medis siapa pun.
     * 2. Stored XSS: Perubahan data disimpan langsung ke database tanpa sanitasi.
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        $record = MedicalRecord::findOrFail($id);

        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'visit_date' => 'required|date',
            'diagnosis' => 'required',
            'treatment' => 'required',
        ]);

        // Kebutuhan Praktikum: Mass assignment langsung dari $request->all()
        $record->update($request->all());

        return redirect()->route('medical-records.index')->with('success', 'Rekam medis berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * Kebutuhan Praktikum (IDOR):
     * Tidak ada validasi otorisasi sebelum penghapusan data secara permanen.
     */
    public function destroy(string $id): RedirectResponse
    {
        $record = MedicalRecord::findOrFail($id);
        $record->delete();

        return redirect()->route('medical-records.index')->with('success', 'Rekam medis berhasil dihapus.');
    }
}
