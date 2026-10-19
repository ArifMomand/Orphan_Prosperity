<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class PioneerAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::guard('pioneer')->check()) {
            return redirect()->route('pioneer.login');
        }

        return $next($request);
    }
}