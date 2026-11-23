<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class EnsureUserIsActive
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if ($user && in_array($user->status, ['suspended', 'blocked'])) {
            $status = $user->status;
            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            $message = $status === 'suspended'
                ? 'Akun Anda sedang ditangguhkan (suspended). Silakan hubungi administrator.'
                : 'Akun Anda telah diblokir (blocked) karena pelanggaran kebijakan.';

            return redirect()->route('login')->withErrors(['email' => $message]);
        }

        return $next($request);
    }
}
