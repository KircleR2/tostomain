<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function __invoke (Request $request)
    {
        // Check for token in query parameter as fallback
        $tokenParam = $request->query('token');
        if ($tokenParam && $tokenParam !== 'fallback' && !session('clauToken')) {
            Log::debug('Setting token from query parameter', [
                'token_prefix' => substr($tokenParam, 0, 10) . '...'
            ]);
            
            // Store in session
            session(['clauToken' => $tokenParam]);
            $request->session()->put('clauToken', $tokenParam);
            $request->session()->save();
            
            // Also store in cookies
            $cookieOptions = [
                'expires' => time() + 60 * 60 * 24, // 1 day
                'path' => '/',
                'domain' => null,
                'secure' => true,
                'httponly' => false,
                'samesite' => 'None'
            ];
            
            setcookie('clau_token', $tokenParam, $cookieOptions);
        }
        
        // Debug log
        Log::debug('Dashboard access', [
            'session_id' => session()->getId(),
            'has_token' => !empty(session('clauToken')),
            'token_in_param' => !empty($tokenParam),
            'token_prefix' => session('clauToken') ? substr(session('clauToken'), 0, 10) . '...' : 'none'
        ]);
        
        return view('back.dashboard');
    }
}