<?php

namespace App\Http\Controllers;

use App\Models\StockRequisition;
use App\Models\Payment;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function index(Request $request)
    {
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

        $requisitions      = $query->latest()->get();
        $totalRequisitions = $requisitions->count();
        $totalPaid         = $requisitions->whereIn('status', ['paid', 'completed'])->count();
        $totalPending      = $requisitions->where('status', 'pending')->count();
        $totalAmount       = Payment::whereIn('stock_requisition_id', $requisitions->pluck('id'))->sum('amount');
        $departmentStats   = StockRequisition::selectRaw('department_id, count(*) as total')
                                ->groupBy('department_id')->with('department')->get();
        $departments       = Department::all();

        return view('admin.reports', compact(
            'requisitions', 'totalRequisitions', 'totalPaid',
            'totalPending', 'totalAmount', 'departmentStats', 'departments'
        ));
    }

    public function exportPdf(Request $request)
    {
        $query = StockRequisition::with(['user', 'department', 'payment']);

        if ($request->status) {
            $query->where('status', $request->status);
        }
        if ($request->department_id) {
            $query->where('department_id', $request->department_id);
        }
        if ($request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $requisitions      = $query->latest()->get();
        $totalRequisitions = $requisitions->count();
        $totalPaid         = $requisitions->whereIn('status', ['paid', 'completed'])->count();
        $totalPending      = $requisitions->where('status', 'pending')->count();
        $totalAmount       = Payment::whereIn('stock_requisition_id', $requisitions->pluck('id'))->sum('amount');

        $pdf = Pdf::loadView('admin.reports-pdf', compact(
            'requisitions', 'totalRequisitions', 'totalPaid',
            'totalPending', 'totalAmount'
        ))->setPaper('a4', 'landscape');

        return $pdf->download('stock-report-' . now()->format('Y-m-d') . '.pdf');
    }
}