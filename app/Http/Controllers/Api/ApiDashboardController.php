<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ClauService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class ApiDashboardController extends Controller
{
    private $clauService;
    
    public function __construct()
    {
        $this->clauService = new ClauService();
    }
    
    private function getToken(Request $request)
    {
        // Try multiple sources to find the token
        $token = null;
        
        // 1. Try request attribute (set by middleware)
        if ($request->attributes->has('clauToken')) {
            $token = $request->attributes->get('clauToken');
            Log::debug('Dashboard: Token found in request attributes');
        }
        
        // 2. Try session
        if (!$token) {
            $token = session('clauToken');
            if ($token) {
                Log::debug('Dashboard: Token found in session');
            }
        }
        
        // 3. Try cookies
        if (!$token) {
            $token = $request->cookie('clau_token') ?? $request->cookie('clau_token_secure');
            if ($token) {
                Log::debug('Dashboard: Token found in cookie');
                // Save to session for future use
                session(['clauToken' => $token]);
            }
        }
        
        // 4. Try request input (might be passed in POST body)
        if (!$token && $request->has('token')) {
            $token = $request->input('token');
            if ($token) {
                Log::debug('Dashboard: Token found in request input');
                // Save to session for future use
                session(['clauToken' => $token]);
            }
        }
        
        // 5. Check the debug file as last resort
        if (!$token && file_exists(storage_path('logs/debug_token.txt'))) {
            $token = file_get_contents(storage_path('logs/debug_token.txt'));
            if ($token) {
                Log::debug('Dashboard: Token found in debug file');
                // Save to session for future use
                session(['clauToken' => $token]);
            }
        }
        
        return $token;
    }

    public function index(Request $request)
    {
        try {
            $token = $this->getToken($request);
            
            if (!$token) {
                Log::error('Dashboard: No token found');
                return response()->json([
                    'code' => 1000,
                    'message' => 'Token de sesión no encontrado',
                    'redirect' => '/login'
                ])->setStatusCode(Response::HTTP_UNAUTHORIZED);
            }
            
            Log::debug('Dashboard: Calling API with token', [
                'token_prefix' => substr($token, 0, 10) . '...'
            ]);
            
            $response = $this->clauService->getUserData($token);
    
            if ($response->successful()) {
                $responseData = $response->json();
                
                if (!is_array($responseData)) {
                    Log::error('Dashboard: Invalid response data', [
                        'data' => $responseData
                    ]);
                    return response()->json([
                        'code' => 1001,
                        'message' => 'Respuesta de API inválida',
                        'redirect' => '/login'
                    ])->setStatusCode(Response::HTTP_BAD_REQUEST);
                }
    
                if (isset($responseData['CodRes']) && $responseData['CodRes'] === 0) {
                    // Success case
                    Log::debug('Dashboard: Success response from API');
                    $points = $responseData['ArrRes']['Points'] / 100;
                    return response()->json([
                        'code' => 0,
                        'user' => [
                            'fullname' => $responseData['ArrRes']['Name'] . ' ' . $responseData['ArrRes']['Last'],
                            'points' => $points,
                            'balance' => $points / ($responseData['ArrRes']['tasaCambio'] ?? 1),
                            'phone' => $responseData['ArrRes']['Phone'],
                            'email' => $responseData['ArrRes']['Email'],
                            'ref' => $responseData['ArrRes']['codRef'] ?? '',
                        ],
                    ])->setStatusCode(Response::HTTP_OK);
                }
                
                // Error case
                Log::warning('Dashboard: API returned error', [
                    'code' => $responseData['CodRes'] ?? 'unknown',
                    'message' => $responseData['Msj'] ?? 'Unknown error'
                ]);
                
                // Don't remove the token on first error, just return the error
                return response()->json([
                    'code' => $responseData['CodRes'] ?? 1002,
                    'message' => $responseData['Msj'] ?? 'Error desconocido',
                ])->setStatusCode(Response::HTTP_BAD_REQUEST);
            }
    
            Log::error('Dashboard: API request failed', [
                'status' => $response->status(),
                'body' => substr($response->body(), 0, 500)
            ]);
            
            return response()->json([
                'code' => 1003,
                'message' => 'Error en la solicitud a la API: ' . $response->status(),
            ])->setStatusCode($response->status());
        } catch (\Exception $e) {
            Log::error('Dashboard: Exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'code' => 1004,
                'message' => 'Error interno: ' . $e->getMessage(),
            ])->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function store_points(Request $request)
    {
        try {
            $token = $this->getToken($request);
            
            if (!$token) {
                return response()->json([
                    'code' => 1000,
                    'message' => 'Token de sesión no encontrado',
                    'redirect' => '/login'
                ])->setStatusCode(Response::HTTP_UNAUTHORIZED);
            }
            
            $response = $this->clauService->getStorePoints($token);
    
            if ($response->successful()) {
                $responseData = $response->json();
    
                if (isset($responseData['CodRes']) && $responseData['CodRes'] === 0) {
                    return response()->json([
                        'code' => 0,
                        'products' => $responseData['ArrRes'],
                    ])->setStatusCode(Response::HTTP_OK);
                }
                
                // Don't remove the token on first error
                return response()->json([
                    'code' => $responseData['CodRes'] ?? 1002,
                    'message' => $responseData['Msj'] ?? 'Error desconocido',
                ])->setStatusCode(Response::HTTP_BAD_REQUEST);
            }
    
            return response()->json([
                'code' => 1003,
                'message' => 'Error en la solicitud a la API: ' . $response->status(),
            ])->setStatusCode($response->status());
        } catch (\Exception $e) {
            Log::error('Store Points: Exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'code' => 1004,
                'message' => 'Error interno: ' . $e->getMessage(),
            ])->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function buy_product(Request $request)
    {
        try {
            $token = $this->getToken($request);
            
            if (!$token) {
                return response()->json([
                    'code' => 1000,
                    'message' => 'Token de sesión no encontrado',
                    'redirect' => '/login'
                ])->setStatusCode(Response::HTTP_UNAUTHORIZED);
            }
            
            $productId = $request->input('regaloId');
            $response = $this->clauService->buyProduct($token, $productId);
    
            if ($response->successful()) {
                $responseData = $response->json();
    
                if (isset($responseData['CodRes']) && $responseData['CodRes'] === 0) {
                    return response()->json([
                        'code' => 0,
                        'message' => '¡Producto canjeado correctamente!',
                    ])->setStatusCode(Response::HTTP_OK);
                }
                
                // Don't remove the token on first error
                return response()->json([
                    'code' => $responseData['CodRes'] ?? 1002,
                    'message' => $responseData['Msj'] ?? 'Error desconocido',
                ])->setStatusCode(Response::HTTP_BAD_REQUEST);
            }
    
            return response()->json([
                'code' => 1003,
                'message' => 'Error en la solicitud a la API: ' . $response->status(),
            ])->setStatusCode($response->status());
        } catch (\Exception $e) {
            Log::error('Buy Product: Exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'code' => 1004,
                'message' => 'Error interno: ' . $e->getMessage(),
            ])->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function gifts(Request $request)
    {
        try {
            $token = $this->getToken($request);
            
            if (!$token) {
                return response()->json([
                    'code' => 1000,
                    'message' => 'Token de sesión no encontrado',
                    'redirect' => '/login'
                ])->setStatusCode(Response::HTTP_UNAUTHORIZED);
            }
            
            $response = $this->clauService->getGifts($token);
    
            if ($response->successful()) {
                $responseData = $response->json();
    
                if (isset($responseData['CodRes']) && $responseData['CodRes'] === 0) {
                    return response()->json([
                        'code' => 0,
                        'gifts' => $responseData['ArrRes'],
                    ])->setStatusCode(Response::HTTP_OK);
                }
                
                // Don't remove the token on first error
                return response()->json([
                    'code' => $responseData['CodRes'] ?? 1002,
                    'message' => $responseData['Msj'] ?? 'Error desconocido',
                ])->setStatusCode(Response::HTTP_BAD_REQUEST);
            }
    
            return response()->json([
                'code' => 1003,
                'message' => 'Error en la solicitud a la API: ' . $response->status(),
            ])->setStatusCode($response->status());
        } catch (\Exception $e) {
            Log::error('Gifts: Exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'code' => 1004,
                'message' => 'Error interno: ' . $e->getMessage(),
            ])->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}