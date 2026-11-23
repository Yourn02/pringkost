<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class SearcherMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        if ($user && $user->role === 'pencari') {
            return $next($request);
        }

        // Jika admin atau pemilik mengakses dashboard pencari, arahkan ke dashboard masing-masing
        if ($user && $user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($user && $user->role === 'pemilik') {
            return redirect()->route('owner.dashboard');
        }

        abort(403, 'Unauthorized. Khusus Akun Pencari Kost.');
    }
}
