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
        // Debug session information
        Log::info('API Dashboard request', [
            'has_session' => $request->hasSession(),
            'session_id' => $request->session() ? $request->session()->getId() : 'no-session',
            'session_driver' => config('session.driver'),
            'request_headers' => $request->headers->all(),
            'cookies' => $request->cookies->all(),
        ]);

        // Try to get token from session or cookie
        $token = null;
        if ($request->hasSession()) {
            $token = $request->session()->get('clauToken');
        }
        
        // Fallback to cookie if session doesn't have token
        if (!$token) {
            $token = $request->cookie('clau_token');
        }
        
        if (!$token) {
            Log::warning('No token found in API request');
            return response()->json([
                'code' => 401,
                'message' => 'Authentication required',
            ])->setStatusCode(Response::HTTP_UNAUTHORIZED);
        }

        $response = (new ClauService())->getUserData($token);

        if ($response->successful()) {
            $responseData = $response->json();

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
            $request->session()->remove('clauToken');
            return response()->json([
                'code' => $responseData['CodRes'],
                'message' => $responseData['Msj'],
            ])->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        return response()->json([
            'message' => 'Error en la solicitud a la API',
        ])->setStatusCode($response->status());
    }

    public function store_points (Request $request)
    {
        // Try to get token from session or cookie
        $token = null;
        if ($request->hasSession()) {
            $token = $request->session()->get('clauToken');
        }
        
        // Fallback to cookie if session doesn't have token
        if (!$token) {
            $token = $request->cookie('clau_token');
        }
        
        if (!$token) {
            return response()->json([
                'code' => 401,
                'message' => 'Authentication required',
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

        return response()->json([
            'message' => 'Error en la solicitud a la API',
        ])->setStatusCode($response->status());
    }

    public function buy_product (Request $request)
    {
        // Try to get token from session or cookie
        $token = null;
        if ($request->hasSession()) {
            $token = $request->session()->get('clauToken');
        }
        
        // Fallback to cookie if session doesn't have token
        if (!$token) {
            $token = $request->cookie('clau_token');
        }
        
        if (!$token) {
            return response()->json([
                'code' => 401,
                'message' => 'Authentication required',
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

            $request->session()->remove('clauToken');
            return response()->json([
                'code' => $responseData['CodRes'],
                'message' => $responseData['Msj'],
            ])->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        return response()->json([
            'message' => 'Error en la solicitud a la API',
        ])->setStatusCode($response->status());
    }

    public function gifts (Request $request)
    {
        // Try to get token from session or cookie
        $token = null;
        if ($request->hasSession()) {
            $token = $request->session()->get('clauToken');
        }
        
        // Fallback to cookie if session doesn't have token
        if (!$token) {
            $token = $request->cookie('clau_token');
        }
        
        if (!$token) {
            return response()->json([
                'code' => 401,
                'message' => 'Authentication required',
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

            $request->session()->remove('clauToken');
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
