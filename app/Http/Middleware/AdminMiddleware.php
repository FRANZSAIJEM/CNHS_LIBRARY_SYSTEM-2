<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        if ($request->route()->getName() === 'bookList' || (auth()->user() && (auth()->user()->is_admin || auth()->user()->is_staff))) {
            return $next($request);
        }

        // Redirect non-admin users to a different page or show an error message
        return redirect()->route('dashboard'); // Adjust 'home
    }
}
