<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\AccountantController;
use App\Http\Controllers\RequisitionController;

Route::get('/', function () {
    return redirect()->route('login');
});

// ── Auth Routes (provided by Breeze) ──────────────────────────────
require __DIR__.'/auth.php';

// ── Redirect after login based on role ───────────────────────────
Route::middleware('auth')->get('/dashboard', function () {
    $role = auth()->user()->role;
    return match($role) {
        'admin'      => redirect()->route('admin.dashboard'),
        'accountant' => redirect()->route('accountant.dashboard'),
        default      => redirect()->route('employee.dashboard'),
    };
})->name('dashboard');

// ── Admin Routes ──────────────────────────────────────────────────
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard',              [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/users',                  [AdminController::class, 'users'])->name('users');
    Route::get('/users/create',           [AdminController::class, 'createUser'])->name('users.create');
    Route::post('/users',                 [AdminController::class, 'storeUser'])->name('users.store');
    Route::post('/users/{user}/toggle',   [AdminController::class, 'toggleUser'])->name('users.toggle');
    Route::get('/departments',            [AdminController::class, 'departments'])->name('departments');
    Route::post('/departments',           [AdminController::class, 'storeDepartment'])->name('departments.store');
    Route::get('/requisitions',           [AdminController::class, 'requisitions'])->name('requisitions');
    Route::post('/requisitions/{requisition}/approve', [RequisitionController::class, 'approve'])->name('requisitions.approve');
    Route::post('/requisitions/{requisition}/reject',  [RequisitionController::class, 'reject'])->name('requisitions.reject');
    Route::get('/requisitions/{requisition}',          [RequisitionController::class, 'show'])->name('requisitions.show');
});

// ── Employee Routes ───────────────────────────────────────────────
Route::middleware(['auth', 'role:employee'])->prefix('employee')->name('employee.')->group(function () {
    Route::get('/dashboard',                    [EmployeeController::class, 'dashboard'])->name('dashboard');
    Route::get('/requisitions',                 [EmployeeController::class, 'index'])->name('requisitions');
    Route::get('/requisitions/create',          [EmployeeController::class, 'create'])->name('requisitions.create');
    Route::post('/requisitions',                [EmployeeController::class, 'store'])->name('requisitions.store');
    Route::get('/requisitions/{requisition}',   [EmployeeController::class, 'show'])->name('requisitions.show');
});

// ── Accountant Routes ─────────────────────────────────────────────
Route::middleware(['auth', 'role:accountant'])->prefix('accountant')->name('accountant.')->group(function () {
    Route::get('/dashboard',                                        [AccountantController::class, 'dashboard'])->name('dashboard');
    Route::get('/requisitions',                                     [AccountantController::class, 'requisitions'])->name('requisitions');
    Route::post('/requisitions/{requisition}/status',               [AccountantController::class, 'updateStatus'])->name('requisitions.status');
    Route::get('/requisitions/{requisition}/payment',               [AccountantController::class, 'showPaymentForm'])->name('requisitions.payment');
    Route::post('/requisitions/{requisition}/payment',              [AccountantController::class, 'uploadPayment'])->name('requisitions.payment.store');
});