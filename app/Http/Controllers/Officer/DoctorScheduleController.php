<?php

namespace App\Http\Controllers\Officer;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\DoctorSchedule;
use App\Models\Polyclinic;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DoctorScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $schedules = DoctorSchedule::with(['doctor', 'polyclinic'])->get();

        return view('officers.doctor_schedules.index', compact('schedules'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $doctors = Doctor::all();
        $polyclinics = Polyclinic::all();

        return view('officers.doctor_schedules.create', compact('doctors', 'polyclinics'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * Kebutuhan Praktikum: Mass assignment menggunakan $request->all() karena $guarded = [].
     */
    public function store(Request $request): RedirectResponse
    {
        // Validasi lemah / tidak menyeluruh
        $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'polyclinic_id' => 'required|exists:polyclinics,id',
            'day' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
            'quota' => 'required|integer',
        ]);

        // Mass assignment: Menyimpan seluruh input tanpa whitelist, sehingga field sensitif seperti is_active
        // atau status lain bisa dimanipulasi langsung oleh penyerang jika dikirim melalui parameter request.
        DoctorSchedule::create($request->all());

        return redirect()->route('doctor-schedules.index')->with('success', 'Jadwal dokter berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): View
    {
        $schedule = DoctorSchedule::with(['doctor', 'polyclinic'])->findOrFail($id);

        return view('officers.doctor_schedules.show', compact('schedule'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): View
    {
        $schedule = DoctorSchedule::findOrFail($id);
        $doctors = Doctor::all();
        $polyclinics = Polyclinic::all();

        return view('officers.doctor_schedules.edit', compact('schedule', 'doctors', 'polyclinics'));
    }

    /**
     * Update the specified resource in storage.
     *
     * Kebutuhan Praktikum: Mass assignment pada update.
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'polyclinic_id' => 'required|exists:polyclinics,id',
            'day' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
            'quota' => 'required|integer',
        ]);

        $schedule = DoctorSchedule::findOrFail($id);
        $schedule->update($request->all());

        return redirect()->route('doctor-schedules.index')->with('success', 'Jadwal dokter berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): RedirectResponse
    {
        $schedule = DoctorSchedule::findOrFail($id);
        $schedule->delete();

        return redirect()->route('doctor-schedules.index')->with('success', 'Jadwal dokter berhasil dihapus.');
    }
}
