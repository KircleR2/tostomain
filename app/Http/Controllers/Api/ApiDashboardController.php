<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ClauService;
use Http;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class ApiDashboardController extends Controller
{
    public function index (Request $request)
    {
        $token = Session::get('clauToken');
        
        if (empty($token)) {
            return response()->json([
                'code' => 401,
                'message' => 'Authentication required',
            ])->setStatusCode(Response::HTTP_UNAUTHORIZED);
        }
        
        $response = (new ClauService())->getUserData($token);

        if ($response->successful()) {
            $responseData = $response->json();
            
            // Debug the response structure
            \Log::debug('Received user data response', ['status' => $response->status(), 'response_data' => $responseData]);

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
            \Log::warning('Unexpected API response format', ['response' => $responseData]);
            $request->session()->remove('clauToken');
            return response()->json([
                'code' => $responseData['CodRes'] ?? 500,
                'message' => $responseData['Msj'] ?? 'Unexpected API response format',
            ])->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        \Log::error('API request failed', ['status' => $response->status(), 'body' => $response->body()]);
        return response()->json([
            'message' => 'Error en la solicitud a la API',
        ])->setStatusCode($response->status());
    }

    public function store_points (Request $request)
    {
        $token = Session::get('clauToken');
        
        if (empty($token)) {
            return response()->json([
                'code' => 401,
                'message' => 'Authentication required',
            ])->setStatusCode(Response::HTTP_UNAUTHORIZED);
        }
        
        $response = (new ClauService())->getStorePoints($token);

        if ($response->successful()) {
            $responseData = $response->json();

            if (isset($responseData['CodRes']) && $responseData['CodRes'] === 0 && isset($responseData['ArrRes'])) {
                return response()->json([
                    'code' => 0,
                    'products' => $responseData['ArrRes'],
                ])->setStatusCode(Response::HTTP_OK);
            }

            $request->session()->remove('clauToken');
            return response()->json([
                'code' => $responseData['CodRes'] ?? 500,
                'message' => $responseData['Msj'] ?? 'Unexpected API response format',
            ])->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        return response()->json([
            'message' => 'Error en la solicitud a la API',
        ])->setStatusCode($response->status());
    }

    public function buy_product (Request $request)
    {
        $token = Session::get('clauToken');
        $productId = $request->input('regaloId');
        
        if (empty($token)) {
            return response()->json([
                'code' => 401,
                'message' => 'Authentication required',
            ])->setStatusCode(Response::HTTP_UNAUTHORIZED);
        }
        
        if (empty($productId)) {
            return response()->json([
                'code' => 400,
                'message' => 'Product ID is required',
            ])->setStatusCode(Response::HTTP_BAD_REQUEST);
        }
        
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
                'code' => $responseData['CodRes'] ?? 500,
                'message' => $responseData['Msj'] ?? 'Unexpected API response format',
            ])->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        return response()->json([
            'message' => 'Error en la solicitud a la API',
        ])->setStatusCode($response->status());
    }

    public function gifts (Request $request)
    {
        $token = Session::get('clauToken');
        
        if (empty($token)) {
            return response()->json([
                'code' => 401,
                'message' => 'Authentication required',
            ])->setStatusCode(Response::HTTP_UNAUTHORIZED);
        }
        
        $response = (new ClauService())->getGifts($token);

        if ($response->successful()) {
            $responseData = $response->json();

            if (isset($responseData['CodRes']) && $responseData['CodRes'] === 0 && isset($responseData['ArrRes'])) {
                return response()->json([
                    'code' => 0,
                    'gifts' => $responseData['ArrRes'],
                ])->setStatusCode(Response::HTTP_OK);
            }

            $request->session()->remove('clauToken');
            return response()->json([
                'code' => $responseData['CodRes'] ?? 500,
                'message' => $responseData['Msj'] ?? 'Unexpected API response format',
            ])->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        return response()->json([
            'message' => 'Error en la solicitud a la API',
        ])->setStatusCode($response->status());
    }
}
