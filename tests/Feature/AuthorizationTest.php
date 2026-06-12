<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Patient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthorizationTest extends TestCase
{
    use RefreshDatabase;

    private User $patientUser;
    private User $officerUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->patientUser = User::factory()->create(['role' => 'pasien']);
        Patient::create([
            'user_id' => $this->patientUser->id,
            'phone' => '08123456789',
            'gender' => 'laki-laki',
            'birth_date' => '1995-01-01',
            'address' => 'Test Address',
        ]);

        $this->officerUser = User::factory()->create(['role' => 'petugas']);
    }

    public function test_guest_cannot_access_patient_routes(): void
    {
        $response = $this->get(route('patients.dashboard'));
        $response->assertRedirect(route('login'));

        $responseQueue = $this->get(route('patients.queues.index'));
        $responseQueue->assertRedirect(route('login'));
    }

    public function test_guest_cannot_access_officer_routes(): void
    {
        $response = $this->get(route('officers.dashboard'));
        $response->assertRedirect(route('login'));

        $responsePatients = $this->get(route('patients.index'));
        $responsePatients->assertRedirect(route('login'));
    }

    public function test_patient_cannot_access_officer_routes(): void
    {
        $response = $this->actingAs($this->patientUser)->get(route('officers.dashboard'));
        $response->assertStatus(403);

        $responsePatients = $this->actingAs($this->patientUser)->get(route('patients.index'));
        $responsePatients->assertStatus(403);
    }

    public function test_officer_cannot_access_patient_routes(): void
    {
        $response = $this->actingAs($this->officerUser)->get(route('patients.dashboard'));
        $response->assertStatus(403);

        $responseQueues = $this->actingAs($this->officerUser)->get(route('patients.queues.index'));
        $responseQueues->assertStatus(403);
    }
}
