<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FrontController;
use App\Values\MenuValues;
use Illuminate\Support\Facades\Route;

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
Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout.post');
Route::get('/dashboard', DashboardController::class)
    ->middleware('clau.auth')
    ->name('back.dashboard');

Route::group(['as' => 'front.menu.', 'prefix' => 'menu'], static function () {
    Route::get('/', [FrontController::class, 'menu'])->name('index');
    Route::get('/{menu}', [FrontController::class, 'menu'])
        ->name('show')
        ->whereIn('menu', collect(MenuValues::getList())->pluck('value')->toArray());
});

// Special route for manifest.json with CORS headers
Route::get('/images/favicon/manifest.json', function () {
    $path = public_path('images/favicon/manifest.json');
    $content = file_get_contents($path);
    
    $response = response($content)->header('Content-Type', 'application/json');
    
    // Get the origin from the request
    $origin = request()->header('Origin');
    
    // List of allowed domains
    $allowedOrigins = [
        'https://www.tostocoffee.com',
        'https://tostocoffee.com',
        'https://tostomain-achxn.ondigitalocean.app',
        'http://localhost:8000',
        'http://localhost'
    ];
    
    // If the origin is in our allowed list, set it as the allowed origin
    if (in_array($origin, $allowedOrigins)) {
        $response->header('Access-Control-Allow-Origin', $origin);
    } else {
        // Otherwise, use a wildcard (or default to main domain if you prefer)
        $response->header('Access-Control-Allow-Origin', '*');
    }
    
    // Add other CORS headers
    $response->header('Access-Control-Allow-Methods', 'GET, OPTIONS');
    $response->header('Access-Control-Allow-Headers', 'Origin, X-Requested-With, Content-Type, Accept');
    
    return $response;
});
