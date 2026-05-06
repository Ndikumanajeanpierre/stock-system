<?php

namespace App\Http\Controllers;

use App\Models\StockItem;
use Illuminate\Http\Request;

class StockItemController extends Controller
{
    public function index()
    {
        $stockItems = StockItem::latest()->get();
        return view('admin.stock-items', compact('stockItems'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'               => 'required|string|max:255',
            'category'           => 'nullable|string',
            'unit'               => 'required|string',
            'unit_price'         => 'required|numeric|min:0',
            'quantity_available' => 'required|integer|min:0',
            'description'        => 'nullable|string',
        ]);

        StockItem::create($request->all());

        return redirect()->route('admin.stock-items')->with('success', 'Stock item added successfully!');
    }

    public function update(Request $request, StockItem $stockItem)
    {
        $request->validate([
            'name'               => 'required|string|max:255',
            'category'           => 'nullable|string',
            'unit'               => 'required|string',
            'unit_price'         => 'required|numeric|min:0',
            'quantity_available' => 'required|integer|min:0',
            'description'        => 'nullable|string',
        ]);

        $stockItem->update($request->all());

        return redirect()->route('admin.stock-items')->with('success', 'Stock item updated successfully!');
    }

    public function destroy(StockItem $stockItem)
    {
        $stockItem->delete();
        return redirect()->route('admin.stock-items')->with('success', 'Stock item deleted!');
    }

    // For employee - view available stock
    public function available()
    {
        $stockItems = StockItem::available()->get();
        return view('employee.stock-items', compact('stockItems'));
    }
}