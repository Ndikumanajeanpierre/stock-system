<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ $requisition->reference_number }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 13px; color: #333; margin: 0; padding: 20px; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 3px solid #667eea; padding-bottom: 15px; }
        .header h1 { color: #667eea; margin: 0 0 5px; font-size: 24px; }
        .header p { margin: 0; color: #666; font-size: 12px; }
        .ref-badge { display: inline-block; background: #667eea; color: white; padding: 5px 15px; border-radius: 20px; font-size: 14px; font-weight: bold; margin: 10px 0; }
        .section { margin-bottom: 25px; }
        .section-title { font-size: 14px; font-weight: bold; color: #667eea; border-bottom: 1px solid #eee; padding-bottom: 5px; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; }
        .info-table td { padding: 8px 10px; border-bottom: 1px solid #f0f0f0; }
        .info-table td:first-child { font-weight: bold; color: #555; width: 35%; background: #f8f9fa; }
        .badge { display: inline-block; padding: 3px 10px; border-radius: 4px; font-size: 11px; color: white; font-weight: bold; }
        .badge-warning  { background: #f7971e; }
        .badge-info     { background: #4facfe; }
        .badge-success  { background: #11998e; }
        .badge-danger   { background: #e74c3c; }
        .badge-primary  { background: #667eea; }
        .badge-dark     { background: #343a40; }
        .badge-secondary{ background: #6c757d; }
        .payment-box { background: #f0fff8; border: 1px solid #11998e; border-radius: 8px; padding: 15px; margin-top: 15px; }
        .footer { margin-top: 40px; text-align: center; color: #999; font-size: 11px; border-top: 1px solid #eee; padding-top: 15px; }
        .status-bar { text-align: center; margin: 20px 0; }
        .watermark { position: fixed; top: 40%; left: 20%; opacity: 0.05; font-size: 80px; font-weight: bold; color: #667eea; transform: rotate(-30deg); }
    </style>
</head>
<body>
    <div class="watermark">StockSys</div>

    <div class="header">
        <h1>📦 StockSys</h1>
        <p>Internal Stock Requisition Management System</p>
        <div class="ref-badge">{{ $requisition->reference_number }}</div>
        <p>Generated on {{ now()->format('d M Y H:i') }}</p>
    </div>

    <!-- Status -->
    <div class="status-bar">
        <span class="badge badge-{{ $requisition->getStatusBadgeClass() }}" style="font-size:14px; padding:8px 20px;">
            {{ strtoupper($requisition->status) }}
        </span>
    </div>

    <!-- Requisition Info -->
    <div class="section">
        <div class="section-title">📋 Requisition Information</div>
        <table class="info-table">
            <tr><td>Reference Number</td><td>{{ $requisition->reference_number }}</td></tr>
            <tr><td>Employee Name</td><td>{{ $requisition->user->name }}</td></tr>
            <tr><td>Department</td><td>{{ $requisition->department->name }}</td></tr>
            <tr><td>Item Name</td><td>{{ $requisition->item_name }}</td></tr>
            <tr><td>Quantity</td><td>{{ $requisition->quantity }} {{ $requisition->unit }}</td></tr>
            <tr><td>Priority</td><td>
                <span class="badge badge-{{ $requisition->getPriorityBadgeClass() }}">
                    {{ strtoupper($requisition->priority) }}
                </span>
            </td></tr>
           <tr><td>Estimated Cost</td><td>{{ $requisition->estimated_cost ? 'RWF '.number_format($requisition->estimated_cost, 2) : 'N/A' }}</td></tr>
            <tr><td>Description</td><td>{{ $requisition->description ?? 'N/A' }}</td></tr>
            <tr><td>Submitted On</td><td>{{ $requisition->created_at->format('d M Y H:i') }}</td></tr>
        </table>
    </div>

    <!-- Approval Info -->
    @if($requisition->approved_at)
    <div class="section">
        <div class="section-title">✅ Approval Information</div>
        <table class="info-table">
            <tr><td>Approved By</td><td>{{ $requisition->approver->name ?? 'N/A' }}</td></tr>
            <tr><td>Approved At</td><td>{{ $requisition->approved_at->format('d M Y H:i') }}</td></tr>
        </table>
    </div>
    @endif

    <!-- Rejection Info -->
    @if($requisition->status === 'rejected')
    <div class="section">
        <div class="section-title">❌ Rejection Information</div>
        <table class="info-table">
            <tr><td>Rejection Reason</td><td>{{ $requisition->rejection_reason }}</td></tr>
        </table>
    </div>
    @endif

    <!-- Payment Info -->
    @if($requisition->payment)
    <div class="section">
        <div class="section-title">💳 Payment Information</div>
        <div class="payment-box">
            <table class="info-table">
                <tr><td>Amount Paid</td><td><strong>${{ number_format($requisition->payment->amount, 2) }}</strong></td></tr>
                <tr><td>Payment Method</td><td>{{ ucfirst(str_replace('_', ' ', $requisition->payment->payment_method)) }}</td></tr>
                <tr><td>Payment Date</td><td>{{ $requisition->payment->payment_date->format('d M Y') }}</td></tr>
                <tr><td>Transaction Ref</td><td>{{ $requisition->payment->transaction_reference ?? 'N/A' }}</td></tr>
            </table>
        </div>
    </div>
    @endif

    <div class="footer">
        <p>This document was generated automatically by StockSys</p>
        <p>{{ $requisition->user->name }} &bull; {{ $requisition->user->email }} &bull; {{ now()->format('d M Y') }}</p>
        <p>&copy; {{ date('Y') }} StockSys - Internal Stock Requisition Management System</p>
    </div>
</body>
</html>