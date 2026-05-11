<?php

namespace App\Http\Controllers;

use App\Models\StockItem;
use App\Models\StockMovement;
use App\Models\StockRequisition;
use Illuminate\Http\Request;

class StockReportController extends Controller
{
    public function index()
    {
        // All stock items with their movements
        $stockItems = StockItem::with('movements')->get();

        // Summary stats
        $totalItems        = $stockItems->count();
        $totalAvailable    = $stockItems->sum('quantity_available');
        $totalValue        = $stockItems->sum(function($item) {
            return $item->quantity_available * $item->unit_price;
        });
        $totalOut          = StockMovement::where('type', 'out')->sum('quantity');
        $totalIn           = StockMovement::where('type', 'in')->sum('quantity');

        // Items with low stock (less than 5)
        $lowStockItems = StockItem::where('quantity_available', '<', 5)
                            ->where('quantity_available', '>', 0)
                            ->get();

        // Out of stock items
        $outOfStockItems = StockItem::where('quantity_available', 0)->get();

        // Recent movements
        $recentMovements = StockMovement::with(['stockItem', 'requisition', 'createdBy'])
                            ->latest()->take(10)->get();

        return view('stock.report', compact(
            'stockItems', 'totalItems', 'totalAvailable',
            'totalValue', 'totalOut', 'totalIn',
            'lowStockItems', 'outOfStockItems', 'recentMovements'
        ));
    }
    public function exportPdf()
{
    $stockItems      = \App\Models\StockItem::with('movements')->get();
    $totalItems      = $stockItems->count();
    $totalAvailable  = $stockItems->sum('quantity_available');
    $totalValue      = $stockItems->sum(function($item) {
        return $item->quantity_available * $item->unit_price;
    });
    $totalOut        = \App\Models\StockMovement::where('type', 'out')->sum('quantity');
    $totalIn         = \App\Models\StockMovement::where('type', 'in')->sum('quantity');
    $lowStockItems   = \App\Models\StockItem::where('quantity_available', '<', 5)->where('quantity_available', '>', 0)->get();
    $outOfStockItems = \App\Models\StockItem::where('quantity_available', 0)->get();

    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('stock.report-pdf', compact(
        'stockItems', 'totalItems', 'totalAvailable',
        'totalValue', 'totalOut', 'totalIn',
        'lowStockItems', 'outOfStockItems'
    ))->setPaper('a4', 'landscape');

    return $pdf->download('stock-report-' . now()->format('Y-m-d') . '.pdf');
}
}
