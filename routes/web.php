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
// ── Notification Routes ───────────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/notifications/{notification}/read', function (App\Models\Notification $notification) {
        $notification->update(['is_read' => true]);
        return redirect($notification->link ?? back()->getTargetUrl());
    })->name('notifications.read');

    Route::get('/notifications/read-all', function () {
        auth()->user()->notifications()->update(['is_read' => true]);
        return redirect()->back()->with('success', 'All notifications marked as read!');
    })->name('notifications.readall');
});

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
    Route::get('/reports', [App\Http\Controllers\ReportController::class, 'index'])->name('reports');
    Route::get('/stock-items', [App\Http\Controllers\StockItemController::class, 'index'])->name('stock-items');
    Route::post('/stock-items', [App\Http\Controllers\StockItemController::class, 'store'])->name('stock-items.store');
    Route::put('/stock-items/{stockItem}', [App\Http\Controllers\StockItemController::class, 'update'])->name('stock-items.update');
    Route::delete('/stock-items/{stockItem}', [App\Http\Controllers\StockItemController::class, 'destroy'])->name('stock-items.destroy');
    Route::get('/payments/{payment}/download', function(App\Models\Payment $payment) {
        $path = storage_path('app/public/' . $payment->receipt_path);
        return response()->download($path, $payment->receipt_original_name);
    })->name('receipt.download');
});

// ── Employee Routes ───────────────────────────────────────────────
Route::middleware(['auth', 'role:employee'])->prefix('employee')->name('employee.')->group(function () {
    Route::get('/dashboard',                    [EmployeeController::class, 'dashboard'])->name('dashboard');
    Route::get('/requisitions',                 [EmployeeController::class, 'index'])->name('requisitions');
    Route::get('/requisitions/create',          [EmployeeController::class, 'create'])->name('requisitions.create');
    Route::post('/requisitions',                [EmployeeController::class, 'store'])->name('requisitions.store');
    Route::get('/requisitions/{requisition}',   [EmployeeController::class, 'show'])->name('requisitions.show');
    Route::get('/stock-items', [App\Http\Controllers\StockItemController::class, 'available'])->name('stock-items');
});

// ── Accountant Routes ─────────────────────────────────────────────
Route::middleware(['auth', 'role:accountant'])->prefix('accountant')->name('accountant.')->group(function () {
    Route::get('/dashboard',                                        [AccountantController::class, 'dashboard'])->name('dashboard');
    Route::get('/requisitions',                                     [AccountantController::class, 'requisitions'])->name('requisitions');
    Route::post('/requisitions/{requisition}/status',               [AccountantController::class, 'updateStatus'])->name('requisitions.status');
    Route::get('/requisitions/{requisition}/payment',               [AccountantController::class, 'showPaymentForm'])->name('requisitions.payment');
    Route::post('/requisitions/{requisition}/payment',              [AccountantController::class, 'uploadPayment'])->name('requisitions.payment.store');
});