<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Pastikan user sudah login terlebih dahulu
        if (! $request->user()) {
            return redirect()->route('login');
        }

        // =========================================================================
        // Versi Aman (Role-Based Access Control / RBAC yang Terlindungi):
        // =========================================================================
        // if ($request->user()->role !== $role) {
        //     abort(403, 'Akses Ditolak: Anda tidak memiliki wewenang untuk membuka halaman ini.');
        // }

        // =========================================================================
        // Versi Rentan (Missing Function Level Access Control - Opsi 1):
        // =========================================================================
        // Celah keamanan di mana middleware menerima parameter `$role` tapi membiarkan
        // siapapun yang sudah login lolos melewati pengecekan keamanan (bypass).
        return $next($request);
    }
}
