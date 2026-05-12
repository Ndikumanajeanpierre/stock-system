@extends('layouts.app')

@section('title', 'Accountant Dashboard')

@section('content')
<div class="row g-4">
    <div class="col-md-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #f7971e, #ffd200);">
            <div class="stat-icon"><i class="fas fa-check"></i></div>
            <div class="stat-number">{{ $approvedRequests }}</div>
            <div class="stat-label">Approved Requests</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #4facfe, #00f2fe);">
            <div class="stat-icon"><i class="fas fa-shopping-cart"></i></div>
            <div class="stat-number">{{ $purchasedRequests }}</div>
            <div class="stat-label">Purchased</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #11998e, #38ef7d);">
            <div class="stat-icon"><i class="fas fa-money-bill"></i></div>
            <div class="stat-number">{{ $paidRequests }}</div>
            <div class="stat-label">Paid</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #667eea, #764ba2);">
            <div class="stat-icon"><i class="fas fa-money-bill-wave"></i></div>
            <div class="stat-number">RWF {{ number_format($totalPayments, 0) }}</div>
            <div class="stat-label">Total Payments</div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row g-4 mb-2">
    <div class="col-md-6">
        <a href="{{ route('accountant.requisitions') }}?status=approved" style="text-decoration:none;">
            <div class="card h-100 text-center p-4" style="border: 2px dashed #f7971e; background: #fffbf0; cursor:pointer;">
                <div style="width:60px;height:60px;background:linear-gradient(135deg,#f7971e,#ffd200);border-radius:16px;display:flex;align-items:center;justify-content:center;margin:0 auto 15px;color:white;font-size:1.5rem;">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <h6 class="fw-bold text-dark">Mark as Purchased</h6>
                <small class="text-muted">Process approved requisitions</small>
            </div>
        </a>
    </div>
    <div class="col-md-6">
        <a href="{{ route('accountant.requisitions') }}?status=purchased" style="text-decoration:none;">
            <div class="card h-100 text-center p-4" style="border: 2px dashed #11998e; background: #f0fff8; cursor:pointer;">
                <div style="width:60px;height:60px;background:linear-gradient(135deg,#11998e,#38ef7d);border-radius:16px;display:flex;align-items:center;justify-content:center;margin:0 auto 15px;color:white;font-size:1.5rem;">
                    <i class="fas fa-upload"></i>
                </div>
                <h6 class="fw-bold text-dark">Upload Payments</h6>
                <small class="text-muted">Upload receipts for purchased items</small>
            </div>
        </a>
    </div>
</div>

<!-- Recent Payments -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            <h6 class="mb-0 fw-bold">Recent Payments</h6>
            <small class="text-muted">Latest uploaded payments</small>
        </div>
        <a href="{{ route('accountant.requisitions') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-eye me-1"></i> View All
        </a>
    </div>
    <div class="card-body p-0">
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
                    <td>{{ $payment->payment_date->format('d M Y') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-4 text-muted">
                        <i class="fas fa-receipt fa-2x mb-2 d-block"></i>
                        No payments yet.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection