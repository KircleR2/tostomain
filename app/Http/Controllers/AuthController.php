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
        Log::debug('Logging out user', [
            'has_session' => $request->hasSession(),
            'token_exists' => $request->session()->has('clauToken'),
            'session_id' => $request->session()->getId()
        ]);
        
        // Remove token from session
        $request->session()->forget('clauToken');
        $request->session()->save();
        
        return redirect(route('auth.login'));
    }
}
