<?php

use App\Http\Controllers\Web\AuditLogController;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\DepartmentController;
use App\Http\Controllers\Web\LocationController;
use App\Http\Controllers\Web\MachineController;
use App\Http\Controllers\Web\MachineTypeController;
use App\Http\Controllers\Web\MaintenanceRecordController;
use App\Http\Controllers\Web\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::get('/machines/template', [MachineController::class, 'template'])->name('machines.template');

Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/machines', [MachineController::class, 'index'])->name('machines.index');
    Route::get('/machines/create', [MachineController::class, 'createCategory'])->name('machines.create');
    Route::get('/machines/create/category/{category}', [MachineController::class, 'createType'])->name('machines.create.type');
    Route::get('/machines/create/category/{category}/type/{machineType}', [MachineController::class, 'createForm'])->name('machines.create.form');
    Route::post('/machines', [MachineController::class, 'store'])->name('machines.store');
    Route::post('/machines/import', [MachineController::class, 'import'])->name('machines.import');
    Route::post('/machines/bulk-delete', [MachineController::class, 'bulkDelete'])->name('machines.bulk-delete');
    Route::delete('/machines/delete-all', [MachineController::class, 'deleteAll'])->name('machines.delete-all');
    Route::get('/machines/{machine}', [MachineController::class, 'show'])->name('machines.show');
    Route::get('/machines/{machine}/edit', [MachineController::class, 'edit'])->name('machines.edit');
    Route::put('/machines/{machine}', [MachineController::class, 'update'])->name('machines.update');
    Route::delete('/machines/{machine}', [MachineController::class, 'destroy'])->name('machines.destroy');

    Route::middleware('role:admin')->group(function () {
        Route::get('/maintenance', [MaintenanceRecordController::class, 'index'])->name('maintenance.index');
        Route::post('/maintenance', [MaintenanceRecordController::class, 'store'])->name('maintenance.store');

        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

        Route::get('/audit-logs', [AuditLogController::class, 'index'])->name('audit-logs.index');
        Route::delete('/audit-logs/delete-all', [AuditLogController::class, 'deleteAll'])->name('audit-logs.delete-all');
        Route::delete('/audit-logs/{auditLog}', [AuditLogController::class, 'destroy'])->name('audit-logs.destroy');
    });

    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/categories', [MachineTypeController::class, 'index'])->name('categories');
        Route::post('/categories', [MachineTypeController::class, 'store'])->name('categories.store');
        Route::put('/categories/{machineType}', [MachineTypeController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{machineType}', [MachineTypeController::class, 'destroy'])->name('categories.destroy');

        Route::get('/departments', [DepartmentController::class, 'index'])->name('departments');
        Route::post('/departments', [DepartmentController::class, 'store'])->name('departments.store');
        Route::put('/departments/{department}', [DepartmentController::class, 'update'])->name('departments.update');
        Route::delete('/departments/{department}', [DepartmentController::class, 'destroy'])->name('departments.destroy');

        Route::get('/locations', [LocationController::class, 'index'])->name('locations');
        Route::post('/locations', [LocationController::class, 'store'])->name('locations.store');
        Route::put('/locations/{location}', [LocationController::class, 'update'])->name('locations.update');
        Route::delete('/locations/{location}', [LocationController::class, 'destroy'])->name('locations.destroy');
    });
});
