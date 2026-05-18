<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetGlobalSettings
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Handle Locale
        if (session()->has('locale')) {
            app()->setLocale(session('locale'));
        }

        // Handle Currency (Default to INR)
        if (!session()->has('currency')) {
            session(['currency' => 'INR']);
        }

        return $next($request);
    }
}
