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

// ── Profile Routes ────────────────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/profile/settings',        [App\Http\Controllers\ProfileController::class, 'index'])->name('profile.settings');
    Route::post('/profile/update-info',    [App\Http\Controllers\ProfileController::class, 'updateInfo'])->name('profile.update-info');
    Route::post('/profile/update-password',[App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('profile.update-password');
    Route::post('/profile/update-photo',   [App\Http\Controllers\ProfileController::class, 'updatePhoto'])->name('profile.update-photo');
    Route::delete('/profile/delete-photo', [App\Http\Controllers\ProfileController::class, 'deletePhoto'])->name('profile.delete-photo');
});

// ── Notification Routes ───────────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/notifications/{notification}/read', function (App\Models\Notification $notification) {
        $notification->update(['is_read' => true]);
        if ($notification->link) {
            return redirect($notification->link);
        }
        return redirect()->back();
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
    Route::put('/users/{user}',           [AdminController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{user}',        [AdminController::class, 'destroyUser'])->name('users.destroy');
    Route::get('/departments',            [AdminController::class, 'departments'])->name('departments');
    Route::post('/departments',           [AdminController::class, 'storeDepartment'])->name('departments.store');
    Route::get('/requisitions',           [AdminController::class, 'requisitions'])->name('requisitions');
    Route::post('/requisitions/{requisition}/approve', [RequisitionController::class, 'approve'])->name('requisitions.approve');
    Route::post('/requisitions/{requisition}/reject',  [RequisitionController::class, 'reject'])->name('requisitions.reject');
    Route::get('/requisitions/{requisition}',          [RequisitionController::class, 'show'])->name('requisitions.show');
    Route::post('/requisitions/{requisition}/comments', function(App\Models\StockRequisition $requisition, Illuminate\Http\Request $request) {
        $request->validate(['comment' => 'required|string|max:1000']);
        App\Models\RequisitionComment::create([
            'stock_requisition_id' => $requisition->id,
            'user_id'              => auth()->id(),
            'comment'              => $request->comment,
        ]);
        return redirect()->back()->with('success', 'Comment added!');
    })->name('requisitions.comment');
    Route::get('/reports',            [App\Http\Controllers\ReportController::class, 'index'])->name('reports');
    Route::get('/reports/export-pdf', [App\Http\Controllers\ReportController::class, 'exportPdf'])->name('reports.export-pdf');
    Route::get('/stock-items',        [App\Http\Controllers\StockItemController::class, 'index'])->name('stock-items');
    Route::post('/stock-items',       [App\Http\Controllers\StockItemController::class, 'store'])->name('stock-items.store');
    Route::put('/stock-items/{stockItem}',    [App\Http\Controllers\StockItemController::class, 'update'])->name('stock-items.update');
    Route::delete('/stock-items/{stockItem}', [App\Http\Controllers\StockItemController::class, 'destroy'])->name('stock-items.destroy');
    Route::get('/stock-report',   [App\Http\Controllers\StockReportController::class, 'index'])->name('stock-report');
    Route::get('/settings', function() {
    $settings = \Illuminate\Support\Facades\DB::table('system_settings')->pluck('value', 'key');
    return view('admin.settings', compact('settings'));
})->name('settings');
Route::post('/settings', function(Illuminate\Http\Request $request) {
    $data = $request->except('_token');
    foreach($data as $key => $value) {
        \Illuminate\Support\Facades\DB::table('system_settings')
            ->where('key', $key)
            ->update(['value' => $value]);
    }
    return redirect()->route('admin.settings')->with('success', 'Settings updated successfully!');
})->name('settings.update');
    Route::get('/activity-log', function() {
        $logs = \App\Models\ActivityLog::with('user')->latest()->paginate(20);
        return view('admin.activity-log', compact('logs'));
    })->name('activity-log');
    Route::get('/payments/{payment}/download', function(App\Models\Payment $payment) {
        $path = storage_path('app/public/' . $payment->receipt_path);
        return response()->download($path, $payment->receipt_original_name);
    })->name('receipt.download');
});

// ── Employee Routes ───────────────────────────────────────────────
Route::middleware(['auth', 'role:employee'])->prefix('employee')->name('employee.')->group(function () {
    Route::get('/dashboard',                            [EmployeeController::class, 'dashboard'])->name('dashboard');
    Route::get('/requisitions',                         [EmployeeController::class, 'index'])->name('requisitions');
    Route::get('/requisitions/create',                  [EmployeeController::class, 'create'])->name('requisitions.create');
    Route::post('/requisitions',                        [EmployeeController::class, 'store'])->name('requisitions.store');
    Route::get('/requisitions/{requisition}',           [EmployeeController::class, 'show'])->name('requisitions.show');
    Route::delete('/requisitions/{requisition}/cancel', [EmployeeController::class, 'cancel'])->name('requisitions.cancel');
    Route::get('/requisitions/{requisition}/print', function(App\Models\StockRequisition $requisition) {
        if ($requisition->user_id !== auth()->id()) abort(403);
        $requisition->load(['department', 'payment', 'user', 'approver']);
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('employee.requisition-pdf', compact('requisition'));
        return $pdf->download('requisition-'.$requisition->reference_number.'.pdf');
    })->name('requisitions.print');
    Route::post('/requisitions/{requisition}/comments', function(App\Models\StockRequisition $requisition, Illuminate\Http\Request $request) {
        $request->validate(['comment' => 'required|string|max:1000']);
        App\Models\RequisitionComment::create([
            'stock_requisition_id' => $requisition->id,
            'user_id'              => auth()->id(),
            'comment'              => $request->comment,
        ]);
        return redirect()->back()->with('success', 'Comment added!');
    })->name('requisitions.comment');
    Route::get('/stock-items', [App\Http\Controllers\StockItemController::class, 'available'])->name('stock-items');
});

// ── Accountant Routes ─────────────────────────────────────────────
Route::middleware(['auth', 'role:accountant'])->prefix('accountant')->name('accountant.')->group(function () {
    Route::get('/dashboard',                           [AccountantController::class, 'dashboard'])->name('dashboard');
    Route::get('/requisitions',                        [AccountantController::class, 'requisitions'])->name('requisitions');
    Route::post('/requisitions/{requisition}/status',  [AccountantController::class, 'updateStatus'])->name('requisitions.status');
    Route::get('/requisitions/{requisition}/payment',  [AccountantController::class, 'showPaymentForm'])->name('requisitions.payment');
    Route::post('/requisitions/{requisition}/payment', [AccountantController::class, 'uploadPayment'])->name('requisitions.payment.store');
    Route::get('/stock-report', [App\Http\Controllers\StockReportController::class, 'index'])->name('stock-report');
});