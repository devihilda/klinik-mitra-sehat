<?php

namespace Tests\Feature;

use App\Models\MedicalRecord;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MedicalRecordTest extends TestCase
{
    use RefreshDatabase;

    private User $officer;

    private Patient $patient;

    protected function setUp(): void
    {
        parent::setUp();

        // Create an officer
        $this->officer = User::factory()->create([
            'role' => 'petugas',
        ]);

        // Create a patient user and details
        $patientUser = User::factory()->create([
            'role' => 'pasien',
        ]);

        $this->patient = Patient::create([
            'user_id' => $patientUser->id,
            'phone' => '081234567890',
            'gender' => 'laki-laki',
            'birth_date' => '1995-10-10',
            'address' => 'Jl. Kebagusan Raya No. 4',
        ]);
    }

    public function test_officer_can_view_medical_records_index(): void
    {
        $response = $this
            ->actingAs($this->officer)
            ->get(route('medical-records.index'));

        $response->assertOk();
    }

    public function test_officer_can_view_create_medical_record_page(): void
    {
        $response = $this
            ->actingAs($this->officer)
            ->get(route('medical-records.create'));

        $response->assertOk();
    }

    public function test_officer_can_store_medical_record(): void
    {
        $response = $this
            ->actingAs($this->officer)
            ->post(route('medical-records.store'), [
                'patient_id' => $this->patient->id,
                'visit_date' => '2026-05-29',
                'diagnosis' => 'Flu Ringan dan Batuk',
                'treatment' => 'Paracetamol 500mg, Ambroxol syrup',
                'notes' => 'Istirahat cukup 3 hari',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('medical-records.index'));

        $this->assertDatabaseHas('medical_records', [
            'patient_id' => $this->patient->id,
            'officer_id' => $this->officer->id,
            'visit_date' => '2026-05-29',
            'diagnosis' => 'Flu Ringan dan Batuk',
            'treatment' => 'Paracetamol 500mg, Ambroxol syrup',
            'notes' => 'Istirahat cukup 3 hari',
        ]);
    }

    public function test_officer_can_view_medical_record_details(): void
    {
        $record = MedicalRecord::create([
            'patient_id' => $this->patient->id,
            'officer_id' => $this->officer->id,
            'visit_date' => '2026-05-29',
            'diagnosis' => 'Sakit Kepala',
            'treatment' => 'Asam Mefenamat',
        ]);

        $response = $this
            ->actingAs($this->officer)
            ->get(route('medical-records.show', $record->id));

        $response->assertOk();
        $response->assertSee('Sakit Kepala');
        $response->assertSee('Asam Mefenamat');
    }

    public function test_officer_can_view_edit_medical_record_page(): void
    {
        $record = MedicalRecord::create([
            'patient_id' => $this->patient->id,
            'officer_id' => $this->officer->id,
            'visit_date' => '2026-05-29',
            'diagnosis' => 'Sakit Kepala',
            'treatment' => 'Asam Mefenamat',
        ]);

        $response = $this
            ->actingAs($this->officer)
            ->get(route('medical-records.edit', $record->id));

        $response->assertOk();
    }

    public function test_officer_can_update_medical_record(): void
    {
        $record = MedicalRecord::create([
            'patient_id' => $this->patient->id,
            'officer_id' => $this->officer->id,
            'visit_date' => '2026-05-29',
            'diagnosis' => 'Sakit Kepala',
            'treatment' => 'Asam Mefenamat',
        ]);

        $response = $this
            ->actingAs($this->officer)
            ->put(route('medical-records.update', $record->id), [
                'patient_id' => $this->patient->id,
                'visit_date' => '2026-05-29',
                'diagnosis' => 'Sakit Kepala Migrain',
                'treatment' => 'Paracetamol',
                'notes' => 'Kontrol 1 minggu lagi',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('medical-records.index'));

        $this->assertDatabaseHas('medical_records', [
            'id' => $record->id,
            'diagnosis' => 'Sakit Kepala Migrain',
            'treatment' => 'Paracetamol',
            'notes' => 'Kontrol 1 minggu lagi',
        ]);
    }

    public function test_officer_can_delete_medical_record(): void
    {
        $record = MedicalRecord::create([
            'patient_id' => $this->patient->id,
            'officer_id' => $this->officer->id,
            'visit_date' => '2026-05-29',
            'diagnosis' => 'Sakit Kepala',
            'treatment' => 'Asam Mefenamat',
        ]);

        $response = $this
            ->actingAs($this->officer)
            ->delete(route('medical-records.destroy', $record->id));

        $response->assertRedirect(route('medical-records.index'));
        $this->assertDatabaseMissing('medical_records', ['id' => $record->id]);
    }
}
