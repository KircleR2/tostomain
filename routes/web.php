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
Route::get('nueva-sucursal', [FrontController::class, 'newBranch'])->name('front.new-branch');

Route::get('login', [AuthController::class, 'login'])->name('auth.login');
Route::get('register', [AuthController::class, 'register'])->name('auth.register');
Route::get('recovery-password', [AuthController::class, 'recoveryPassword'])->name('auth.recovery-password');

Route::group(['middleware' => ['clau.token']], static function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
});

Route::group(['as' => 'front.menu.', 'prefix' => 'menu'], static function () {
    Route::get('/', [FrontController::class, 'menu'])->name('index');
    Route::get('/{menu}', [FrontController::class, 'menuShow'])
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
        
        // View application logs
        Route::get('/logs', function () {
            $logPath = storage_path('logs/laravel.log');
            
            if (!file_exists($logPath)) {
                return response()->json([
                    'error' => 'Log file not found',
                    'path' => $logPath
                ], 404);
            }
            
            // Get the last 500 lines of the log file
            $logs = [];
            $file = new \SplFileObject($logPath, 'r');
            $file->seek(PHP_INT_MAX); // Seek to the end of file
            $totalLines = $file->key(); // Get total lines
            
            $linesToRead = min(500, $totalLines);
            $startLine = max(0, $totalLines - $linesToRead);
            
            $file->seek($startLine);
            
            while (!$file->eof()) {
                $line = $file->current();
                if (trim($line) !== '') {
                    $logs[] = $line;
                }
                $file->next();
            }
            
            // Format as pre-formatted text for browser viewing
            $content = implode("\n", $logs);
            return response('<pre>' . htmlspecialchars($content) . '</pre>')
                ->header('Content-Type', 'text/html');
        });
        
        // View authentication-related logs only
        Route::get('/auth-logs', function () {
            $logPath = storage_path('logs/laravel.log');
            
            if (!file_exists($logPath)) {
                return response()->json([
                    'error' => 'Log file not found',
                    'path' => $logPath
                ], 404);
            }
            
            // Search for authentication-related log entries
            $authLogs = [];
            $file = new \SplFileObject($logPath, 'r');
            
            while (!$file->eof()) {
                $line = $file->current();
                // Filter for auth-related log entries
                if (preg_match('/(login|auth|token|clau|session)/i', $line)) {
                    $authLogs[] = $line;
                }
                $file->next();
            }
            
            // Format as pre-formatted text for browser viewing
            $content = implode("\n", $authLogs);
            return response('<pre>' . htmlspecialchars($content) . '</pre>')
                ->header('Content-Type', 'text/html');
        });
    });
}
