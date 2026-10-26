<?php

use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('login'));

Route::get('/login', [App\Http\Controllers\AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [App\Http\Controllers\AuthController::class, 'login']);
Route::post('/logout', [App\Http\Controllers\AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

    Route::resource('branches', App\Http\Controllers\BranchController::class);
    Route::resource('users', App\Http\Controllers\UserController::class);
    Route::resource('locations', App\Http\Controllers\LocationController::class);
    Route::resource('shifts', App\Http\Controllers\ShiftController::class);
    Route::resource('patrol-checkpoints', App\Http\Controllers\PatrolCheckpointController::class);
    Route::resource('attendance', App\Http\Controllers\AttendanceController::class);
    Route::resource('izin', App\Http\Controllers\IzinController::class);
    Route::resource('reports', App\Http\Controllers\ReportController::class);
    Route::resource('audit-logs', App\Http\Controllers\AuditLogController::class);

    Route::post('/izin/{id}/approve', [App\Http\Controllers\IzinController::class, 'approve'])->name('izin.approve');
    Route::post('/izin/{id}/reject', [App\Http\Controllers\IzinController::class, 'reject'])->name('izin.reject');
    Route::post('/attendance/manual-correction', [App\Http\Controllers\AttendanceController::class, 'manualCorrection'])->name('attendance.manual-correction');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/api/logout', [App\Http\Controllers\Api\AuthApiController::class, 'logout']);
});

Route::post('/api/login', [App\Http\Controllers\Api\AuthApiController::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/api/me', [App\Http\Controllers\Api\AuthApiController::class, 'me']);
    Route::get('/api/dashboard', [App\Http\Controllers\Api\UserDashboardController::class, 'summary']);
    Route::get('/api/attendance/today', [App\Http\Controllers\Api\AttendanceApiController::class, 'today']);
    Route::post('/api/attendance/check-in', [App\Http\Controllers\Api\AttendanceApiController::class, 'checkIn']);
    Route::post('/api/attendance/check-out', [App\Http\Controllers\Api\AttendanceApiController::class, 'checkOut']);
    Route::get('/api/patrol', [App\Http\Controllers\Api\PatrolApiController::class, 'index']);
    Route::post('/api/patrol/scan', [App\Http\Controllers\Api\PatrolApiController::class, 'scan']);
    Route::post('/api/izin/request', [App\Http\Controllers\Api\IzinApiController::class, 'request']);
    Route::get('/api/izin/history', [App\Http\Controllers\Api\IzinApiController::class, 'history']);
});
