<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FrontController;
use App\Values\MenuValues;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;
use App\Services\ClauService;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [FrontController::class, 'index'])->name('front.index');
Route::get('lang/{locale}', [FrontController::class, 'lang'])->name('front.locale');
Route::get('nueva-sucursal-altaplaza', [FrontController::class, 'new_branch'])->name('front.new-branch');

Route::group(['middleware' => ['clau.redirect']], static function () {
    Route::get('/login', [AuthController::class, 'login'])->name('auth.login');
    Route::get('/registro-club-elite', [AuthController::class, 'register'])->name('auth.register');
    Route::get('/recuperar', [AuthController::class, 'recovery_password'])->name('auth.recovery-password');
});

Route::get('/logout', [AuthController::class, 'logout'])->name('auth.logout');
Route::get('/dashboard', DashboardController::class)
    ->middleware('clau.auth')
    ->name('back.dashboard');

Route::group(['as' => 'front.menu.', 'prefix' => 'menu'], static function () {
    Route::get('/', [FrontController::class, 'menu'])->name('index');
    Route::get('/{menu}', [FrontController::class, 'menu'])
        ->name('show')
        ->whereIn('menu', collect(MenuValues::getList())->pluck('value')->toArray());
});

// Diagnostic routes
if (config('app.debug')) {
    Route::prefix('diagnostic')->group(function () {
        // Session diagnostic
        Route::get('/session', function (\Illuminate\Http\Request $request) {
            return response()->json([
                'has_session' => $request->hasSession(),
                'session_id' => $request->hasSession() ? $request->session()->getId() : null,
                'session_driver' => config('session.driver'),
                'session_keys' => $request->hasSession() ? array_keys($request->session()->all()) : [],
                'clau_token_exists' => $request->hasSession() && $request->session()->has('clauToken'),
                'clau_token_length' => $request->hasSession() && $request->session()->has('clauToken') ? 
                    strlen($request->session()->get('clauToken')) : 0,
                'cookies' => array_keys($request->cookies->all()),
                'cookie_token_exists' => $request->hasCookie('clau_token'),
            ]);
        });
        
        // API connection test
        Route::get('/api-test', function () {
            try {
                $clauService = app(ClauService::class);
                $testEndpoint = config('clau.api_url');
                
                $headers = [
                    'Content-Type' => 'application/json',
                    'APPID' => config('clau.appid'),
                ];
                
                $response = \Illuminate\Support\Facades\Http::withoutVerifying()
                    ->withHeaders($headers)
                    ->get($testEndpoint);
                
                return response()->json([
                    'api_url' => $testEndpoint,
                    'status' => $response->status(),
                    'headers' => $response->headers(),
                    'body' => substr($response->body(), 0, 500) . '...',
                    'clau_config' => [
                        'api_url' => config('clau.api_url'),
                        'appid' => config('clau.appid'),
                        'origin' => config('clau.origin'),
                    ]
                ]);
            } catch (\Exception $e) {
                Log::error('API test failed', [
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                
                return response()->json([
                    'error' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'clau_config' => [
                        'api_url' => config('clau.api_url'),
                        'appid' => config('clau.appid'),
                    ]
                ], 500);
            }
        });
        
        // Clau API test
        Route::get('/clau-test', function () {
            try {
                $clauService = app(ClauService::class);
                $result = $clauService->testConnection();
                
                return response()->json($result);
            } catch (\Exception $e) {
                Log::error('Clau API test failed', [
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                
                return response()->json([
                    'error' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ], 500);
            }
        });
        
        // Dashboard API test
        Route::get('/dashboard-api', function (\Illuminate\Http\Request $request) {
            try {
                $token = $request->session()->get('clauToken');
                if (empty($token)) {
                    $token = $request->cookie('clau_token');
                }
                
                if (empty($token)) {
                    return response()->json([
                        'error' => 'No token found in session or cookie',
                        'session_id' => $request->session()->getId(),
                        'session_keys' => array_keys($request->session()->all()),
                        'cookies' => array_keys($request->cookies->all()),
                    ], 401);
                }
                
                $clauService = app(ClauService::class);
                $response = $clauService->getUserData($token);
                
                return response()->json([
                    'token_length' => strlen($token),
                    'api_response_status' => $response->status(),
                    'api_response_successful' => $response->successful(),
                    'api_response_body' => $response->json(),
                    'api_response_headers' => $response->headers(),
                ]);
            } catch (\Exception $e) {
                Log::error('Dashboard API test failed', [
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                
                return response()->json([
                    'error' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ], 500);
            }
        });
        
        // Environment variables check
        Route::get('/env', function () {
            return response()->json([
                'app' => [
                    'name' => config('app.name'),
                    'env' => config('app.env'),
                    'debug' => config('app.debug'),
                    'url' => config('app.url'),
                ],
                'session' => [
                    'driver' => config('session.driver'),
                    'lifetime' => config('session.lifetime'),
                    'secure' => config('session.secure'),
                    'same_site' => config('session.same_site'),
                ],
                'clau' => [
                    'api_url' => config('clau.api_url'),
                    'appid' => config('clau.appid'),
                    'origin' => config('clau.origin'),
                    'api_keys_set' => !empty(config('clau.api_auth_key')) && !empty(config('clau.api_pos_key')),
                ],
            ]);
        });
        
        // View logs
        Route::get('/logs', function () {
            $logPath = storage_path('logs/laravel.log');
            $logContent = file_exists($logPath) ? file_get_contents($logPath) : 'Log file not found';
            $logLines = array_slice(explode("\n", $logContent), -500); // Get last 500 lines
            return response($logLines, 200)->header('Content-Type', 'text/plain');
        });
    });
}
