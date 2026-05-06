<?php

namespace App\Http\Controllers;

use App\Models\StockRequisition;
use App\Models\Payment;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // Filters
        $status       = $request->status;
        $departmentId = $request->department_id;
        $dateFrom     = $request->date_from;
        $dateTo       = $request->date_to;

        $query = StockRequisition::with(['user', 'department', 'payment']);

        if ($status) {
            $query->where('status', $status);
        }
        if ($departmentId) {
            $query->where('department_id', $departmentId);
        }
        if ($dateFrom) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }
        if ($dateTo) {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        $requisitions = $query->latest()->get();

        // Summary stats
        $totalRequisitions  = $requisitions->count();
        $totalPaid          = $requisitions->whereIn('status', ['paid', 'completed'])->count();
        $totalPending       = $requisitions->where('status', 'pending')->count();
        $totalAmount        = Payment::whereIn('stock_requisition_id', $requisitions->pluck('id'))->sum('amount');

        // Department breakdown
        $departmentStats = StockRequisition::selectRaw('department_id, count(*) as total')
            ->groupBy('department_id')
            ->with('department')
            ->get();

        $departments = Department::all();

        return view('admin.reports', compact(
            'requisitions', 'totalRequisitions', 'totalPaid',
            'totalPending', 'totalAmount', 'departmentStats', 'departments'
        ));
    }
}