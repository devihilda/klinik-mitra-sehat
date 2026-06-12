<?php

namespace Tests\Feature;

use App\Models\Doctor;
use App\Models\DoctorSchedule;
use App\Models\Patient;
use App\Models\Polyclinic;
use App\Models\Queue;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QueueTest extends TestCase
{
    use RefreshDatabase;

    private User $officer;

    private User $patientUserA;

    private User $patientUserB;

    private Patient $patientA;

    private Patient $patientB;

    private Polyclinic $polyclinic;

    private Doctor $doctor;

    private DoctorSchedule $schedule;

    protected function setUp(): void
    {
        parent::setUp();

        // Create an officer
        $this->officer = User::factory()->create([
            'role' => 'petugas',
        ]);

        // Create patient user A
        $this->patientUserA = User::factory()->create([
            'role' => 'pasien',
        ]);
        $this->patientA = Patient::create([
            'user_id' => $this->patientUserA->id,
            'phone' => '081234567890',
            'gender' => 'laki-laki',
            'birth_date' => '1995-10-10',
            'address' => 'Jl. Mawar Merah No. 1',
        ]);

        // Create patient user B
        $this->patientUserB = User::factory()->create([
            'role' => 'pasien',
        ]);
        $this->patientB = Patient::create([
            'user_id' => $this->patientUserB->id,
            'phone' => '089876543210',
            'gender' => 'perempuan',
            'birth_date' => '1998-05-15',
            'address' => 'Jl. Melati Putih No. 2',
        ]);

        // Create a polyclinic
        $this->polyclinic = Polyclinic::create([
            'name' => 'Poli Mata',
            'description' => 'Poliklinik pelayanan kesehatan mata.',
        ]);

        // Create a doctor
        $this->doctor = Doctor::create([
            'name' => 'dr. Andika',
            'sip' => 'SIP/789/2026',
            'specialization' => 'Spesialis Mata',
            'polyclinic_id' => $this->polyclinic->id,
            'phone' => '081223344556',
            'status' => 'aktif',
        ]);

        // Create a doctor schedule with quota of 2
        $this->schedule = DoctorSchedule::create([
            'doctor_id' => $this->doctor->id,
            'polyclinic_id' => $this->polyclinic->id,
            'day' => 'Rabu',
            'start_time' => '13:00',
            'end_time' => '16:00',
            'quota' => 2,
            'is_active' => true,
        ]);
    }

    /**
     * Test Officer Queue CRUD.
     */
    public function test_officer_can_view_queues_index(): void
    {
        $response = $this
            ->actingAs($this->officer)
            ->get(route('queues.index'));

        $response->assertOk();
    }

    public function test_officer_can_view_create_queue_page(): void
    {
        $response = $this
            ->actingAs($this->officer)
            ->get(route('queues.create'));

        $response->assertOk();
    }

    public function test_officer_can_store_queue(): void
    {
        $response = $this
            ->actingAs($this->officer)
            ->post(route('queues.store'), [
                'patient_id' => $this->patientA->id,
                'doctor_schedule_id' => $this->schedule->id,
                'queue_date' => '2026-06-03',
                'complaint' => 'Mata sering berair dan gatal',
                'status' => 'menunggu',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('queues.index'));

        $this->assertDatabaseHas('queues', [
            'patient_id' => $this->patientA->id,
            'doctor_schedule_id' => $this->schedule->id,
            'poli_id' => $this->polyclinic->id,
            'doctor_id' => $this->doctor->id,
            'queue_date' => '2026-06-03',
            'queue_number' => 1,
            'complaint' => 'Mata sering berair dan gatal',
            'status' => 'menunggu',
        ]);
    }

    public function test_officer_can_view_queue_details(): void
    {
        $queue = Queue::create([
            'patient_id' => $this->patientA->id,
            'doctor_schedule_id' => $this->schedule->id,
            'poli_id' => $this->polyclinic->id,
            'doctor_id' => $this->doctor->id,
            'queue_date' => '2026-06-03',
            'queue_number' => 1,
            'complaint' => 'Mata merah',
            'status' => 'menunggu',
        ]);

        $response = $this
            ->actingAs($this->officer)
            ->get(route('queues.show', $queue->id));

        $response->assertOk();
        $response->assertSee('Mata merah');
    }

    public function test_officer_can_view_edit_queue_page(): void
    {
        $queue = Queue::create([
            'patient_id' => $this->patientA->id,
            'doctor_schedule_id' => $this->schedule->id,
            'poli_id' => $this->polyclinic->id,
            'doctor_id' => $this->doctor->id,
            'queue_date' => '2026-06-03',
            'queue_number' => 1,
            'complaint' => 'Mata merah',
            'status' => 'menunggu',
        ]);

        $response = $this
            ->actingAs($this->officer)
            ->get(route('queues.edit', $queue->id));

        $response->assertOk();
    }

    public function test_officer_can_update_queue(): void
    {
        $queue = Queue::create([
            'patient_id' => $this->patientA->id,
            'doctor_schedule_id' => $this->schedule->id,
            'poli_id' => $this->polyclinic->id,
            'doctor_id' => $this->doctor->id,
            'queue_date' => '2026-06-03',
            'queue_number' => 1,
            'complaint' => 'Mata merah',
            'status' => 'menunggu',
        ]);

        $response = $this
            ->actingAs($this->officer)
            ->put(route('queues.update', $queue->id), [
                'patient_id' => $this->patientA->id,
                'doctor_schedule_id' => $this->schedule->id,
                'queue_date' => '2026-06-03',
                'complaint' => 'Mata sangat merah dan perih',
                'status' => 'diperiksa',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('queues.index'));

        $this->assertDatabaseHas('queues', [
            'id' => $queue->id,
            'complaint' => 'Mata sangat merah dan perih',
            'status' => 'diperiksa',
        ]);
    }

    public function test_officer_can_delete_queue(): void
    {
        $queue = Queue::create([
            'patient_id' => $this->patientA->id,
            'doctor_schedule_id' => $this->schedule->id,
            'poli_id' => $this->polyclinic->id,
            'doctor_id' => $this->doctor->id,
            'queue_date' => '2026-06-03',
            'queue_number' => 1,
            'complaint' => 'Mata merah',
            'status' => 'menunggu',
        ]);

        $response = $this
            ->actingAs($this->officer)
            ->delete(route('queues.destroy', $queue->id));

        $response->assertRedirect(route('queues.index'));
        $this->assertDatabaseMissing('queues', ['id' => $queue->id]);
    }

    /**
     * Test auto-increment of queue number.
     */
    public function test_queue_numbers_increment_sequentially(): void
    {
        // First Queue
        $this->actingAs($this->officer)
            ->post(route('queues.store'), [
                'patient_id' => $this->patientA->id,
                'doctor_schedule_id' => $this->schedule->id,
                'queue_date' => '2026-06-03',
                'complaint' => 'Keluhan A',
            ]);

        // Second Queue
        $this->actingAs($this->officer)
            ->post(route('queues.store'), [
                'patient_id' => $this->patientB->id,
                'doctor_schedule_id' => $this->schedule->id,
                'queue_date' => '2026-06-03',
                'complaint' => 'Keluhan B',
            ]);

        $this->assertDatabaseHas('queues', [
            'patient_id' => $this->patientA->id,
            'queue_number' => 1,
        ]);

        $this->assertDatabaseHas('queues', [
            'patient_id' => $this->patientB->id,
            'queue_number' => 2,
        ]);
    }

    /**
     * Test Quota Limit enforcement.
     */
    public function test_cannot_register_queue_when_quota_is_exceeded(): void
    {
        // Fill up quota (quota is 2)
        Queue::create([
            'patient_id' => $this->patientA->id,
            'doctor_schedule_id' => $this->schedule->id,
            'poli_id' => $this->polyclinic->id,
            'doctor_id' => $this->doctor->id,
            'queue_date' => '2026-06-03',
            'queue_number' => 1,
            'status' => 'menunggu',
        ]);

        Queue::create([
            'patient_id' => $this->patientB->id,
            'doctor_schedule_id' => $this->schedule->id,
            'poli_id' => $this->polyclinic->id,
            'doctor_id' => $this->doctor->id,
            'queue_date' => '2026-06-03',
            'queue_number' => 2,
            'status' => 'menunggu',
        ]);

        // Try registering a third patient (should fail due to quota)
        $patientUserC = User::factory()->create(['role' => 'pasien']);
        $patientC = Patient::create([
            'user_id' => $patientUserC->id,
            'phone' => '083333333333',
            'gender' => 'laki-laki',
            'birth_date' => '1990-01-01',
            'address' => 'Jl. Ketiga No. 3',
        ]);

        $response = $this
            ->actingAs($this->officer)
            ->post(route('queues.store'), [
                'patient_id' => $patientC->id,
                'doctor_schedule_id' => $this->schedule->id,
                'queue_date' => '2026-06-03',
                'complaint' => 'Keluhan C',
            ]);

        $response->assertSessionHasErrors('queue_date');
        $this->assertDatabaseCount('queues', 2);
    }

    /**
     * Test Patient self-registration, index, show, and cancel.
     */
    public function test_patient_can_register_own_queue_and_view_own_data(): void
    {
        $response = $this
            ->actingAs($this->patientUserA)
            ->post(route('patients.queues.store'), [
                'doctor_schedule_id' => $this->schedule->id,
                'queue_date' => '2026-06-03',
                'complaint' => 'Sakit mata sebelah kiri',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('patients.queues.index'));

        $this->assertDatabaseHas('queues', [
            'patient_id' => $this->patientA->id,
            'complaint' => 'Sakit mata sebelah kiri',
            'status' => 'menunggu',
        ]);

        // Patient can view own queue list
        $responseIndex = $this
            ->actingAs($this->patientUserA)
            ->get(route('patients.queues.index'));

        $responseIndex->assertOk();
        $responseIndex->assertSee('Sakit mata sebelah kiri');

        // Patient can view own queue details
        $queue = Queue::where('patient_id', $this->patientA->id)->first();
        $responseShow = $this
            ->actingAs($this->patientUserA)
            ->get(route('patients.queues.show', $queue->id));

        $responseShow->assertOk();
        $responseShow->assertSee('Sakit mata sebelah kiri');

        // Patient can cancel own queue
        $responseDestroy = $this
            ->actingAs($this->patientUserA)
            ->delete(route('patients.queues.destroy', $queue->id));

        $responseDestroy->assertRedirect(route('patients.queues.index'));
        $this->assertDatabaseHas('queues', [
            'id' => $queue->id,
            'status' => 'batal',
        ]);
    }

    /**
     * Test prevention of IDOR on Patient Show.
     */
    public function test_patient_cannot_view_other_patients_queue_details(): void
    {
        // Patient B registers a queue
        $queueB = Queue::create([
            'patient_id' => $this->patientB->id,
            'doctor_schedule_id' => $this->schedule->id,
            'poli_id' => $this->polyclinic->id,
            'doctor_id' => $this->doctor->id,
            'queue_date' => '2026-06-03',
            'queue_number' => 1,
            'complaint' => 'Rahasia Rekam Medis Pasien B',
            'status' => 'menunggu',
        ]);

        // Patient A attempts to view Patient B's queue details directly via IDOR
        $response = $this
            ->actingAs($this->patientUserA)
            ->get(route('patients.queues.show', $queueB->id));

        // It should fail with 403 Forbidden
        $response->assertStatus(403);
    }

    /**
     * Test prevention of IDOR on Patient Destroy (Cancel).
     */
    public function test_patient_cannot_cancel_other_patients_queue(): void
    {
        // Patient B registers a queue
        $queueB = Queue::create([
            'patient_id' => $this->patientB->id,
            'doctor_schedule_id' => $this->schedule->id,
            'poli_id' => $this->polyclinic->id,
            'doctor_id' => $this->doctor->id,
            'queue_date' => '2026-06-03',
            'queue_number' => 1,
            'complaint' => 'Keluhan Pasien B',
            'status' => 'menunggu',
        ]);

        // Patient A attempts to cancel Patient B's queue directly via IDOR
        $response = $this
            ->actingAs($this->patientUserA)
            ->delete(route('patients.queues.destroy', $queueB->id));

        // It should fail with 403 Forbidden
        $response->assertStatus(403);
        $this->assertDatabaseHas('queues', [
            'id' => $queueB->id,
            'status' => 'menunggu',
        ]);
    }
}
