<?php

namespace Tests\Feature;

use Tests\TestCase;

class SecurityHeadersTest extends TestCase
{
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
}
