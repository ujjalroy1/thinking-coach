<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PremiumOnly
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
       if (!auth()->check() || !auth()->user()->isPremium()) {
            return redirect()->route('dashboard')
                ->with('error', 'This feature requires a Premium plan.');
        }

        return $next($request);

    }
}
