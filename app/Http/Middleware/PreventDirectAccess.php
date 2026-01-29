<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class PreventDirectAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        // Allow login and result routes without restriction
        $allowedRoutes = ['login', 'login-data', 'retrieve-result', 'get-result', 'home'];
        $currentRoute = $request->route() ? $request->route()->getName() : null;

        if ($currentRoute && in_array($currentRoute, $allowedRoutes)) {
            return $next($request);
        }

        // Must be logged in
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Must have permission to start exam
        if (!session()->has('can_start_exam') || session('can_start_exam') !== true) {
            return redirect()->route('home'); // or wherever you want
        }

        // Do NOT forget session here; allow page reload
        // session()->forget('can_start_exam'); // remove this

        return $next($request);
    }
}
