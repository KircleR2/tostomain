<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class ClauTokenMiddleware
{
    public function handle (Request $request, Closure $next)
    {
        $token = $request->session()->get('clauToken');
        
        // Log for debugging
        Log::debug('Session check in middleware', [
            'has_session' => $request->hasSession(),
            'token_exists' => !empty($token),
            'session_id' => $request->session()->getId()
        ]);
        
        if (!$token) {
            return redirect(route('auth.login'));
        }

        return $next($request);
    }
}
