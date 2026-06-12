<?php

namespace Tests\Feature;

use App\Models\Doctor;
use App\Models\DoctorSchedule;
use App\Models\Polyclinic;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DoctorScheduleTest extends TestCase
{
    use RefreshDatabase;

    private User $officer;

    private Polyclinic $polyclinic;

    private Doctor $doctor;

    protected function setUp(): void
    {
        parent::setUp();

        // Create an officer
        $this->officer = User::factory()->create([
            'role' => 'petugas',
        ]);

        // Create a polyclinic
        $this->polyclinic = Polyclinic::create([
            'name' => 'Poli Umum',
            'description' => 'Poliklinik pelayanan kesehatan umum dasar.',
        ]);

        // Create a doctor
        $this->doctor = Doctor::create([
            'name' => 'dr. Budiman',
            'sip' => 'SIP/123/2026',
            'specialization' => 'Dokter Umum',
            'polyclinic_id' => $this->polyclinic->id,
            'phone' => '081122334455',
            'status' => 'aktif',
        ]);
    }

    public function test_officer_can_view_doctor_schedules_index(): void
    {
        $response = $this
            ->actingAs($this->officer)
            ->get(route('doctor-schedules.index'));

        $response->assertOk();
    }

    public function test_officer_can_view_create_doctor_schedule_page(): void
    {
        $response = $this
            ->actingAs($this->officer)
            ->get(route('doctor-schedules.create'));

        $response->assertOk();
    }

    public function test_officer_can_store_doctor_schedule(): void
    {
        $response = $this
            ->actingAs($this->officer)
            ->post(route('doctor-schedules.store'), [
                'doctor_id' => $this->doctor->id,
                'polyclinic_id' => $this->polyclinic->id,
                'day' => 'Senin',
                'start_time' => '08:00',
                'end_time' => '12:00',
                'quota' => 20,
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('doctor-schedules.index'));

        $this->assertDatabaseHas('doctor_schedules', [
            'doctor_id' => $this->doctor->id,
            'polyclinic_id' => $this->polyclinic->id,
            'day' => 'Senin',
            'start_time' => '08:00:00',
            'end_time' => '12:00:00',
            'quota' => 20,
            'is_active' => 1, // Default value
        ]);
    }

    public function test_officer_can_view_doctor_schedule_details(): void
    {
        $schedule = DoctorSchedule::create([
            'doctor_id' => $this->doctor->id,
            'polyclinic_id' => $this->polyclinic->id,
            'day' => 'Senin',
            'start_time' => '08:00',
            'end_time' => '12:00',
            'quota' => 20,
            'is_active' => true,
        ]);

        $response = $this
            ->actingAs($this->officer)
            ->get(route('doctor-schedules.show', $schedule->id));

        $response->assertOk();
        $response->assertSee('dr. Budiman');
        $response->assertSee('Senin');
    }

    public function test_officer_can_view_edit_doctor_schedule_page(): void
    {
        $schedule = DoctorSchedule::create([
            'doctor_id' => $this->doctor->id,
            'polyclinic_id' => $this->polyclinic->id,
            'day' => 'Senin',
            'start_time' => '08:00',
            'end_time' => '12:00',
            'quota' => 20,
            'is_active' => true,
        ]);

        $response = $this
            ->actingAs($this->officer)
            ->get(route('doctor-schedules.edit', $schedule->id));

        $response->assertOk();
    }

    public function test_officer_can_update_doctor_schedule(): void
    {
        $schedule = DoctorSchedule::create([
            'doctor_id' => $this->doctor->id,
            'polyclinic_id' => $this->polyclinic->id,
            'day' => 'Senin',
            'start_time' => '08:00',
            'end_time' => '12:00',
            'quota' => 20,
            'is_active' => true,
        ]);

        $response = $this
            ->actingAs($this->officer)
            ->put(route('doctor-schedules.update', $schedule->id), [
                'doctor_id' => $this->doctor->id,
                'polyclinic_id' => $this->polyclinic->id,
                'day' => 'Selasa',
                'start_time' => '09:00',
                'end_time' => '13:00',
                'quota' => 15,
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('doctor-schedules.index'));

        $this->assertDatabaseHas('doctor_schedules', [
            'id' => $schedule->id,
            'day' => 'Selasa',
            'start_time' => '09:00:00',
            'end_time' => '13:00:00',
            'quota' => 15,
        ]);
    }

    public function test_officer_can_delete_doctor_schedule(): void
    {
        $schedule = DoctorSchedule::create([
            'doctor_id' => $this->doctor->id,
            'polyclinic_id' => $this->polyclinic->id,
            'day' => 'Senin',
            'start_time' => '08:00',
            'end_time' => '12:00',
            'quota' => 20,
            'is_active' => true,
        ]);

        $response = $this
            ->actingAs($this->officer)
            ->delete(route('doctor-schedules.destroy', $schedule->id));

        $response->assertRedirect(route('doctor-schedules.index'));
        $this->assertDatabaseMissing('doctor_schedules', ['id' => $schedule->id]);
    }

    public function test_officer_can_exploit_mass_assignment_on_doctor_schedule(): void
    {
        // Attacker sends extra unvalidated param: 'is_active' => false.
        // Because of raw $request->all() injection inside Controller, is_active is modified directly.
        $response = $this
            ->actingAs($this->officer)
            ->post(route('doctor-schedules.store'), [
                'doctor_id' => $this->doctor->id,
                'polyclinic_id' => $this->polyclinic->id,
                'day' => 'Kamis',
                'start_time' => '14:00',
                'end_time' => '17:00',
                'quota' => 30,
                'is_active' => false, // Injeksikan is_active false secara Mass Assignment
            ]);

        $response->assertSessionHasNoErrors();

        // Verifikasi bahwa is_active berhasil dimanipulasi menjadi false
        $this->assertDatabaseHas('doctor_schedules', [
            'doctor_id' => $this->doctor->id,
            'day' => 'Kamis',
            'is_active' => 0, // Terbukti rentan Mass Assignment!
        ]);
    }
}
