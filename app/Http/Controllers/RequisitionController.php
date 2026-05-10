<?php

namespace App\Http\Controllers;

use App\Models\StockRequisition;
use App\Models\Notification;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;

class RequisitionController extends Controller
{
    public function approve(StockRequisition $requisition)
    {
        $requisition->update([
            'status'      => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        // Log activity
        ActivityLog::record('approved_request', auth()->user()->name . ' approved request ' . $requisition->reference_number, 'StockRequisition', $requisition->id);

        // Notify employee
        Notification::send(
            $requisition->user_id,
            'Requisition Approved',
            'Your request ' . $requisition->reference_number . ' has been approved!',
            'success',
            route('employee.requisitions.show', $requisition)
        );

        // Notify accountants
        $accountants = User::where('role', 'accountant')->get();
        foreach ($accountants as $accountant) {
            Notification::send(
                $accountant->id,
                'New Approved Requisition',
                'Requisition ' . $requisition->reference_number . ' is ready for purchasing.',
                'info',
                route('accountant.requisitions')
            );
        }

        return redirect()->back()->with('success', 'Requisition approved successfully!');
    }

    public function reject(Request $request, StockRequisition $requisition)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $requisition->update([
            'status'           => 'rejected',
            'rejection_reason' => $request->rejection_reason,
        ]);

        // Log activity
        ActivityLog::record('rejected_request', auth()->user()->name . ' rejected request ' . $requisition->reference_number, 'StockRequisition', $requisition->id);

        // Notify employee
        Notification::send(
            $requisition->user_id,
            'Requisition Rejected',
            'Your request ' . $requisition->reference_number . ' has been rejected. Reason: ' . $request->rejection_reason,
            'danger',
            route('employee.requisitions.show', $requisition)
        );

        return redirect()->back()->with('success', 'Requisition rejected.');
    }

    public function show(StockRequisition $requisition)
    {
        $requisition->load(['user', 'department', 'approver', 'payment']);
        return view('admin.show-requisition', compact('requisition'));
    }
}