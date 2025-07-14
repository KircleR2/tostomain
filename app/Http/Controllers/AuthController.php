<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;

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
    public function logout()
    {
        Session::remove('clauToken');
        return redirect(route('auth.login'));
    }
}
