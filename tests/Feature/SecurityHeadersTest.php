<?php

namespace Tests\Feature;

use Tests\TestCase;

class SecurityHeadersTest extends TestCase
{
    public function test_security_headers_are_present_in_responses(): void
    {
        $response = $this->get('/');

        $response->assertHeader('Content-Security-Policy', "default-src 'self'; font-src 'self' https://fonts.bunny.net; style-src 'self' 'unsafe-inline' https://fonts.bunny.net; img-src 'self' data:;");
        $response->assertHeader('X-Frame-Options', 'DENY');
        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->assertHeader('X-XSS-Protection', '1; mode=block');

        $this->assertFalse($response->headers->has('X-Powered-By'), 'X-Powered-By header should be removed for security.');
    }

    public function test_security_headers_are_present_on_auth_pages(): void
    {
        $response = $this->get('/login');

        $response->assertHeader('Content-Security-Policy');
        $response->assertHeader('X-Frame-Options', 'DENY');
        $response->assertHeader('X-Content-Type-Options', 'nosniff');
    }
}
