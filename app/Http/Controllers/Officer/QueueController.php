<?php

namespace App\Http\Controllers\Officer;

use App\Http\Controllers\Controller;
use App\Models\DoctorSchedule;
use App\Models\Patient;
use App\Models\Queue;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class QueueController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = Queue::with(['patient.user', 'doctorSchedule.doctor', 'polyclinic', 'doctor']);

        // Optional filtering by date or status
        if ($request->filled('queue_date')) {
            $query->where('queue_date', $request->queue_date);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $queues = $query->orderBy('queue_date', 'desc')
            ->orderBy('queue_number', 'asc')
            ->get();

        return view('officers.queues.index', compact('queues'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $patients = Patient::with('user')->get();
        $schedules = DoctorSchedule::with(['doctor', 'polyclinic'])->where('is_active', true)->get();

        return view('officers.queues.create', compact('patients', 'schedules'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_schedule_id' => 'required|exists:doctor_schedules,id',
            'queue_date' => 'required|date',
            'complaint' => 'nullable|string',
            'status' => 'nullable|string|in:menunggu,diperiksa,selesai,batal',
        ]);

        $schedule = DoctorSchedule::findOrFail($request->doctor_schedule_id);

        // Check if doctor schedule is active
        if (! $schedule->is_active) {
            return back()->withErrors(['doctor_schedule_id' => 'Jadwal dokter yang dipilih sedang tidak aktif.'])->withInput();
        }

        // Check doctor quota limit (excluding cancelled queues)
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

        // Auto-generate queue number for the specific schedule and date
        $lastQueue = Queue::where('doctor_schedule_id', $schedule->id)
            ->where('queue_date', $request->queue_date)
            ->max('queue_number');
        $queueNumber = ($lastQueue ?? 0) + 1;

        Queue::create([
            'patient_id' => $request->patient_id,
            'doctor_schedule_id' => $schedule->id,
            'poli_id' => $poli_id,
            'doctor_id' => $doctor_id,
            'queue_date' => $request->queue_date,
            'queue_number' => $queueNumber,
            'complaint' => $request->complaint,
            'status' => $request->input('status', 'menunggu'),
        ]);

        return redirect()->route('queues.index')->with('success', 'Antrean berhasil didaftarkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): View
    {
        $queue = Queue::with(['patient.user', 'doctorSchedule.doctor', 'polyclinic', 'doctor'])->findOrFail($id);

        return view('officers.queues.show', compact('queue'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): View
    {
        $queue = Queue::findOrFail($id);
        $patients = Patient::with('user')->get();
        $schedules = DoctorSchedule::with(['doctor', 'polyclinic'])->where('is_active', true)->get();

        return view('officers.queues.edit', compact('queue', 'patients', 'schedules'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_schedule_id' => 'required|exists:doctor_schedules,id',
            'queue_date' => 'required|date',
            'complaint' => 'nullable|string',
            'status' => 'required|string|in:menunggu,diperiksa,selesai,batal',
        ]);

        $queue = Queue::findOrFail($id);
        $schedule = DoctorSchedule::findOrFail($request->doctor_schedule_id);

        // If changing schedule or date, check quota
        if ($queue->doctor_schedule_id != $schedule->id || $queue->queue_date != $request->queue_date) {
            $existingCount = Queue::where('doctor_schedule_id', $schedule->id)
                ->where('queue_date', $request->queue_date)
                ->where('status', '!=', 'batal')
                ->count();

            if ($existingCount >= $schedule->quota) {
                return back()->withErrors(['queue_date' => 'Kuota dokter untuk jadwal dan tanggal tersebut sudah penuh.'])->withInput();
            }

            // Regeneate queue number for the new schedule and date
            $lastQueue = Queue::where('doctor_schedule_id', $schedule->id)
                ->where('queue_date', $request->queue_date)
                ->max('queue_number');
            $queueNumber = ($lastQueue ?? 0) + 1;

            $queue->queue_number = $queueNumber;
        }

        $queue->update([
            'patient_id' => $request->patient_id,
            'doctor_schedule_id' => $schedule->id,
            'poli_id' => $schedule->polyclinic_id,
            'doctor_id' => $schedule->doctor_id,
            'queue_date' => $request->queue_date,
            'complaint' => $request->complaint,
            'status' => $request->status,
        ]);

        return redirect()->route('queues.index')->with('success', 'Antrean berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): RedirectResponse
    {
        $queue = Queue::findOrFail($id);
        $queue->delete();

        return redirect()->route('queues.index')->with('success', 'Antrean berhasil dihapus.');
    }
}
