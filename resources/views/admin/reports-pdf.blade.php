<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Stock Report</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #667eea; padding-bottom: 10px; }
        .header h1 { color: #667eea; margin: 0; font-size: 22px; }
        .header p { margin: 5px 0; color: #666; }
        .summary { display: flex; gap: 10px; margin-bottom: 20px; }
        .summary-card { flex: 1; padding: 10px; border-radius: 8px; text-align: center; color: white; }
        .card-blue { background: #667eea; }
        .card-orange { background: #f7971e; }
        .card-green { background: #11998e; }
        .card-purple { background: #764ba2; }
        .summary-card h3 { margin: 0; font-size: 20px; }
        .summary-card p { margin: 0; font-size: 11px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background: #667eea; color: white; padding: 8px; text-align: left; font-size: 11px; }
        td { padding: 7px 8px; border-bottom: 1px solid #eee; font-size: 11px; }
        tr:nth-child(even) { background: #f8f9fa; }
        .badge { padding: 2px 8px; border-radius: 4px; font-size: 10px; color: white; }
        .badge-success { background: #11998e; }
        .badge-warning { background: #f7971e; }
        .badge-danger  { background: #e74c3c; }
        .badge-info    { background: #4facfe; }
        .badge-primary { background: #667eea; }
        .badge-dark    { background: #343a40; }
        .footer { margin-top: 20px; text-align: center; color: #999; font-size: 10px; border-top: 1px solid #eee; padding-top: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>📦 StockSys — Requisition Report</h1>
        <p>Generated on {{ now()->format('d M Y H:i') }}</p>
    </div>

    <!-- Summary -->
    <table>
        <tr>
            <td style="background:#667eea;color:white;padding:10px;border-radius:8px;text-align:center;">
                <strong style="font-size:18px;">{{ $totalRequisitions }}</strong><br>Total Requisitions
            </td>
            <td style="background:#f7971e;color:white;padding:10px;border-radius:8px;text-align:center;">
                <strong style="font-size:18px;">{{ $totalPending }}</strong><br>Pending
            </td>
            <td style="background:#11998e;color:white;padding:10px;border-radius:8px;text-align:center;">
                <strong style="font-size:18px;">{{ $totalPaid }}</strong><br>Paid/Completed
            </td>
            <td style="background:#764ba2;color:white;padding:10px;border-radius:8px;text-align:center;">
                <strong style="font-size:18px;">${{ number_format($totalAmount, 2) }}</strong><br>Total Payments
            </td>
        </tr>
    </table>

    <br>

    <!-- Requisitions Table -->
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Reference</th>
                <th>Employee</th>
                <th>Item</th>
                <th>Department</th>
                <th>Priority</th>
                <th>Status</th>
                <th>Amount</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse($requisitions as $req)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td><strong>{{ $req->reference_number }}</strong></td>
                <td>{{ $req->user->name }}</td>
                <td>{{ $req->item_name }}</td>
                <td>{{ $req->department->name }}</td>
                <td>{{ ucfirst($req->priority) }}</td>
                <td>
                    <span class="badge badge-{{ $req->getStatusBadgeClass() }}">
                        {{ ucfirst($req->status) }}
                    </span>
                </td>
                <td>{{ $req->payment ? '$'.number_format($req->payment->amount, 2) : 'N/A' }}</td>
                <td>{{ $req->created_at->format('d M Y') }}</td>
            </tr>
            @empty
            <tr><td colspan="9" style="text-align:center;">No records found.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>StockSys - Internal Stock Requisition Management System &copy; {{ date('Y') }}</p>
    </div>
</body>
</html>