<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!$request->session()->has('user_id')) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        $userRole = $request->session()->get('user_role');

        if ($userRole !== $role) {
            if ($userRole === 'admin') {
                return redirect()->route('admin.home')
                    ->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
            }
            return redirect()->route('home')
                ->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
        }

        return $next($request);
    }
}
