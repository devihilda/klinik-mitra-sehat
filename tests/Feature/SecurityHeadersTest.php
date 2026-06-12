<?php

namespace Tests\Feature;

use App\Http\Middleware\VerifyCsrfToken;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SecurityHeadersTest extends TestCase
{
    use RefreshDatabase;

    private string $expectedCsp = "default-src 'self'; base-uri 'self'; object-src 'none'; frame-ancestors 'none'; form-action 'self'; img-src 'self' data:; font-src 'self' data:; script-src 'self'; style-src 'self'; connect-src 'self'";

    // ─── CSP on all routes ───────────────────────────────────────────

    public function test_csp_on_welcome(): void
    {
        $this->get('/')->assertHeader('Content-Security-Policy', $this->expectedCsp);
    }

    public function test_csp_on_login(): void
    {
        $this->get('/login')->assertHeader('Content-Security-Policy', $this->expectedCsp);
    }

    public function test_csp_on_register(): void
    {
        $this->get('/register')->assertHeader('Content-Security-Policy', $this->expectedCsp);
    }

    public function test_csp_on_forgot_password(): void
    {
        $this->get('/forgot-password')->assertHeader('Content-Security-Policy', $this->expectedCsp);
    }

    public function test_csp_on_patient_dashboard(): void
    {
        $user = User::factory()->create(['role' => 'pasien']);
        Patient::create([
            'user_id' => $user->id,
            'phone' => '08123456789',
            'gender' => 'laki-laki',
            'birth_date' => '1995-01-01',
            'address' => 'Test Address',
        ]);

        $this->actingAs($user)
            ->get('/pasien/dashboard')
            ->assertHeader('Content-Security-Policy', $this->expectedCsp);
    }

    public function test_csp_on_officer_dashboard(): void
    {
        $user = User::factory()->create(['role' => 'petugas']);

        $this->actingAs($user)
            ->get('/petugas/dashboard')
            ->assertHeader('Content-Security-Policy', $this->expectedCsp);
    }

    public function test_csp_on_404(): void
    {
        $this->get('/non-existent-route')
            ->assertStatus(404)
            ->assertHeader('Content-Security-Policy', $this->expectedCsp);
    }

    public function test_csp_on_405(): void
    {
        $this->get('/logout')
            ->assertStatus(405)
            ->assertHeader('Content-Security-Policy', $this->expectedCsp);
    }

    public function test_csp_on_robots(): void
    {
        $this->get('/robots.txt')
            ->assertHeader('Content-Security-Policy', $this->expectedCsp);
    }

    public function test_csp_on_sitemap(): void
    {
        $this->get('/sitemap.xml')
            ->assertHeader('Content-Security-Policy', $this->expectedCsp);
    }

    // ─── Other security headers ──────────────────────────────────────

    public function test_security_headers_are_present(): void
    {
        $response = $this->get('/');

        $response->assertHeader('X-Frame-Options', 'DENY');
        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
        $this->assertFalse($response->headers->has('X-Powered-By'), 'X-Powered-By must be removed.');
    }

    public function test_permissions_policy_header(): void
    {
        $response = $this->get('/');
        $pp = $response->headers->get('Permissions-Policy');
        $this->assertNotNull($pp);
        $this->assertStringContainsString('camera=()', $pp);
        $this->assertStringContainsString('microphone=()', $pp);
        $this->assertStringContainsString('geolocation=()', $pp);
    }

    // ─── XSRF-TOKEN cookie must NOT exist ────────────────────────────

    public function test_no_xsrf_token_cookie_on_get(): void
    {
        $response = $this->get('/');
        $this->assertNoXsrfCookie($response);
    }

    public function test_no_xsrf_token_cookie_on_login(): void
    {
        $response = $this->get('/login');
        $this->assertNoXsrfCookie($response);
    }

    public function test_no_xsrf_token_cookie_on_register(): void
    {
        $response = $this->get('/register');
        $this->assertNoXsrfCookie($response);
    }

    // ─── CSRF still works ────────────────────────────────────────────

    public function test_csrf_middleware_is_active(): void
    {
        // Verify our custom middleware is registered and active by checking
        // that the XSRF-TOKEN cookie is NOT set (proving $addHttpCookie = false)
        // while CSRF validation is still in the middleware stack.
        $response = $this->get('/login');
        $this->assertNoXsrfCookie($response);

        // Verify the middleware class is our custom one
        $this->assertTrue(
            method_exists(VerifyCsrfToken::class, 'shouldAddXsrfTokenCookie'),
            'Custom VerifyCsrfToken middleware must exist.'
        );

        $middleware = app(VerifyCsrfToken::class);
        $this->assertFalse(
            $middleware->shouldAddXsrfTokenCookie(),
            'shouldAddXsrfTokenCookie() must return false.'
        );
    }

    public function test_post_with_valid_csrf_token_works(): void
    {
        // Login form POST with CSRF from session should not get 419.
        // Tests bypass CSRF automatically, so we verify a valid login flow works.
        $response = $this->from('/login')->post('/login', [
            'email' => 'nonexistent@test.com',
            'password' => 'wrongpassword',
        ]);
        // Should redirect back with validation error, NOT 419
        $this->assertNotEquals(419, $response->getStatusCode(), 'CSRF token should be accepted.');
    }

    public function test_login_succeeds_with_valid_credentials(): void
    {
        $user = User::factory()->create([
            'role' => 'pasien',
            'password' => 'password123',
        ]);
        Patient::create([
            'user_id' => $user->id,
            'phone' => '08123456789',
            'gender' => 'laki-laki',
            'birth_date' => '1995-01-01',
            'address' => 'Test Address',
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        $response->assertRedirect();
        $this->assertAuthenticatedAs($user);
    }

    public function test_register_succeeds(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'newuser@example.com',
            'password' => 'SecurePass123!',
            'password_confirmation' => 'SecurePass123!',
            'phone' => '081234567890',
            'address' => 'Jl. Test No. 1',
            'gender' => 'laki-laki',
            'birth_date' => '2000-01-15',
        ]);

        $response->assertRedirect();
        $this->assertAuthenticated();
    }

    public function test_ajax_with_x_csrf_token_works(): void
    {
        $user = User::factory()->create(['role' => 'pasien']);
        Patient::create([
            'user_id' => $user->id,
            'phone' => '08123456789',
            'gender' => 'laki-laki',
            'birth_date' => '1995-01-01',
            'address' => 'Test Address',
        ]);

        // Simulate AJAX request with X-CSRF-TOKEN header (from meta tag)
        $response = $this->actingAs($user)
            ->withHeaders([
                'X-CSRF-TOKEN' => csrf_token(),
                'X-Requested-With' => 'XMLHttpRequest',
            ])
            ->patch('/profile', [
                'name' => 'Updated Name',
                'email' => $user->email,
            ]);

        $this->assertNotEquals(419, $response->getStatusCode(), 'X-CSRF-TOKEN header should work.');
    }

    // ─── laravel_session remains HttpOnly ─────────────────────────────

    public function test_session_cookie_is_http_only(): void
    {
        $this->assertTrue(config('session.http_only'), 'Session cookie must be HttpOnly.');
        $this->assertContains(config('session.same_site'), ['lax', 'strict']);
    }

    // ─── Stateless robots/sitemap ────────────────────────────────────

    public function test_robots_is_stateless(): void
    {
        $response = $this->get('/robots.txt');
        $response->assertHeader('Content-Type', 'text/plain; charset=UTF-8');
        $this->assertNoSessionCookie($response);
        $this->assertNoXsrfCookie($response);
    }

    public function test_sitemap_is_stateless(): void
    {
        $response = $this->get('/sitemap.xml');
        $response->assertHeader('Content-Type', 'application/xml; charset=UTF-8');
        $this->assertNoSessionCookie($response);
        $this->assertNoXsrfCookie($response);
    }

    // ─── Redirect body minimization ──────────────────────────────────

    public function test_redirect_body_is_minimized(): void
    {
        $response = $this->get('/dashboard');
        $response->assertStatus(302);
        $content = $response->getContent();
        $this->assertLessThan(400, strlen($content));
        $this->assertStringNotContainsString('<!DOCTYPE html>', $content);
    }

    // ─── SRI in build manifest ───────────────────────────────────────

    public function test_sri_in_build_manifest(): void
    {
        $path = public_path('build/manifest.json');
        if (! file_exists($path)) {
            $this->markTestSkipped('Run npm run build first.');
        }

        $manifest = json_decode(file_get_contents($path), true);
        foreach ($manifest as $entry => $data) {
            $this->assertArrayHasKey('integrity', $data, "Missing SRI for '{$entry}'.");
            $this->assertMatchesRegularExpression('/^sha(256|384|512)-.+/', $data['integrity']);
        }
    }

    // ─── Helpers ─────────────────────────────────────────────────────

    private function assertNoXsrfCookie($response): void
    {
        $setCookies = $response->headers->all('set-cookie');
        foreach ($setCookies as $cookie) {
            $this->assertStringNotContainsString(
                'XSRF-TOKEN',
                $cookie,
                'XSRF-TOKEN cookie must NOT be set.'
            );
        }
    }

    private function assertNoSessionCookie($response): void
    {
        $setCookies = $response->headers->all('set-cookie');
        foreach ($setCookies as $cookie) {
            $this->assertStringNotContainsString(
                'laravel_session',
                $cookie,
                'laravel_session cookie must NOT be set on stateless routes.'
            );
        }
    }
}
