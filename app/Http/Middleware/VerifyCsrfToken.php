<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier
{
    /**
     * Disable the XSRF-TOKEN cookie.
     *
     * CSRF protection remains fully active via:
     * - @csrf / _token hidden input in forms
     * - X-CSRF-TOKEN header read from <meta name="csrf-token"> for AJAX
     *
     * This eliminates the "Cookie No HttpOnly Flag" OWASP ZAP finding
     * because the XSRF-TOKEN cookie (which must be non-HttpOnly for JS access)
     * is no longer created at all.
     *
     * @var bool
     */
    protected $addHttpCookie = false;
}
