<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ClauService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class ApiAuthController extends Controller
{
    private $clauService;
    public function __construct (ClauService $clauService)
    {
        $this->clauService = $clauService;
    }

    public function login (Request $request)
    {
        $this->validate($request, [
            'email' => 'required',
            'password' => 'required',
        ]);

        $login_data = [
            'email' => $request->input('email'),
            'password' => $request->input('password'),
        ];

        try {
            Log::info('Login attempt', [
                'email' => $login_data['email'],
                'has_password' => !empty($login_data['password']),
                'ip' => $request->ip(),
                'user_agent' => $request->header('User-Agent')
            ]);
        } catch (\Exception $e) {
            // Silent fail if logging fails
        }

        $response = $this->clauService->login($login_data['email'], $login_data['password']);
        
        if ($response->successful()) {
            $responseData = $response->json();

            if (isset($responseData['codigoRespuesta']) && $responseData['codigoRespuesta'] === 0) {
                // Check if session exists
                if (!$request->hasSession()) {
                    try {
                        Log::error('Session not available during login', [
                            'email' => $login_data['email'],
                            'ip' => $request->ip()
                        ]);
                    } catch (\Exception $e) {
                        // Silent fail if logging fails
                    }
                    
                    return response()->json([
                        'code' => 500,
                        'message' => 'Session not available',
                    ])->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
                }
                
                // Store token in session
                $request->session()->put('clauToken', $responseData['token']);
                
                // Force session save
                $request->session()->save();
                
                // Set cookie with appropriate settings for production
                $domain = parse_url(config('app.url'), PHP_URL_HOST);
                
                // If domain starts with www, make cookie available to subdomains
                if (strpos($domain, 'www.') === 0) {
                    $domain = substr($domain, 4); // Remove www.
                }
                
                // For localhost or IP testing
                if ($domain === 'localhost' || filter_var($domain, FILTER_VALIDATE_IP)) {
                    $domain = null;
                }
                
                $cookie = cookie(
                    'clau_token',        // name
                    $responseData['token'], // value
                    120,                 // minutes (2 hours)
                    '/',                 // path
                    $domain,             // domain (null for localhost)
                    null,                // secure (null = auto-detect)
                    false,               // httpOnly (false to allow JS access)
                    true,                // raw
                    'lax'                // sameSite
                );
                
                try {
                    Log::debug('Login successful, token stored in session', [
                        'has_session' => $request->hasSession(),
                        'session_id' => $request->session()->getId(),
                        'token_stored' => $request->session()->has('clauToken'),
                        'token_length' => strlen($responseData['token']),
                        'session_driver' => config('session.driver'),
                        'cookie_set' => true,
                        'domain' => $domain
                    ]);
                } catch (\Exception $e) {
                    // Silent fail if logging fails
                }
                
                return response()->json([
                    'code' => 0,
                    'message' => 'Haz iniciado sesión correctamente',
                ])->setStatusCode(Response::HTTP_OK)->withCookie($cookie);
            }

            try {
                Log::warning('Login failed: API returned error', [
                    'code' => $responseData['codigoRespuesta'],
                    'message' => $responseData['msj'],
                    'email' => $login_data['email']
                ]);
            } catch (\Exception $e) {
                // Silent fail if logging fails
            }

            return response()->json([
                'code' => $responseData['codigoRespuesta'],
                'message' => $responseData['msj'],
            ])->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        try {
            Log::error('Login failed: API request error', [
                'status' => $response->status(),
                'body' => $response->body(),
                'email' => $login_data['email']
            ]);
        } catch (\Exception $e) {
            // Silent fail if logging fails
        }

        return response()->json([
            'message' => 'Error en la solicitud a la API',
        ])->setStatusCode($response->status());
    }

    public function register (Request $request)
    {
        $this->validate($request, [
            'fullname' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'birthday' => 'required|date',
            'password' => 'required',
            'ref' => 'nullable'
        ]);

        $nameExploded = explode(' ', $request->get('fullname'));
        $firstName = $nameExploded[0] ?? '';
        $lastName = $nameExploded[1] ?? '';

        $register_data = [
            'fullname' => $request->get('fullname'),
            'firstname' => $firstName,
            'lastname' => $lastName,
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'birthday' => $request->input('birthday'),
            'password' => $request->input('password'),
        ];

        if ($request->input('ref')) {
            $register_data['ref'] = $request->input('ref');
        }

        $response = $this->clauService->register($register_data);
        if ($response->successful()) {
            $responseData = $response->json();

            if (isset($responseData['CodRes']) && $responseData['CodRes'] === 0) {
                $responseLogin = $this->clauService->login($request->input('email'), $request->input('password'));
                if ($responseLogin->successful()) {
                    $responseLoginData = $responseLogin->json();

                    if (isset($responseLoginData['codigoRespuesta']) && $responseLoginData['codigoRespuesta'] === 0) {
                        $request->session()->put('clauToken', $responseLoginData['token']);
                        $request->session()->save();
                        
                        // Set cookie here as well
                        $domain = parse_url(config('app.url'), PHP_URL_HOST);
                        if (strpos($domain, 'www.') === 0) {
                            $domain = substr($domain, 4);
                        }
                        if ($domain === 'localhost' || filter_var($domain, FILTER_VALIDATE_IP)) {
                            $domain = null;
                        }
                        
                        $cookie = cookie(
                            'clau_token',
                            $responseLoginData['token'],
                            120,
                            '/',
                            $domain,
                            null,
                            false,
                            true,
                            'lax'
                        );

                        return response()->json([
                            'code' => 0,
                            'message' => 'Registro completado correctamente',
                        ])->setStatusCode(Response::HTTP_OK)->withCookie($cookie);
                    }
                }
            }

            return response()->json([
                'code' => $responseData['CodRes'],
                'message' => $responseData['Msj'],
            ])->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        return response()->json([
            'message' => 'Error en la solicitud a la API',
        ])->setStatusCode($response->status());
    }

    public function recovery_password (Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
        ]);

        $response = $this->clauService->recovery($request->get('email'));
        if ($response->successful()) {
            $responseData = $response->json();

            if (isset($responseData['CodRes']) && $responseData['CodRes'] === 0) {
                return response()->json([
                    'code' => 0,
                    'message' => 'Correo de recuperación enviado',
                ])->setStatusCode(Response::HTTP_OK);
            }

            return response()->json([
                'code' => $responseData['CodRes'],
                'message' => $responseData['Msj'],
            ])->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        return response()->json([
            'message' => 'Error en la solicitud a la API',
        ])->setStatusCode($response->status());
    }
}
