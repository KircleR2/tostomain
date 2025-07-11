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
    
    return response($content)
        ->header('Content-Type', 'application/json')
        ->header('Access-Control-Allow-Origin', '*')
        ->header('Access-Control-Allow-Methods', 'GET, OPTIONS')
        ->header('Access-Control-Allow-Headers', 'Origin, X-Requested-With, Content-Type, Accept');
});
