<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Remove X-Powered-By header before outputting the headers
        if (function_exists('header_remove')) {
            header_remove('X-Powered-By');
        }

        $response = $next($request);

        // Remove X-Powered-By from Symfony response headers as well
        if (method_exists($response, 'headers')) {
            $response->headers->remove('X-Powered-By');
        }

        // Apply OWASP recommended security headers
        if (! app()->environment('local')) {
            $response->headers->set('Content-Security-Policy', "default-src 'self'; base-uri 'self'; object-src 'none'; frame-ancestors 'none'; form-action 'self'; img-src 'self' data:; font-src 'self' data:; script-src 'self'; style-src 'self' 'unsafe-inline'; connect-src 'self';");
        }
        $response->headers->set('X-Frame-Options', 'DENY');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // Permissions-Policy: restrict sensitive browser features
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=(), payment=(), usb=(), magnetometer=(), gyroscope=(), accelerometer=()');

        // Strict-Transport-Security (HSTS): only when served over HTTPS
        if ($request->isSecure()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }

        // Minimize redirect response body to prevent "Big Redirect Detected" alert
        $statusCode = $response->getStatusCode();
        if ($statusCode >= 300 && $statusCode < 400 && $response->headers->has('Location')) {
            $location = $response->headers->get('Location');
            $response->setContent(
                '<html><head><meta http-equiv="refresh" content="0;url='.e($location).'"></head><body></body></html>'
            );
        }

        return $response;
    }
}
