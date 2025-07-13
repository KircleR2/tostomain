<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DiagnosticController;
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

// Direct session check route - no middleware
Route::get('/check-session', function(\Illuminate\Http\Request $request) {
    $sessionData = [
        'has_session' => $request->hasSession(),
        'session_id' => $request->hasSession() ? $request->session()->getId() : null,
        'clau_token_exists' => $request->hasSession() ? $request->session()->has('clauToken') : false,
        'clau_token_length' => $request->hasSession() && $request->session()->has('clauToken') ? 
            strlen($request->session()->get('clauToken')) : 0,
        'cookie_token_exists' => $request->hasCookie('clau_token'),
        'cookie_token_length' => $request->hasCookie('clau_token') ? strlen($request->cookie('clau_token')) : 0,
        'all_cookies' => array_keys($request->cookies->all()),
        'all_session' => $request->hasSession() ? array_keys($request->session()->all()) : [],
    ];
    
    return response()->json($sessionData);
});

// Test cookie route
Route::get('/test-cookie', function(\Illuminate\Http\Request $request) {
    // Get domain for cookie
    $domain = parse_url(config('app.url'), PHP_URL_HOST);
    
    // If domain starts with www, make cookie available to subdomains
    if (strpos($domain, 'www.') === 0) {
        $domain = substr($domain, 4); // Remove www.
    }
    
    // For localhost or IP testing
    if ($domain === 'localhost' || filter_var($domain, FILTER_VALIDATE_IP)) {
        $domain = null;
    }
    
    $response = response()->json([
        'message' => 'Test cookie set',
        'domain' => $domain,
        'app_url' => config('app.url'),
        'request_secure' => $request->secure(),
        'request_host' => $request->getHost(),
    ]);
    
    return $response->withCookie(cookie(
        'test_cookie',      // name
        'test_value',       // value
        60,                 // minutes
        '/',                // path
        $domain,            // domain
        null,               // secure (null = auto-detect)
        false,              // httpOnly
        true,               // raw
        'lax'               // sameSite
    ));
});

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
Route::prefix('diagnostic')->group(function () {
    Route::get('/session', [DiagnosticController::class, 'showSession']);
    Route::get('/api-test', [DiagnosticController::class, 'testApi']);
    Route::get('/clau-test', [DiagnosticController::class, 'testClauApi']);
    Route::get('/dashboard-api', [DiagnosticController::class, 'testDashboardApi']);
    Route::get('/logs', [DiagnosticController::class, 'showLogs']);
    Route::get('/env', [DiagnosticController::class, 'showEnv']);
});
