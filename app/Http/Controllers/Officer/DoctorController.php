<?php

namespace App\Http\Controllers\Officer;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Polyclinic;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DoctorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * Kebutuhan Praktikum: Fitur pencarian rentan terhadap SQL Injection (SQLi).
     */
    public function index(Request $request): View
    {
        $search = $request->input('search');

        if ($search) {
            // [KERENTANAN - SQL Injection]: Penggabungan string mentah ke dalam query SQL tanpa parameter binding
            $doctorsRaw = DB::select("SELECT * FROM doctors WHERE name LIKE '%".$search."%'");

            // Konversi raw array of stdClass menjadi Eloquent Collection dan load relasi
            $doctors = Doctor::hydrate($doctorsRaw);
            $doctors->load('polyclinic');
        } else {
            $doctors = Doctor::with('polyclinic')->get();
        }

        return view('officers.doctors.index', compact('doctors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $polyclinics = Polyclinic::all();

        return view('officers.doctors.create', compact('polyclinics'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * Kebutuhan Praktikum: Mass assignment menggunakan $request->all() karena $guarded = [].
     */
    public function store(Request $request): RedirectResponse
    {
        // Validasi lemah
        $request->validate([
            'name' => 'required',
            'sip' => 'required',
            'specialization' => 'required',
            'polyclinic_id' => 'required|exists:polyclinics,id',
            'phone' => 'required',
            'status' => 'required',
        ]);

        // Mass assignment
        Doctor::create($request->all());

        return redirect()->route('doctors.index')->with('success', 'Dokter berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): View
    {
        $doctor = Doctor::with('polyclinic')->findOrFail($id);

        return view('officers.doctors.show', compact('doctor'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): View
    {
        $doctor = Doctor::findOrFail($id);
        $polyclinics = Polyclinic::all();

        return view('officers.doctors.edit', compact('doctor', 'polyclinics'));
    }

    /**
     * Update the specified resource in storage.
     *
     * Kebutuhan Praktikum: Mass assignment pada update.
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        $request->validate([
            'name' => 'required',
            'sip' => 'required',
            'specialization' => 'required',
            'polyclinic_id' => 'required|exists:polyclinics,id',
            'phone' => 'required',
            'status' => 'required',
        ]);

        $doctor = Doctor::findOrFail($id);
        $doctor->update($request->all());

        return redirect()->route('doctors.index')->with('success', 'Data dokter berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): RedirectResponse
    {
        $doctor = Doctor::findOrFail($id);
        $doctor->delete();

        return redirect()->route('doctors.index')->with('success', 'Dokter berhasil dihapus.');
    }
}
