<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DiagnosticController;
use App\Http\Controllers\FrontController;
use App\Http\Middleware\ClauTokenMiddleware;
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
    $cookieName = ClauTokenMiddleware::COOKIE_NAME;
    $allCookies = $request->cookies->all();
    
    // Direct check for cookie value
    $cookieValue = isset($allCookies[$cookieName]) ? $allCookies[$cookieName] : null;
    
    $sessionData = [
        'has_session' => $request->hasSession(),
        'session_id' => $request->hasSession() ? $request->session()->getId() : null,
        'clau_token_exists' => $request->hasSession() ? $request->session()->has('clauToken') : false,
        'clau_token_length' => $request->hasSession() && $request->session()->has('clauToken') ? 
            strlen($request->session()->get('clauToken')) : 0,
        'cookie_token_exists' => !empty($cookieValue),
        'cookie_token_length' => !empty($cookieValue) ? strlen($cookieValue) : 0,
        'cookie_name' => $cookieName,
        'all_cookies' => array_keys($allCookies),
        'all_cookie_values' => $allCookies,
        'all_session' => $request->hasSession() ? array_keys($request->session()->all()) : [],
    ];
    
    // If we have a cookie but no session token, transfer it
    if (!empty($cookieValue) && $request->hasSession() && !$request->session()->has('clauToken')) {
        $request->session()->put('clauToken', $cookieValue);
        $request->session()->save();
        $sessionData['action_taken'] = 'Transferred cookie token to session';
        $sessionData['clau_token_exists'] = true;
        $sessionData['clau_token_length'] = strlen($cookieValue);
    }
    
    return response()->json($sessionData);
});

// Direct cookie setter route
Route::get('/set-auth-cookie', function(\Illuminate\Http\Request $request) {
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
    
    // Generate a test token
    $testToken = 'test_token_' . time();
    
    // Store in session if available
    if ($request->hasSession()) {
        $request->session()->put('clauToken', $testToken);
        $request->session()->save();
    }
    
    $response = response()->json([
        'message' => 'Auth cookie set',
        'domain' => $domain,
        'app_url' => config('app.url'),
        'request_secure' => $request->secure(),
        'request_host' => $request->getHost(),
        'token' => $testToken,
    ]);
    
    return $response->withCookie(cookie(
        ClauTokenMiddleware::COOKIE_NAME, // name
        $testToken,           // value
        120,                  // minutes
        '/',                  // path
        $domain,              // domain
        null,                 // secure (null = auto-detect)
        false,                // httpOnly
        false,                // raw - set to false to avoid encoding issues
        'lax'                 // sameSite
    ));
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
        false,              // raw - set to false to avoid encoding issues
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
