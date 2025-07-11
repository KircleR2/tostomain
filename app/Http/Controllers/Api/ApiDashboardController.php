<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ClauService;
use Http;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class ApiDashboardController extends Controller
{
    public function index (Request $request)
    {
        try {
            Log::debug('Dashboard API request', [
                'has_session' => $request->hasSession(),
                'session_id' => $request->session()->getId(),
                'headers' => $request->headers->all(),
                'cookies' => $request->cookies->all()
            ]);
            
            $token = Session::get('clauToken');
            
            Log::debug('Session token', [
                'token_exists' => !empty($token),
                'token_length' => !empty($token) ? strlen($token) : 0
            ]);
            
            if (empty($token)) {
                Log::error('No token found in session');
                return response()->json([
                    'code' => 401,
                    'message' => 'No authentication token found'
                ])->setStatusCode(Response::HTTP_UNAUTHORIZED);
            }
            
            $response = (new ClauService())->getUserData($token);

            if ($response->successful()) {
                $responseData = $response->json();
                Log::debug('API response successful', ['response' => $responseData]);

                if (isset($responseData['CodRes']) && $responseData['CodRes'] === 0) {
                    $points = $responseData['ArrRes']['Points'] / 100;
                    return response()->json([
                        'code' => 0,
                        'user' => [
                            'fullname' => $responseData['ArrRes']['Name'] . ' ' . $responseData['ArrRes']['Last'],
                            'points' => $points,
                            'balance' => $points / $responseData['ArrRes']['tasaCambio'],
                            'phone' => $responseData['ArrRes']['Phone'],
                            'email' => $responseData['ArrRes']['Email'],
                            'ref' => $responseData['ArrRes']['codRef'],
                        ],
                    ])->setStatusCode(Response::HTTP_OK);
                }
                
                Log::warning('API returned error code', [
                    'code' => $responseData['CodRes'],
                    'message' => $responseData['Msj'] ?? 'No message'
                ]);
                
                $request->session()->remove('clauToken');
                return response()->json([
                    'code' => $responseData['CodRes'],
                    'message' => $responseData['Msj'],
                ])->setStatusCode(Response::HTTP_BAD_REQUEST);
            }
            
            Log::error('API request failed', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            return response()->json([
                'message' => 'Error en la solicitud a la API',
            ])->setStatusCode($response->status());
        } catch (\Exception $e) {
            Log::error('Exception in dashboard API', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'message' => 'Internal server error',
                'error' => $e->getMessage()
            ])->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function store_points (Request $request)
    {
        try {
            Log::debug('Store points API request', [
                'has_session' => $request->hasSession(),
                'session_id' => $request->session()->getId()
            ]);
            
            $token = Session::get('clauToken');
            
            if (empty($token)) {
                Log::error('No token found in session for store points');
                return response()->json([
                    'code' => 401,
                    'message' => 'No authentication token found'
                ])->setStatusCode(Response::HTTP_UNAUTHORIZED);
            }
            
            $response = (new ClauService())->getStorePoints($token);

            if ($response->successful()) {
                $responseData = $response->json();

                if (isset($responseData['CodRes']) && $responseData['CodRes'] === 0) {
                    return response()->json([
                        'code' => 0,
                        'products' => $responseData['ArrRes'],
                    ])->setStatusCode(Response::HTTP_OK);
                }

                $request->session()->remove('clauToken');
                return response()->json([
                    'code' => $responseData['CodRes'],
                    'message' => $responseData['Msj'],
                ])->setStatusCode(Response::HTTP_BAD_REQUEST);
            }

            Log::error('Store points API request failed', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);
            
            return response()->json([
                'message' => 'Error en la solicitud a la API',
            ])->setStatusCode($response->status());
        } catch (\Exception $e) {
            Log::error('Exception in store points API', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'message' => 'Internal server error',
                'error' => $e->getMessage()
            ])->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function buy_product (Request $request)
    {
        try {
            Log::debug('Buy product API request', [
                'has_session' => $request->hasSession(),
                'session_id' => $request->session()->getId(),
                'product_id' => $request->input('regaloId')
            ]);
            
            $token = Session::get('clauToken');
            
            if (empty($token)) {
                Log::error('No token found in session for buy product');
                return response()->json([
                    'code' => 401,
                    'message' => 'No authentication token found'
                ])->setStatusCode(Response::HTTP_UNAUTHORIZED);
            }
            
            $productId = $request->input('regaloId');
            $response = (new ClauService())->buyProduct($token, $productId);

            if ($response->successful()) {
                $responseData = $response->json();

                if (isset($responseData['CodRes']) && $responseData['CodRes'] === 0) {
                    return response()->json([
                        'code' => 0,
                        'message' => '¡Producto canjeado correctamente!',
                    ])->setStatusCode(Response::HTTP_OK);
                }

                Log::warning('Buy product API returned error code', [
                    'code' => $responseData['CodRes'],
                    'message' => $responseData['Msj'] ?? 'No message'
                ]);

                $request->session()->remove('clauToken');
                return response()->json([
                    'code' => $responseData['CodRes'],
                    'message' => $responseData['Msj'],
                ])->setStatusCode(Response::HTTP_BAD_REQUEST);
            }

            Log::error('Buy product API request failed', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            return response()->json([
                'message' => 'Error en la solicitud a la API',
            ])->setStatusCode($response->status());
        } catch (\Exception $e) {
            Log::error('Exception in buy product API', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'message' => 'Internal server error',
                'error' => $e->getMessage()
            ])->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function gifts (Request $request)
    {
        try {
            Log::debug('Gifts API request', [
                'has_session' => $request->hasSession(),
                'session_id' => $request->session()->getId()
            ]);
            
            $token = Session::get('clauToken');
            
            if (empty($token)) {
                Log::error('No token found in session for gifts');
                return response()->json([
                    'code' => 401,
                    'message' => 'No authentication token found'
                ])->setStatusCode(Response::HTTP_UNAUTHORIZED);
            }
            
            $response = (new ClauService())->getGifts($token);

            if ($response->successful()) {
                $responseData = $response->json();

                if (isset($responseData['CodRes']) && $responseData['CodRes'] === 0) {
                    return response()->json([
                        'code' => 0,
                        'gifts' => $responseData['ArrRes'],
                    ])->setStatusCode(Response::HTTP_OK);
                }

                Log::warning('Gifts API returned error code', [
                    'code' => $responseData['CodRes'],
                    'message' => $responseData['Msj'] ?? 'No message'
                ]);

                $request->session()->remove('clauToken');
                return response()->json([
                    'code' => $responseData['CodRes'],
                    'message' => $responseData['Msj'],
                ])->setStatusCode(Response::HTTP_BAD_REQUEST);
            }

            Log::error('Gifts API request failed', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            return response()->json([
                'message' => 'Error en la solicitud a la API',
            ])->setStatusCode($response->status());
        } catch (\Exception $e) {
            Log::error('Exception in gifts API', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'message' => 'Internal server error',
                'error' => $e->getMessage()
            ])->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
