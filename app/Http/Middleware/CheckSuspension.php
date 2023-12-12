<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\Response;

class CheckSuspension
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

        if ($user && $user->is_suspended) {
            $now = Carbon::now();
            $start = Carbon::parse($user->suspend_start_date);
            $end = Carbon::parse($user->suspend_end_date);

            // Calculate the difference in human-readable format
            $suspensionTime = $end->diffForHumans($now, [
                'parts' => 2,
                'short' => true,
            ]);

            auth()->logout();

            return redirect()->route('login')->with('error', "Your account is suspended for {$suspensionTime}.");
        }

        return $next($request);
    }
}
