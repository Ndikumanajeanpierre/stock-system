<?php

namespace App\Http\Controllers;

use App\Models\StockRequisition;
use App\Models\Department;
use App\Models\Notification;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function dashboard()
    {
        $user                = auth()->user();
        $totalRequests       = StockRequisition::where('user_id', $user->id)->count();
        $pendingRequests     = StockRequisition::where('user_id', $user->id)->where('status', 'pending')->count();
        $approvedRequests    = StockRequisition::where('user_id', $user->id)->where('status', 'approved')->count();
        $completedRequests   = StockRequisition::where('user_id', $user->id)->where('status', 'completed')->count();
        $recentRequests      = StockRequisition::where('user_id', $user->id)->with('department')->latest()->take(5)->get();

        return view('employee.dashboard', compact(
            'totalRequests', 'pendingRequests', 'approvedRequests',
            'completedRequests', 'recentRequests'
        ));
    }

    public function index()
    {
        $requisitions = StockRequisition::where('user_id', auth()->id())
                        ->with('department')
                        ->latest()
                        ->get();
        return view('employee.requisitions', compact('requisitions'));
    }

    public function create()
    {
        $departments = Department::active()->get();
        return view('employee.create-requisition', compact('departments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_name'      => 'required|string|max:255',
            'quantity'       => 'required|integer|min:1',
            'unit'           => 'required|string',
            'department_id'  => 'required|exists:departments,id',
            'priority'       => 'required|in:low,medium,high,urgent',
            'description'    => 'nullable|string',
            'estimated_cost' => 'nullable|numeric|min:0',
        ]);

        $requisition = StockRequisition::create([
            'user_id'        => auth()->id(),
            'department_id'  => $request->department_id,
            'item_name'      => $request->item_name,
            'quantity'       => $request->quantity,
            'unit'           => $request->unit,
            'priority'       => $request->priority,
            'description'    => $request->description,
            'estimated_cost' => $request->estimated_cost,
            'status'         => 'pending',
        ]);

        // Log activity
        ActivityLog::record('submitted_request', auth()->user()->name . ' submitted a new request for ' . $request->item_name, 'StockRequisition', $requisition->id);

        // Notify all admins
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            Notification::send(
                $admin->id,
                'New Requisition Submitted',
                auth()->user()->name . ' submitted a new request for ' . $request->item_name,
                'info',
                route('admin.requisitions')
            );
        }

        return redirect()->route('employee.requisitions')->with('success', 'Requisition submitted successfully!');
    }

    public function show(StockRequisition $requisition)
    {
        if ($requisition->user_id !== auth()->id()) {
            abort(403);
        }
        $requisition->load(['department', 'payment']);
        return view('employee.show-requisition', compact('requisition'));
    }

    public function cancel(StockRequisition $requisition)
    {
        if ($requisition->user_id !== auth()->id()) {
            abort(403);
        }

        if ($requisition->status !== 'pending') {
            return redirect()->route('employee.requisitions')
                ->with('error', 'Only pending requests can be cancelled!');
        }

        // Log activity
        ActivityLog::record('cancelled_request', auth()->user()->name . ' cancelled request ' . $requisition->reference_number, 'StockRequisition', $requisition->id);

        $requisition->delete();

        return redirect()->route('employee.requisitions')
            ->with('success', 'Requisition cancelled successfully!');
    }
}