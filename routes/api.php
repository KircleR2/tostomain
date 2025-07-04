<?php

use App\Http\Controllers\Api\ApiAuthController;
use App\Http\Controllers\Api\ApiDashboardController;
use App\Http\Controllers\Api\WebhookController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public routes
Route::post('/login', [ApiAuthController::class, 'login']);
Route::post('/register', [ApiAuthController::class, 'register']);
Route::post('/recovery-password', [ApiAuthController::class, 'recovery_password']);
Route::post('/webhook', [WebhookController::class, 'handleWebhook']);

// Protected routes
Route::middleware(['api', 'session'])->group(function () {
    Route::post('/dashboard', [ApiDashboardController::class, 'index']);
    Route::post('/store-points', [ApiDashboardController::class, 'store_points']);
    Route::post('/gifts', [ApiDashboardController::class, 'gifts']);
    Route::post('/buy-product', [ApiDashboardController::class, 'buy_product']);
});


