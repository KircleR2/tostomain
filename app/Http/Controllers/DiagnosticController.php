<?php

namespace App\Http\Controllers;

use App\Services\ClauService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;

class DiagnosticController extends Controller
{
    public function showSession(Request $request)
    {
        $sessionData = [
            'has_session' => $request->hasSession(),
            'session_id' => $request->session()->getId(),
            'clau_token_exists' => $request->session()->has('clauToken'),
            'clau_token_length' => $request->session()->has('clauToken') ? strlen($request->session()->get('clauToken')) : 0,
            'session_driver' => config('session.driver'),
            'session_lifetime' => config('session.lifetime'),
            'cookie_token_exists' => $request->hasCookie('clau_token'),
            'cookie_token_length' => $request->hasCookie('clau_token') ? strlen($request->cookie('clau_token')) : 0,
            'all_cookies' => $request->cookies->all(),
        ];

        return response()->json($sessionData);
    }

    public function testApi(Request $request)
    {
        try {
            $apiUrl = config('clau.api_url');
            $response = Http::withoutVerifying()->get($apiUrl);
            
            return response()->json([
                'api_url' => $apiUrl,
                'status' => $response->status(),
                'successful' => $response->successful(),
                'body' => $response->body(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
        }
    }

    public function testClauApi(Request $request)
    {
        try {
            $clauService = new ClauService();
            $result = $clauService->testConnection();
            
            return response()->json([
                'api_url' => config('clau.api_url'),
                'appid' => config('clau.appid'),
                'api_auth_key_length' => strlen(config('clau.api_auth_key')),
                'api_pos_key_length' => strlen(config('clau.api_pos_key')),
                'test_result' => $result,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
        }
    }

    public function testDashboardApi(Request $request)
    {
        $token = Session::get('clauToken');
        
        if (empty($token)) {
            return response()->json([
                'error' => true,
                'message' => 'No authentication token found in session',
            ]);
        }
        
        try {
            $clauService = new ClauService();
            $response = $clauService->getUserData($token);
            
            return response()->json([
                'status' => $response->status(),
                'successful' => $response->successful(),
                'body' => $response->json(),
                'headers' => $response->headers(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
        }
    }

    public function showLogs(Request $request)
    {
        try {
            $logPath = storage_path('logs/laravel.log');
            if (!file_exists($logPath)) {
                return response()->json([
                    'error' => true,
                    'message' => 'Log file not found',
                ]);
            }
            
            $logs = file_get_contents($logPath);
            $lastLines = array_slice(explode("\n", $logs), -100);
            
            return response()->json([
                'log_path' => $logPath,
                'last_lines' => $lastLines,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
        }
    }

    public function showEnv(Request $request)
    {
        $envVars = [
            'APP_ENV' => env('APP_ENV'),
            'APP_DEBUG' => env('APP_DEBUG'),
            'APP_URL' => env('APP_URL'),
            'SESSION_DRIVER' => env('SESSION_DRIVER'),
            'SESSION_LIFETIME' => env('SESSION_LIFETIME'),
            'CLAU_API_URL' => env('CLAU_API_URL'),
            'CLAU_APPID' => env('CLAU_APPID'),
            'CLAU_API_AUTH_KEY_LENGTH' => env('CLAU_API_AUTH_KEY') ? strlen(env('CLAU_API_AUTH_KEY')) : 0,
            'CLAU_API_POS_KEY_LENGTH' => env('CLAU_API_POS_KEY') ? strlen(env('CLAU_API_POS_KEY')) : 0,
            'CLAU_API_KEY_PROVIDER' => env('CLAU_API_KEY_PROVIDER'),
            'CLAU_ORIGIN' => env('CLAU_ORIGIN'),
        ];
        
        return response()->json($envVars);
    }
} 