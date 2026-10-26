<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

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
