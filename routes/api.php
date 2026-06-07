<?php

use App\Http\Controllers\Api\AuditLogController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\DepartmentController;
use App\Http\Controllers\Api\LocationController;
use App\Http\Controllers\Api\MachineController;
use App\Http\Controllers\Api\MachineTypeController;
use App\Http\Controllers\Api\MaintenanceRecordController;
use App\Http\Controllers\Api\MovementHistoryController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

/*
 |--------------------------------------------------------------------------
 | API Routes
 |--------------------------------------------------------------------------
 |
 | Here is where you can register API routes for your application. These
 | routes are loaded by the RouteServiceProvider and all of them will
 | be assigned to the "api" middleware group.
 |
 */

// Public routes
Route::post('/login', [AuthController::class , 'login'])->name('api.login');
// Downloadable CSV import template for machines (no auth needed)
Route::get('/machines/template', [MachineController::class , 'template']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Authentication
    Route::post('/logout', [AuthController::class , 'logout']);
    Route::get('/me', [AuthController::class , 'me']);

    // Dashboard
    Route::get('/dashboard', [DashboardController::class , 'index']);

    // Machines
    Route::post('/machines/import', [MachineController::class , 'import'])->middleware('role:admin,it');
    Route::post('/machines/bulk-delete', [MachineController::class , 'bulkDelete'])->middleware('role:admin');
    Route::post('/machines/delete-all', [MachineController::class , 'deleteAll'])->middleware('role:admin');
    Route::get('/machines/statistics', [MachineController::class , 'statistics']);
    Route::get('/machines/{machine}/maintenance-records', [MachineController::class , 'maintenanceRecords']);
    Route::get('/machines/{machine}/movement-histories', [MachineController::class , 'movementHistories']);
    Route::apiResource('machines', MachineController::class);

    // Maintenance Records
    Route::get('/maintenance-records/upcoming', [MaintenanceRecordController::class , 'upcoming']);
    Route::apiResource('maintenance-records', MaintenanceRecordController::class);

    // Movement History (read-only)
    Route::get('/movement-histories', [MovementHistoryController::class , 'index']);
    Route::get('/movement-histories/{movementHistory}', [MovementHistoryController::class , 'show']);

    // Lookup Tables
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('machine-types', MachineTypeController::class);
    Route::apiResource('departments', DepartmentController::class);
    Route::apiResource('locations', LocationController::class);

    // Admin only routes
    Route::middleware('role:admin')->group(function () {
            // User Management
            Route::apiResource('users', UserController::class);

            // Audit Logs
            Route::get('/audit-logs', [AuditLogController::class , 'index']);
            Route::delete('/audit-logs/delete-all', [AuditLogController::class , 'deleteAll']);
            Route::get('/audit-logs/{auditLog}', [AuditLogController::class , 'show']);
            Route::delete('/audit-logs/{auditLog}', [AuditLogController::class , 'destroy']);
        }
        );
    });