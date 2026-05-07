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

    $item = StockItem::create($request->all());

    // Record stock IN movement
    \App\Models\StockMovement::create([
        'stock_item_id' => $item->id,
        'type'          => 'in',
        'quantity'      => $request->quantity_available,
        'note'          => 'Initial stock added',
        'created_by'    => auth()->id(),
    ]);

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

    $oldQuantity = $stockItem->quantity_available;
    $newQuantity = $request->quantity_available;

    $stockItem->update($request->all());

    // Record movement if quantity changed
    if ($newQuantity > $oldQuantity) {
        \App\Models\StockMovement::create([
            'stock_item_id' => $stockItem->id,
            'type'          => 'in',
            'quantity'      => $newQuantity - $oldQuantity,
            'note'          => 'Stock updated - quantity increased',
            'created_by'    => auth()->id(),
        ]);
    } elseif ($newQuantity < $oldQuantity) {
        \App\Models\StockMovement::create([
            'stock_item_id' => $stockItem->id,
            'type'          => 'out',
            'quantity'      => $oldQuantity - $newQuantity,
            'note'          => 'Stock updated - quantity decreased',
            'created_by'    => auth()->id(),
        ]);
    }

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