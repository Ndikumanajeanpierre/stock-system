<?php

namespace App\Http\Controllers;

use App\Models\StockRequisition;
use App\Models\Payment;
use App\Models\Notification;
use Illuminate\Http\Request;

class AccountantController extends Controller
{
    public function dashboard()
    {
        $approvedRequests   = StockRequisition::where('status', 'approved')->count();
        $purchasedRequests  = StockRequisition::where('status', 'purchased')->count();
        $paidRequests       = StockRequisition::where('status', 'paid')->count();
        $totalPayments      = Payment::sum('amount');
        $recentPayments     = Payment::with(['requisition', 'accountant'])->latest()->take(5)->get();

        return view('accountant.dashboard', compact(
            'approvedRequests', 'purchasedRequests',
            'paidRequests', 'totalPayments', 'recentPayments'
        ));
    }

    public function requisitions(Request $request)
    {
        $query = StockRequisition::with(['user', 'department'])
                    ->whereIn('status', ['approved', 'purchased', 'paid', 'completed']);

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $requisitions = $query->latest()->get();
        return view('accountant.requisitions', compact('requisitions'));
    }

   public function updateStatus(Request $request, StockRequisition $requisition)
{
    $request->validate([
        'status' => 'required|in:purchased,completed',
    ]);

    $requisition->update(['status' => $request->status]);

    // Record stock OUT when completed
    if ($request->status === 'completed') {
        $stockItem = \App\Models\StockItem::where('name', $requisition->item_name)->first();
        if ($stockItem) {
            // Subtract quantity
            $stockItem->decrement('quantity_available', $requisition->quantity);

            // Record movement
            \App\Models\StockMovement::create([
                'stock_item_id'        => $stockItem->id,
                'stock_requisition_id' => $requisition->id,
                'type'                 => 'out',
                'quantity'             => $requisition->quantity,
                'note'                 => 'Item issued for requisition ' . $requisition->reference_number,
                'created_by'           => auth()->id(),
            ]);
        }
    }

    // Notify employee
    \App\Models\Notification::send(
        $requisition->user_id,
        'Requisition Status Updated',
        'Your request ' . $requisition->reference_number . ' is now ' . $request->status,
        'info',
        route('employee.requisitions.show', $requisition)
    );

    return redirect()->route('accountant.requisitions')->with('success', 'Status updated successfully!');
}

    public function uploadPayment(Request $request, StockRequisition $requisition)
    {
        $request->validate([
            'amount'                => 'required|numeric|min:0',
            'payment_method'        => 'required|string',
            'payment_date'          => 'required|date',
            'receipt'               => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'transaction_reference' => 'nullable|string',
            'notes'                 => 'nullable|string',
        ]);

        // Store receipt file
        $file         = $request->file('receipt');
        $originalName = $file->getClientOriginalName();
        $path         = $file->store('receipts', 'public');

        // Create payment
        Payment::create([
            'stock_requisition_id'  => $requisition->id,
            'uploaded_by'           => auth()->id(),
            'amount'                => $request->amount,
            'payment_method'        => $request->payment_method,
            'payment_date'          => $request->payment_date,
            'receipt_path'          => $path,
            'receipt_original_name' => $originalName,
            'transaction_reference' => $request->transaction_reference,
            'notes'                 => $request->notes,
        ]);

        // Update requisition status to paid
       // Update requisition status to paid
$requisition->update(['status' => 'paid']);

// Record stock OUT when paid
$stockItem = \App\Models\StockItem::where('name', $requisition->item_name)->first();
if ($stockItem) {
    $stockItem->decrement('quantity_available', $requisition->quantity);
    \App\Models\StockMovement::create([
        'stock_item_id'        => $stockItem->id,
        'stock_requisition_id' => $requisition->id,
        'type'                 => 'out',
        'quantity'             => $requisition->quantity,
        'note'                 => 'Item issued for ' . $requisition->reference_number,
        'created_by'           => auth()->id(),
    ]);
}

        // Notify employee with receipt link
$receiptUrl = asset('storage/' . $path);
Notification::send(
    $requisition->user_id,
    'Payment Receipt Uploaded',
    'Payment receipt for ' . $requisition->reference_number . ' has been uploaded. Amount: $' . $request->amount . '. You can view and download your receipt here: ' . $receiptUrl,
    'success'
);
        return redirect()->route('accountant.requisitions')->with('success', 'Payment uploaded successfully!');
    }

    public function showPaymentForm(StockRequisition $requisition)
    {
        return view('accountant.upload-payment', compact('requisition'));
    }
}