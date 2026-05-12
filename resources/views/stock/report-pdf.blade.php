<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Stock Report</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; color: #333; margin: 0; padding: 15px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 3px solid #11998e; padding-bottom: 10px; }
        .header h1 { color: #11998e; margin: 0; font-size: 22px; }
        .header p { margin: 5px 0 0; color: #666; font-size: 11px; }
        .summary { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .summary td { padding: 10px; text-align: center; color: white; border-radius: 6px; }
        .s1 { background: #667eea; }
        .s2 { background: #11998e; }
        .s3 { background: #f7971e; }
        .s4 { background: #4facfe; }
        .s5 { background: #f5576c; }
        .summary td strong { font-size: 20px; display: block; }
        table.main { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table.main th { background: #11998e; color: white; padding: 8px; text-align: left; font-size: 11px; }
        table.main td { padding: 6px 8px; border-bottom: 1px solid #eee; font-size: 11px; }
        table.main tr:nth-child(even) { background: #f8f9fa; }
        .badge { padding: 2px 8px; border-radius: 4px; font-size: 10px; color: white; }
        .in-stock { background: #11998e; }
        .low-stock { background: #f7971e; }
        .out-stock { background: #e74c3c; }
        .alert-box { background: #fff3cd; border: 1px solid #f7971e; border-radius: 6px; padding: 10px; margin-bottom: 15px; }
        .alert-box h4 { margin: 0 0 8px; color: #856404; font-size: 13px; }
        .footer { margin-top: 20px; text-align: center; color: #999; font-size: 10px; border-top: 1px solid #eee; padding-top: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>📦 StockSys — Stock Inventory Report</h1>
        <p>Generated on {{ now()->format('d M Y H:i') }}</p>
    </div>

    <!-- Summary -->
    <table class="summary">
        <tr>
            <td class="s1" style="width:20%"><strong>{{ $totalItems }}</strong>Total Items</td>
            <td width="2%"></td>
            <td class="s2" style="width:20%"><strong>{{ $totalAvailable }}</strong>Total Available</td>
            <td width="2%"></td>
            <td class="s3" style="width:20%"><strong>RWF {{ number_format($totalValue, 0) }}</strong>Stock Value</td>
            <td width="2%"></td>
            <td class="s4" style="width:20%"><strong>{{ $totalIn }}</strong>Total In</td>
            <td width="2%"></td>
            <td class="s5" style="width:20%"><strong>{{ $totalOut }}</strong>Total Out</td>
        </tr>
    </table>

    <!-- Low Stock Alert -->
    @if($lowStockItems->count() > 0)
    <div class="alert-box">
        <h4>⚠️ Low Stock Alert ({{ $lowStockItems->count() }} items)</h4>
        @foreach($lowStockItems as $item)
            <span style="margin-right:10px;">{{ $item->name }} ({{ $item->quantity_available }} left)</span>
        @endforeach
    </div>
    @endif

    <!-- Stock Items Table -->
    <table class="main">
        <thead>
            <tr>
                <th>#</th>
                <th>Item Name</th>
                <th>Category</th>
                <th>Unit</th>
                <th>Unit Price</th>
                <th>Total In</th>
                <th>Total Out</th>
                <th>Available</th>
                <th>Stock Value</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($stockItems as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td><strong>{{ $item->name }}</strong></td>
                <td>{{ $item->category ?? 'N/A' }}</td>
                <td>{{ $item->unit }}</td>
                <td>RWF {{ number_format($item->unit_price, 2) }}</td>
                <td>{{ $item->totalIn() }}</td>
                <td>{{ $item->totalOut() }}</td>
                <td><strong>{{ $item->quantity_available }}</strong></td>
                <td><strong>RWF {{ number_format($item->quantity_available * $item->unit_price, 2) }}</strong></td>
                <td>
                    @if($item->quantity_available == 0)
                        <span class="badge out-stock">Out of Stock</span>
                    @elseif($item->quantity_available < 5)
                        <span class="badge low-stock">Low Stock</span>
                    @else
                        <span class="badge in-stock">In Stock</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr><td colspan="10" style="text-align:center;">No stock items found.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>StockSys - Internal Stock Requisition Management System &copy; {{ date('Y') }}</p>
    </div>
</body>
</html>