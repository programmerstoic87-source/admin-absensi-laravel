<?php

use Illuminate\Support\Facades\Route;

Route::middleware('api')->group(function () {
    Route::post('/api/login', [App\Http\Controllers\Api\AuthApiController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/api/me', [App\Http\Controllers\Api\AuthApiController::class, 'me']);
        Route::post('/api/logout', [App\Http\Controllers\Api\AuthApiController::class, 'logout']);
        Route::get('/api/dashboard', [App\Http\Controllers\Api\UserDashboardController::class, 'summary']);
        Route::get('/api/attendance/today', [App\Http\Controllers\Api\AttendanceApiController::class, 'today']);
        Route::post('/api/attendance/check-in', [App\Http\Controllers\Api\AttendanceApiController::class, 'checkIn']);
        Route::post('/api/attendance/check-out', [App\Http\Controllers\Api\AttendanceApiController::class, 'checkOut']);
        Route::get('/api/patrol', [App\Http\Controllers\Api\PatrolApiController::class, 'index']);
        Route::post('/api/patrol/scan', [App\Http\Controllers\Api\PatrolApiController::class, 'scan']);
        Route::post('/api/izin/request', [App\Http\Controllers\Api\IzinApiController::class, 'request']);
        Route::get('/api/izin/history', [App\Http\Controllers\Api\IzinApiController::class, 'history']);
    });
});
