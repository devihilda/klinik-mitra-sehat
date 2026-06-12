<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register_safely(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test Patient User',
            'email' => 'patient@example.com',
            'password' => 'SecurePassword123!',
            'password_confirmation' => 'SecurePassword123!',
            'phone' => '081234567890',
            'gender' => 'laki-laki',
            'birth_date' => '1995-10-10',
            'address' => 'Jl. Kebon Jeruk No. 22',
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));

        // Assert database record exists
        $user = User::where('email', 'patient@example.com')->first();
        $this->assertNotNull($user);

        // Assert password is encrypted and NOT plaintext
        $this->assertNotEquals('SecurePassword123!', $user->password);
        $this->assertTrue(Hash::check('SecurePassword123!', $user->password));

        // Assert role is strictly 'pasien'
        $this->assertEquals('pasien', $user->role);

        // Assert patient profile was created and fields populated correctly
        $this->assertNotNull($user->patient);
        $this->assertEquals('081234567890', $user->patient->phone);
        $this->assertEquals('laki-laki', $user->patient->gender);
        $this->assertEquals('1995-10-10', $user->patient->birth_date);
        $this->assertEquals('Jl. Kebon Jeruk No. 22', $user->patient->address);
    }

    public function test_registration_prevents_duplicate_email(): void
    {
        // Create an existing user
        User::factory()->create([
            'email' => 'duplicate@example.com',
        ]);

        // Try registering with the same email
        $response = $this->post('/register', [
            'name' => 'Another User',
            'email' => 'duplicate@example.com',
            'password' => 'SecurePassword123!',
            'password_confirmation' => 'SecurePassword123!',
            'phone' => '08122222222',
            'gender' => 'perempuan',
            'birth_date' => '1990-05-05',
            'address' => 'Jl. Kebayoran No. 5',
        ]);

        // Expect validation errors, not a 500 error
        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }

    public function test_registration_requires_password_confirmation(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'mismatch@example.com',
            'password' => 'SecurePassword123!',
            'password_confirmation' => 'DifferentPassword123!',
            'phone' => '081234567890',
            'gender' => 'laki-laki',
            'birth_date' => '1995-10-10',
            'address' => 'Jl. Kebon Jeruk No. 22',
        ]);

        $response->assertSessionHasErrors(['password']);
        $this->assertGuest();
    }

    public function test_registration_enforces_password_minimum_length(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'short@example.com',
            'password' => 'short',
            'password_confirmation' => 'short',
            'phone' => '081234567890',
            'gender' => 'laki-laki',
            'birth_date' => '1995-10-10',
            'address' => 'Jl. Kebon Jeruk No. 22',
        ]);

        $response->assertSessionHasErrors(['password']);
        $this->assertGuest();
    }

    public function test_registration_prevents_role_escalation(): void
    {
        // Try registering as a 'petugas' via mass assignment / param tampering
        $response = $this->post('/register', [
            'name' => 'Attacker',
            'email' => 'attacker@example.com',
            'password' => 'SecurePassword123!',
            'password_confirmation' => 'SecurePassword123!',
            'phone' => '081299999999',
            'gender' => 'laki-laki',
            'birth_date' => '1992-02-02',
            'address' => 'Jl. Dark Web No. 1',
            'role' => 'petugas', // Attempting privilege escalation
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertAuthenticated();

        $user = User::where('email', 'attacker@example.com')->first();
        $this->assertNotNull($user);
        // Role MUST still be 'pasien'
        $this->assertEquals('pasien', $user->role);
    }
}
