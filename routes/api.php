<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\RuangController;
use App\Http\Controllers\BookingController;

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

Route::middleware(['auth:sanctum', 'api'])
    ->group(function () {
        Route::prefix("auth")
            ->group(function () {
                Route::get('/me', [AuthController::class, "handleGetMe"]);
                Route::post('/change-password', [AuthController::class, "handleChangePassword"]);
                Route::post('/logout', [AuthController::class, "handleChangePassword"]);
            });
        Route::prefix("ruangans")
            ->group(function () {
                Route::get('/all', [RuangController::class, 'index']);
                Route::get('/{ruangan}/show', [RuangController::class, 'edit']);
                Route::post('/create', [RuangController::class, 'create']);
                Route::post('/{ruangan}/update', [RuangController::class, 'update']);
                Route::delete('/{id}/delete', [RuangController::class, 'delete']);
            });
        Route::prefix("bookings")
            ->group(function () {
                Route::get('/all', [BookingController::class, 'index']);
                Route::get('/{booking}/show', [BookingController::class, 'edit']);
                Route::post('/create', [BookingController::class, 'create']);
                Route::post('/{booking}/update', [BookingController::class, 'update']);
                Route::delete('/{id}/delete', [BookingController::class, 'delete']);
            });
    });

Route::prefix("auth")->group(function () {
    Route::post("login", [AuthController::class, "handleLogin"]);
    Route::post("register", [AuthController::class, "handleRegister"]);
});
