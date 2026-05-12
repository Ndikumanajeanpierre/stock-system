@extends('layouts.app')

@section('title', 'Accountant Dashboard')

@section('content')

<style>
/* ── Stat Cards ── */
.acc-cards {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
    margin-bottom: 24px;
}
.acc-card {
    background: #fff;
    border-radius: 14px;
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 16px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    border: 1px solid #f0f0f0;
    transition: transform 0.15s, box-shadow 0.15s;
    position: relative;
    overflow: hidden;
}
.acc-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 24px rgba(0,0,0,0.10);
}
.acc-card .ac-icon {
    width: 52px; height: 52px;
    border-radius: 13px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    color: #fff;
    flex-shrink: 0;
}
.ac-icon.ic-amber  { background: #f59e0b; }
.ac-icon.ic-sky    { background: #0ea5e9; }
.ac-icon.ic-green  { background: #10b981; }
.ac-icon.ic-violet { background: #8b5cf6; }

.acc-card .ac-body { flex: 1; min-width: 0; }
.acc-card .ac-value {
    font-size: 1.75rem;
    font-weight: 800;
    color: #111827;
    line-height: 1.1;
    margin-bottom: 4px;
}
.acc-card .ac-value.small-val { font-size: 1.05rem; }
.acc-card .ac-label {
    font-size: 0.75rem;
    color: #6b7280;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.4px;
}
.acc-card::after {
    content: '';
    position: absolute;
    right: -10px; top: -10px;
    width: 70px; height: 70px;
    border-radius: 50%;
    opacity: 0.06;
}
.acc-card.c-amber::after  { background: #f59e0b; }
.acc-card.c-sky::after    { background: #0ea5e9; }
.acc-card.c-green::after  { background: #10b981; }
.acc-card.c-violet::after { background: #8b5cf6; }

/* ── Quick Action Cards ── */
.action-card {
    background: #fff;
    border-radius: 14px;
    border: 1px solid #f0f0f0;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    padding: 28px 20px;
    text-align: center;
    cursor: pointer;
    transition: transform 0.15s, box-shadow 0.15s;
    text-decoration: none;
    display: block;
    height: 100%;
}
.action-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 24px rgba(0,0,0,0.10);
    text-decoration: none;
}
.action-card .ac-btn-icon {
    width: 60px; height: 60px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 14px;
    font-size: 1.4rem;
    color: #fff;
}
.action-card h6 {
    font-weight: 700;
    color: #111827;
    margin-bottom: 4px;
    font-size: 0.95rem;
}
.action-card small { color: #6b7280; font-size: 0.8rem; }

/* ── Table card ── */
.tbl-card {
    background: #fff;
    border-radius: 14px;
    border: 1px solid #f0f0f0;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    overflow: hidden;
}
.tbl-card .tbl-header {
    padding: 16px 20px;
    border-bottom: 1px solid #f3f4f6;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.tbl-card .tbl-header h6 {
    margin: 0;
    font-weight: 700;
    color: #111827;
    font-size: 0.9rem;
}
.tbl-card table thead tr th {
    font-size: 0.72rem;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: 0.4px;
    background: #f9fafb;
    padding: 10px 16px;
    border-bottom: 1px solid #f0f0f0;
}
.tbl-card table tbody tr td {
    padding: 11px 16px;
    font-size: 0.855rem;
    color: #374151;
    border-bottom: 1px solid #f9fafb;
    vertical-align: middle;
}

@media (max-width: 992px) {
    .acc-cards { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 576px) {
    .acc-cards { grid-template-columns: 1fr; }
}
</style>

<!-- Stat Cards -->
<div class="acc-cards">
    <div class="acc-card c-amber">
        <div class="ac-icon ic-amber"><i class="fas fa-check"></i></div>
        <div class="ac-body">
            <div class="ac-value">{{ $approvedRequests }}</div>
            <div class="ac-label">Approved Requests</div>
        </div>
    </div>
    <div class="acc-card c-sky">
        <div class="ac-icon ic-sky"><i class="fas fa-shopping-cart"></i></div>
        <div class="ac-body">
            <div class="ac-value">{{ $purchasedRequests }}</div>
            <div class="ac-label">Purchased</div>
        </div>
    </div>
    <div class="acc-card c-green">
        <div class="ac-icon ic-green"><i class="fas fa-money-bill"></i></div>
        <div class="ac-body">
            <div class="ac-value">{{ $paidRequests }}</div>
            <div class="ac-label">Paid</div>
        </div>
    </div>
    <div class="acc-card c-violet">
        <div class="ac-icon ic-violet"><i class="fas fa-money-bill-wave"></i></div>
        <div class="ac-body">
            <div class="ac-value small-val">RWF {{ number_format($totalPayments, 0) }}</div>
            <div class="ac-label">Total Payments</div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row g-4 mb-4">
    <div class="col-md-6">
        <a href="{{ route('accountant.requisitions') }}?status=approved" class="action-card">
            <div class="ac-btn-icon" style="background:#f59e0b;">
                <i class="fas fa-shopping-cart"></i>
            </div>
            <h6>Mark as Purchased</h6>
            <small>Process approved requisitions</small>
        </a>
    </div>
    <div class="col-md-6">
        <a href="{{ route('accountant.requisitions') }}?status=purchased" class="action-card">
            <div class="ac-btn-icon" style="background:#10b981;">
                <i class="fas fa-upload"></i>
            </div>
            <h6>Upload Payments</h6>
            <small>Upload receipts for purchased items</small>
        </a>
    </div>
</div>

<!-- Recent Payments -->
<div class="tbl-card">
    <div class="tbl-header">
        <div>
            <h6>Recent Payments</h6>
            <small class="text-muted" style="font-size:0.78rem;">Latest uploaded payments</small>
        </div>
        <a href="{{ route('accountant.requisitions') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-eye me-1"></i> View All
        </a>
    </div>
    <table class="table mb-0">
        <thead>
            <tr>
                <th>Reference</th>
                <th>Item</th>
                <th>Amount</th>
                <th>Method</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse($recentPayments as $payment)
            <tr>
                <td><span class="fw-bold text-primary">{{ $payment->requisition->reference_number }}</span></td>
                <td>{{ $payment->requisition->item_name }}</td>
                <td><span class="fw-bold text-success">RWF {{ number_format($payment->amount, 2) }}</span></td>
                <td>{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</td>
                <td style="color:#6b7280;">{{ $payment->payment_date->format('d M Y') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center py-5 text-muted">
                    <i class="fas fa-receipt fa-2x mb-2 d-block"></i>
                    No payments yet.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection