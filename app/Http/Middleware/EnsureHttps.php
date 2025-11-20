<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureHttps
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->secure() && in_array(env('APP_ENV'), ['production', 'staging'])) {
            return redirect()->secure($request->getRequestUri());
        }

        return $next($request);
    }
}