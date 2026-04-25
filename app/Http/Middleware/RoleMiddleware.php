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
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!auth()->check()) {
            return redirect('/login');
        }

        if (auth()->user()->role !== $role) {
            // Redirect ke dashboard sesuai role user
            return redirect($this->redirectByRole(auth()->user()->role));
        }

        return $next($request);
    }

    /**
     * Redirect user ke dashboard sesuai role
     */
    private function redirectByRole(string $role): string
    {
        return match($role) {
            'admin' => '/admin/dashboard',
            'coach' => '/coach/dashboard',
            'siswa' => '/siswa/dashboard',
            default => '/',
        };
    }
}
