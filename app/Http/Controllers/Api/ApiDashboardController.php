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
        $token = Session::get('clauToken');
        
        if (empty($token)) {
            Log::warning('Dashboard access attempted without token');
            return response()->json([
                'code' => 401,
                'message' => 'Authentication required',
            ])->setStatusCode(Response::HTTP_UNAUTHORIZED);
        }
        
        Log::debug('Fetching user data from Clau API', [
            'token_length' => strlen($token)
        ]);
        
        $response = (new ClauService())->getUserData($token);

        if ($response->successful()) {
            $responseData = $response->json();
            
            Log::debug('Received user data response', [
                'status' => $response->status(),
                'response_data' => $responseData
            ]);

            // Check if the response contains the expected keys
            if (isset($responseData['CodRes']) && $responseData['CodRes'] === 0 && isset($responseData['ArrRes'])) {
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
            
            // If we get here, the response format is unexpected
            Log::warning('Unexpected API response format', [
                'response' => $responseData
            ]);
            
            $request->session()->remove('clauToken');
            return response()->json([
                'code' => $responseData['CodRes'] ?? 500,
                'message' => $responseData['Msj'] ?? 'Unexpected API response format',
            ])->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        Log::error('Failed to fetch user data', [
            'status' => $response->status(),
            'body' => $response->body()
        ]);

        return response()->json([
            'message' => 'Error en la solicitud a la API',
        ])->setStatusCode($response->status());
    }

    public function store_points (Request $request)
    {
        $token = Session::get('clauToken');
        
        if (empty($token)) {
            Log::warning('Store points access attempted without token');
            return response()->json([
                'code' => 401,
                'message' => 'Authentication required',
            ])->setStatusCode(Response::HTTP_UNAUTHORIZED);
        }
        
        Log::debug('Fetching store points from Clau API', [
            'token_length' => strlen($token)
        ]);
        
        $response = (new ClauService())->getStorePoints($token);

        if ($response->successful()) {
            $responseData = $response->json();
            
            Log::debug('Received store points response', [
                'status' => $response->status(),
                'response_keys' => array_keys($responseData)
            ]);

            if (isset($responseData['CodRes']) && $responseData['CodRes'] === 0 && isset($responseData['ArrRes'])) {
                return response()->json([
                    'code' => 0,
                    'products' => $responseData['ArrRes'],
                ])->setStatusCode(Response::HTTP_OK);
            }

            Log::warning('Unexpected store points API response format', [
                'response' => $responseData
            ]);
            
            $request->session()->remove('clauToken');
            return response()->json([
                'code' => $responseData['CodRes'] ?? 500,
                'message' => $responseData['Msj'] ?? 'Unexpected API response format',
            ])->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        Log::error('Failed to fetch store points', [
            'status' => $response->status(),
            'body' => $response->body()
        ]);

        return response()->json([
            'message' => 'Error en la solicitud a la API',
        ])->setStatusCode($response->status());
    }

    public function buy_product (Request $request)
    {
        $token = Session::get('clauToken');
        $productId = $request->input('regaloId');
        
        if (empty($token)) {
            Log::warning('Buy product attempted without token');
            return response()->json([
                'code' => 401,
                'message' => 'Authentication required',
            ])->setStatusCode(Response::HTTP_UNAUTHORIZED);
        }
        
        if (empty($productId)) {
            Log::warning('Buy product attempted without product ID');
            return response()->json([
                'code' => 400,
                'message' => 'Product ID is required',
            ])->setStatusCode(Response::HTTP_BAD_REQUEST);
        }
        
        Log::debug('Buying product from Clau API', [
            'token_length' => strlen($token),
            'product_id' => $productId
        ]);
        
        $response = (new ClauService())->buyProduct($token, $productId);

        if ($response->successful()) {
            $responseData = $response->json();
            
            Log::debug('Received buy product response', [
                'status' => $response->status(),
                'response' => $responseData
            ]);

            if (isset($responseData['CodRes']) && $responseData['CodRes'] === 0) {
                return response()->json([
                    'code' => 0,
                    'message' => '¡Producto canjeado correctamente!',
                ])->setStatusCode(Response::HTTP_OK);
            }

            Log::warning('Failed to buy product', [
                'response' => $responseData,
                'product_id' => $productId
            ]);
            
            $request->session()->remove('clauToken');
            return response()->json([
                'code' => $responseData['CodRes'] ?? 500,
                'message' => $responseData['Msj'] ?? 'Unexpected API response format',
            ])->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        Log::error('Failed to buy product - API error', [
            'status' => $response->status(),
            'body' => $response->body(),
            'product_id' => $productId
        ]);

        return response()->json([
            'message' => 'Error en la solicitud a la API',
        ])->setStatusCode($response->status());
    }

    public function gifts (Request $request)
    {
        $token = Session::get('clauToken');
        
        if (empty($token)) {
            Log::warning('Gifts access attempted without token');
            return response()->json([
                'code' => 401,
                'message' => 'Authentication required',
            ])->setStatusCode(Response::HTTP_UNAUTHORIZED);
        }
        
        Log::debug('Fetching gifts from Clau API', [
            'token_length' => strlen($token)
        ]);
        
        $response = (new ClauService())->getGifts($token);

        if ($response->successful()) {
            $responseData = $response->json();
            
            Log::debug('Received gifts response', [
                'status' => $response->status(),
                'response_keys' => array_keys($responseData)
            ]);

            if (isset($responseData['CodRes']) && $responseData['CodRes'] === 0 && isset($responseData['ArrRes'])) {
                return response()->json([
                    'code' => 0,
                    'gifts' => $responseData['ArrRes'],
                ])->setStatusCode(Response::HTTP_OK);
            }

            Log::warning('Unexpected gifts API response format', [
                'response' => $responseData
            ]);
            
            $request->session()->remove('clauToken');
            return response()->json([
                'code' => $responseData['CodRes'] ?? 500,
                'message' => $responseData['Msj'] ?? 'Unexpected API response format',
            ])->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        Log::error('Failed to fetch gifts', [
            'status' => $response->status(),
            'body' => $response->body()
        ]);

        return response()->json([
            'message' => 'Error en la solicitud a la API',
        ])->setStatusCode($response->status());
    }
}
