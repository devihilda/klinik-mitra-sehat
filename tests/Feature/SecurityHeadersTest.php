<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SecurityHeadersTest extends TestCase
{
    use RefreshDatabase;

    private $expectedCsp = "default-src 'self'; base-uri 'self'; object-src 'none'; frame-ancestors 'none'; form-action 'self'; img-src 'self' data:; font-src 'self' data:; script-src 'self'; style-src 'self' 'unsafe-inline'; connect-src 'self';";

    public function test_security_headers_are_present_in_responses(): void
    {
        $response = $this->get('/');

        $response->assertHeader('Content-Security-Policy', $this->expectedCsp);
        $response->assertHeader('X-Frame-Options', 'DENY');
        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->assertHeader('X-XSS-Protection', '1; mode=block');

        $this->assertFalse($response->headers->has('X-Powered-By'), 'X-Powered-By header should be removed for security.');
    }

    public function test_security_headers_are_present_on_auth_pages(): void
    {
        $response = $this->get('/login');

        $response->assertHeader('Content-Security-Policy', $this->expectedCsp);
        $response->assertHeader('X-Frame-Options', 'DENY');
        $response->assertHeader('X-Content-Type-Options', 'nosniff');
    }

    public function test_security_headers_are_present_on_robots_and_sitemap(): void
    {
        $responseRobots = $this->get('/robots.txt');
        $responseRobots->assertHeader('Content-Type', 'text/plain; charset=UTF-8');
        $responseRobots->assertHeader('X-Content-Type-Options', 'nosniff');
        $responseRobots->assertHeader('Content-Security-Policy', $this->expectedCsp);

        $responseSitemap = $this->get('/sitemap.xml');
        $responseSitemap->assertHeader('Content-Type', 'application/xml; charset=UTF-8');
        $responseSitemap->assertHeader('X-Content-Type-Options', 'nosniff');
        $responseSitemap->assertHeader('Content-Security-Policy', $this->expectedCsp);
    }

    public function test_security_headers_are_present_on_error_pages(): void
    {
        // 404 Page
        $response404 = $this->get('/non-existent-route-for-testing-404-pages');
        $response404->assertStatus(404);
        $response404->assertHeader('Content-Security-Policy', $this->expectedCsp);
        $response404->assertHeader('X-Frame-Options', 'DENY');
        $response404->assertHeader('X-Content-Type-Options', 'nosniff');

        // 405 Page (GET /logout is not allowed, only POST)
        $response405 = $this->get('/logout');
        $response405->assertStatus(405);
        $response405->assertHeader('Content-Security-Policy', $this->expectedCsp);
        $response405->assertHeader('X-Frame-Options', 'DENY');
        $response405->assertHeader('X-Content-Type-Options', 'nosniff');
    }

    public function test_session_cookie_settings(): void
    {
        $this->assertTrue(config('session.http_only'), 'Session cookie must be HttpOnly.');
        $this->assertContains(config('session.same_site'), ['lax', 'strict'], 'Session SameSite must be Lax or Strict.');
    }

    public function test_permissions_policy_header_is_present(): void
    {
        $response = $this->get('/');

        $permissionsPolicy = $response->headers->get('Permissions-Policy');
        $this->assertNotNull($permissionsPolicy, 'Permissions-Policy header must be present.');
        $this->assertStringContainsString('camera=()', $permissionsPolicy);
        $this->assertStringContainsString('microphone=()', $permissionsPolicy);
        $this->assertStringContainsString('geolocation=()', $permissionsPolicy);
        $this->assertStringContainsString('payment=()', $permissionsPolicy);
    }

    public function test_redirect_body_is_minimized(): void
    {
        // GET /dashboard without auth should redirect to /login
        $response = $this->get('/dashboard');

        $response->assertStatus(302);
        $response->assertHeader('Location');

        $content = $response->getContent();
        // Redirect body must be small (< 400 bytes) to avoid "Big Redirect Detected"
        $this->assertLessThan(400, strlen($content), 'Redirect response body should be minimized (< 400 bytes).');
        // Must not contain sensitive form data or large HTML
        $this->assertStringNotContainsString('<!DOCTYPE html>', $content, 'Redirect body should not contain full HTML document.');
    }

    public function test_authenticated_redirect_body_is_minimized(): void
    {
        $user = User::factory()->create(['role' => 'pasien']);

        // Dashboard redirector sends 302 to patients.dashboard
        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertRedirect();
        $content = $response->getContent();
        $this->assertLessThan(400, strlen($content), 'Authenticated redirect body should be minimized.');
    }

    public function test_sri_integrity_hashes_in_build_manifest(): void
    {
        $manifestPath = public_path('build/manifest.json');

        if (! file_exists($manifestPath)) {
            $this->markTestSkipped('Build manifest not found. Run `npm run build` first.');
        }

        $manifest = json_decode(file_get_contents($manifestPath), true);

        foreach ($manifest as $entry => $data) {
            $this->assertArrayHasKey(
                'integrity',
                $data,
                "Build manifest entry '{$entry}' is missing SRI integrity hash. Run `npm run build` with vite-plugin-manifest-sri."
            );
            // Integrity hash should start with a valid algorithm prefix
            $this->assertMatchesRegularExpression(
                '/^sha(256|384|512)-.+/',
                $data['integrity'],
                "Integrity hash for '{$entry}' must use a valid algorithm (sha256/sha384/sha512)."
            );
        }
    }
}
