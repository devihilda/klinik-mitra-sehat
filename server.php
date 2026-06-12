<?php

$publicPath = getcwd();

$uri = urldecode(
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? ''
);

$file = $publicPath.$uri;

// Block access to dotfiles and hidden directories (like .htaccess, .git, etc.)
$filename = basename($file);
if ($uri !== '/' && (str_starts_with($filename, '.') || str_contains($uri, '/.')) && ! str_contains($uri, '/.well-known/')) {
    if (function_exists('header_remove')) {
        header_remove('X-Powered-By');
    }
    http_response_code(403);
    header('Content-Type: text/plain; charset=utf-8');
    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: DENY');
    echo 'Access Denied.';
    exit;
}

// This file allows us to emulate Apache's "mod_rewrite" functionality from the
// built-in PHP web server. This provides a convenient way to test a Laravel
// application without having installed a "real" web server software here.
if ($uri !== '/' && file_exists($file) && ! is_dir($file)) {
    // Remove X-Powered-By header
    if (function_exists('header_remove')) {
        header_remove('X-Powered-By');
    }

    // Apply OWASP recommended security headers for static files served in dev
    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: DENY');
    header('Referrer-Policy: strict-origin-when-cross-origin');
    header("Content-Security-Policy: default-src 'self'; base-uri 'self'; object-src 'none'; frame-ancestors 'none'; form-action 'self'; img-src 'self' data:; font-src 'self' data:; script-src 'self'; style-src 'self'; connect-src 'self'");

    // Guess and set Content-Type header
    $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
    $mimeTypes = [
        'css' => 'text/css',
        'js' => 'text/javascript',
        'svg' => 'image/svg+xml',
        'png' => 'image/png',
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'gif' => 'image/gif',
        'ico' => 'image/x-icon',
        'json' => 'application/json',
        'xml' => 'application/xml',
        'txt' => 'text/plain',
    ];

    if (isset($mimeTypes[$extension])) {
        header('Content-Type: '.$mimeTypes[$extension]);
    }

    readfile($file);
    exit;
}

$formattedDateTime = date('D M j H:i:s Y');

$requestMethod = $_SERVER['REQUEST_METHOD'];
$remoteAddress = $_SERVER['REMOTE_ADDR'].':'.$_SERVER['REMOTE_PORT'];

file_put_contents('php://stdout', "[$formattedDateTime] $remoteAddress [$requestMethod] URI: $uri\n");

require_once $publicPath.'/index.php';
