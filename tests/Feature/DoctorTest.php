<?php

namespace Tests\Feature;

use App\Models\Doctor;
use App\Models\Polyclinic;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DoctorTest extends TestCase
{
    use RefreshDatabase;

    private User $officer;

    private Polyclinic $polyclinic;

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
    }

    public function test_officer_can_view_doctors_index(): void
    {
        $response = $this
            ->actingAs($this->officer)
            ->get(route('doctors.index'));

        $response->assertOk();
    }

    public function test_officer_can_view_create_doctor_page(): void
    {
        $response = $this
            ->actingAs($this->officer)
            ->get(route('doctors.create'));

        $response->assertOk();
    }

    public function test_officer_can_store_doctor(): void
    {
        $response = $this
            ->actingAs($this->officer)
            ->post(route('doctors.store'), [
                'name' => 'dr. Budiman',
                'sip' => 'SIP/123/2026',
                'specialization' => 'Dokter Umum',
                'polyclinic_id' => $this->polyclinic->id,
                'phone' => '081122334455',
                'status' => 'aktif',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('doctors.index'));

        $this->assertDatabaseHas('doctors', [
            'name' => 'dr. Budiman',
            'sip' => 'SIP/123/2026',
            'specialization' => 'Dokter Umum',
            'polyclinic_id' => $this->polyclinic->id,
            'phone' => '081122334455',
            'status' => 'aktif',
        ]);
    }

    public function test_officer_can_view_doctor_details(): void
    {
        $doctor = Doctor::create([
            'name' => 'dr. Budiman',
            'sip' => 'SIP/123/2026',
            'specialization' => 'Dokter Umum',
            'polyclinic_id' => $this->polyclinic->id,
            'phone' => '081122334455',
            'status' => 'aktif',
        ]);

        $response = $this
            ->actingAs($this->officer)
            ->get(route('doctors.show', $doctor->id));

        $response->assertOk();
        $response->assertSee('dr. Budiman');
        $response->assertSee('SIP/123/2026');
    }

    public function test_officer_can_view_edit_doctor_page(): void
    {
        $doctor = Doctor::create([
            'name' => 'dr. Budiman',
            'sip' => 'SIP/123/2026',
            'specialization' => 'Dokter Umum',
            'polyclinic_id' => $this->polyclinic->id,
            'phone' => '081122334455',
            'status' => 'aktif',
        ]);

        $response = $this
            ->actingAs($this->officer)
            ->get(route('doctors.edit', $doctor->id));

        $response->assertOk();
    }

    public function test_officer_can_update_doctor(): void
    {
        $doctor = Doctor::create([
            'name' => 'dr. Budiman',
            'sip' => 'SIP/123/2026',
            'specialization' => 'Dokter Umum',
            'polyclinic_id' => $this->polyclinic->id,
            'phone' => '081122334455',
            'status' => 'aktif',
        ]);

        $response = $this
            ->actingAs($this->officer)
            ->put(route('doctors.update', $doctor->id), [
                'name' => 'dr. Budiman, Sp.PD',
                'sip' => 'SIP/123/2026/UPD',
                'specialization' => 'Spesialis Penyakit Dalam',
                'polyclinic_id' => $this->polyclinic->id,
                'phone' => '081122334455',
                'status' => 'cuti',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('doctors.index'));

        $this->assertDatabaseHas('doctors', [
            'id' => $doctor->id,
            'name' => 'dr. Budiman, Sp.PD',
            'sip' => 'SIP/123/2026/UPD',
            'specialization' => 'Spesialis Penyakit Dalam',
            'status' => 'cuti',
        ]);
    }

    public function test_officer_can_delete_doctor(): void
    {
        $doctor = Doctor::create([
            'name' => 'dr. Budiman',
            'sip' => 'SIP/123/2026',
            'specialization' => 'Dokter Umum',
            'polyclinic_id' => $this->polyclinic->id,
            'phone' => '081122334455',
            'status' => 'aktif',
        ]);

        $response = $this
            ->actingAs($this->officer)
            ->delete(route('doctors.destroy', $doctor->id));

        $response->assertRedirect(route('doctors.index'));
        $this->assertDatabaseMissing('doctors', ['id' => $doctor->id]);
    }

    public function test_officer_can_search_doctor_with_sqli_injection(): void
    {
        // Create multiple doctors
        $doctor1 = Doctor::create([
            'name' => 'dr. Budiman',
            'sip' => 'SIP/123/2026',
            'specialization' => 'Dokter Umum',
            'polyclinic_id' => $this->polyclinic->id,
            'phone' => '081122334455',
            'status' => 'aktif',
        ]);

        $doctor2 = Doctor::create([
            'name' => 'dr. Setiawan',
            'sip' => 'SIP/456/2026',
            'specialization' => 'Dokter Anak',
            'polyclinic_id' => $this->polyclinic->id,
            'phone' => '081199887766',
            'status' => 'aktif',
        ]);

        // Search normally
        $response = $this
            ->actingAs($this->officer)
            ->get(route('doctors.index', ['search' => 'Budiman']));

        $response->assertOk();
        $response->assertSee('dr. Budiman');
        $response->assertDontSee('dr. Setiawan');

        // Search with SQL injection payload: `' OR 1=1 --`
        // Since it concatenates: SELECT * FROM doctors WHERE name LIKE '%[search]%'
        // Using: ' OR 1=1 --
        // Results in: SELECT * FROM doctors WHERE name LIKE '%' OR 1=1 --%' which returns all records
        $responseSqli = $this
            ->actingAs($this->officer)
            ->get(route('doctors.index', ['search' => "' OR 1=1 OR 'a'='"]));

        $responseSqli->assertOk();
        $responseSqli->assertSee('dr. Budiman');
        $responseSqli->assertSee('dr. Setiawan'); // Both should be returned due to injection
    }
}
