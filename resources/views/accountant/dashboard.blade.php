@extends('layouts.app')

@section('title', 'Accountant Dashboard')

@section('content')
<div class="row">
    <div class="col-md-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #f39c12, #e67e22);">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-1">Approved</h6>
                    <h2 class="mb-0">{{ $approvedRequests }}</h2>
                </div>
                <i class="fas fa-check fa-2x opacity-75"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #3498db, #2980b9);">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-1">Purchased</h6>
                    <h2 class="mb-0">{{ $purchasedRequests }}</h2>
                </div>
                <i class="fas fa-shopping-cart fa-2x opacity-75"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #27ae60, #2ecc71);">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-1">Paid</h6>
                    <h2 class="mb-0">{{ $paidRequests }}</h2>
                </div>
                <i class="fas fa-money-bill fa-2x opacity-75"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #8e44ad, #9b59b6);">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-1">Total Payments</h6>
                    <h2 class="mb-0">${{ number_format($totalPayments, 2) }}</h2>
                </div>
                <i class="fas fa-dollar-sign fa-2x opacity-75"></i>
            </div>
        </div>
    </div>
</div>

<!-- Recent Payments -->
<div class="card mt-3">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Recent Payments</h5>
        <a href="{{ route('accountant.requisitions') }}" class="btn btn-sm btn-primary">View All</a>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
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
                    <td>{{ $payment->requisition->reference_number }}</td>
                    <td>{{ $payment->requisition->item_name }}</td>
                    <td>${{ number_format($payment->amount, 2) }}</td>
                    <td>{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</td>
                    <td>{{ $payment->payment_date->format('d M Y') }}</td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center py-3">No payments yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection