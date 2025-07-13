<?php

namespace App\Http\Controllers;

use App\Http\Middleware\ClauTokenMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cookie;

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
        try {
            // Log before logout
            Log::debug('Logging out user', [
                'has_session' => $request->hasSession(),
                'token_exists' => $request->session()->has('clauToken'),
                'session_id' => $request->session()->getId(),
                'cookie_name' => ClauTokenMiddleware::COOKIE_NAME
            ]);
        } catch (\Exception $e) {
            // Silent fail if logging fails
        }
        
        // Remove token from session
        $request->session()->forget('clauToken');
        $request->session()->save();
        
        // Create a response that will delete the cookie
        $response = redirect(route('auth.login'));
        
        // Get domain for cookie
        $domain = parse_url(config('app.url'), PHP_URL_HOST);
        
        // If domain starts with www, make cookie available to subdomains
        if (strpos($domain, 'www.') === 0) {
            $domain = substr($domain, 4); // Remove www.
        }
        
        // For localhost or IP testing
        if ($domain === 'localhost' || filter_var($domain, FILTER_VALIDATE_IP)) {
            $domain = null;
        }
        
        // Delete the cookie by setting it with a past expiration
        return $response->withCookie(cookie(
            ClauTokenMiddleware::COOKIE_NAME, // name
            '',                  // empty value
            -1,                  // expired (in the past)
            '/',                 // path
            $domain,             // domain
            null,                // secure (null = auto-detect)
            false,               // httpOnly
            true,                // raw
            'lax'                // sameSite
        ));
    }
}
