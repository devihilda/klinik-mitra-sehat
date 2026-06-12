<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\DoctorSchedule;
use App\Models\Queue;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class QueueController extends Controller
{
    /**
     * Display a listing of the patient's own queues.
     */
    public function index(): View
    {
        $patient = auth()->user()->patient;

        if (! $patient) {
            $queues = collect();
        } else {
            $queues = Queue::with(['doctorSchedule.doctor', 'polyclinic', 'doctor'])
                ->where('patient_id', $patient->id)
                ->orderBy('queue_date', 'desc')
                ->get();
        }

        return view('patients.queues.index', compact('queues'));
    }

    /**
     * Show the form for creating a new queue registration.
     */
    public function create(): View
    {
        $schedules = DoctorSchedule::with(['doctor', 'polyclinic'])->where('is_active', true)->get();

        return view('patients.queues.create', compact('schedules'));
    }

    /**
     * Store a newly created queue registration.
     */
    public function store(Request $request): RedirectResponse
    {
        $patient = auth()->user()->patient;

        if (! $patient) {
            return back()->withErrors(['patient' => 'Profil pasien tidak ditemukan. Silakan lengkapi profil Anda terlebih dahulu.']);
        }

        $request->validate([
            'doctor_schedule_id' => 'required|exists:doctor_schedules,id',
            'queue_date' => 'required|date',
            'complaint' => 'nullable|string',
        ]);

        $schedule = DoctorSchedule::findOrFail($request->doctor_schedule_id);

        if (! $schedule->is_active) {
            return back()->withErrors(['doctor_schedule_id' => 'Jadwal dokter yang dipilih sedang tidak aktif.'])->withInput();
        }

        // Check quota limit (excluding cancelled queues)
        $existingCount = Queue::where('doctor_schedule_id', $schedule->id)
            ->where('queue_date', $request->queue_date)
            ->where('status', '!=', 'batal')
            ->count();

        if ($existingCount >= $schedule->quota) {
            return back()->withErrors(['queue_date' => 'Kuota dokter untuk jadwal dan tanggal tersebut sudah penuh.'])->withInput();
        }

        // Automatically assign poli_id and doctor_id from schedule
        $poli_id = $schedule->polyclinic_id;
        $doctor_id = $schedule->doctor_id;

        // Auto-generate queue number
        $lastQueue = Queue::where('doctor_schedule_id', $schedule->id)
            ->where('queue_date', $request->queue_date)
            ->max('queue_number');
        $queueNumber = ($lastQueue ?? 0) + 1;

        Queue::create([
            'patient_id' => $patient->id,
            'doctor_schedule_id' => $schedule->id,
            'poli_id' => $poli_id,
            'doctor_id' => $doctor_id,
            'queue_date' => $request->queue_date,
            'queue_number' => $queueNumber,
            'complaint' => $request->complaint,
            'status' => 'menunggu',
        ]);

        return redirect()->route('patients.queues.index')->with('success', 'Pendaftaran antrean berhasil dilakukan.');
    }

    /**
     * Display the specified queue details.
     *
     * KEBUTUHAN PRAKTIKUM (IDOR Vulnerability):
     * Celah keamanan di mana controller langsung mengambil data Queue berdasarkan ID tanpa
     * memverifikasi apakah queue tersebut benar-benar milik pasien yang sedang login (patient_id).
     */
    public function show(string $id): View
    {
        $queue = Queue::with(['doctorSchedule.doctor', 'polyclinic', 'doctor'])->findOrFail($id);

        return view('patients.queues.show', compact('queue'));
    }

    /**
     * Cancel the specified queue.
     *
     * KEBUTUHAN PRAKTIKUM (IDOR Vulnerability):
     * Celah keamanan di mana pasien mana pun bisa membatalkan antrean pasien lain hanya
     * dengan mengetahui ID antrean tersebut melalui parameter request.
     */
    public function destroy(string $id): RedirectResponse
    {
        $queue = Queue::findOrFail($id);
        $queue->update(['status' => 'batal']);

        return redirect()->route('patients.queues.index')->with('success', 'Antrean berhasil dibatalkan.');
    }
}
