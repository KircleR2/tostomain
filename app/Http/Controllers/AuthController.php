<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function login ()
    {
        return view('back.login');
    }

    public function register()
    {
        return view('back.register');
    }

    public function recovery_password()
    {
        return view('back.recovery-password');
    }
    
    public function logout(Request $request)
    {
        // Log before logout
        Log::debug('Logout initiated', [
            'has_session' => $request->hasSession(),
            'token_exists' => $request->session()->has('clauToken'),
            'session_id' => $request->session()->getId(),
            'method' => $request->method(),
            'is_ajax' => $request->ajax(),
            'headers' => $request->headers->all()
        ]);
        
        // Remove token from session
        $request->session()->forget('clauToken');
        
        // Invalidate the session
        $request->session()->invalidate();
        
        // Regenerate the CSRF token
        $request->session()->regenerateToken();
        
        // Log after clearing session
        Log::debug('Session cleared during logout');
        
        // Force session save
        $request->session()->save();
        
        // Redirect based on request type
        if ($request->ajax()) {
            return response()->json(['success' => true, 'redirect' => route('auth.login')]);
        }
        
        return redirect(route('auth.login'));
    }
}
