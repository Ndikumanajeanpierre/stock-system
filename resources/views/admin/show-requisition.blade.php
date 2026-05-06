@extends('layouts.app')

@section('title', 'Requisition Details')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Requisition Details - {{ $requisition->reference_number }}</h5>
        <a href="{{ route('admin.requisitions') }}" class="btn btn-sm btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <table class="table table-bordered">
                    <tr><th>Reference</th><td>{{ $requisition->reference_number }}</td></tr>
                    <tr><th>Employee</th><td>{{ $requisition->user->name }}</td></tr>
                    <tr><th>Department</th><td>{{ $requisition->department->name }}</td></tr>
                    <tr><th>Item Name</th><td>{{ $requisition->item_name }}</td></tr>
                    <tr><th>Quantity</th><td>{{ $requisition->quantity }} {{ $requisition->unit }}</td></tr>
                    <tr><th>Priority</th><td><span class="badge bg-{{ $requisition->getPriorityBadgeClass() }}">{{ ucfirst($requisition->priority) }}</span></td></tr>
                    <tr><th>Status</th><td><span class="badge bg-{{ $requisition->getStatusBadgeClass() }}">{{ ucfirst($requisition->status) }}</span></td></tr>
                    <tr><th>Estimated Cost</th><td>{{ $requisition->estimated_cost ? '$'.$requisition->estimated_cost : 'N/A' }}</td></tr>
                    <tr><th>Description</th><td>{{ $requisition->description ?? 'N/A' }}</td></tr>
                    <tr><th>Submitted</th><td>{{ $requisition->created_at->format('d M Y H:i') }}</td></tr>
                </table>
            </div>
            <div class="col-md-6">
                @if($requisition->approved_at)
                <div class="alert alert-info">
                    <strong>Approved by:</strong> {{ $requisition->approver->name ?? 'N/A' }}<br>
                    <strong>Approved at:</strong> {{ $requisition->approved_at->format('d M Y H:i') }}
                </div>
                @endif

                @if($requisition->status === 'rejected')
                <div class="alert alert-danger">
                    <strong>Rejection Reason:</strong><br>
                    {{ $requisition->rejection_reason }}
                </div>
                @endif

                @if($requisition->payment)
                <div class="alert alert-success">
                    <strong>Payment Info</strong><br>
                    Amount: ${{ $requisition->payment->amount }}<br>
                    Method: {{ $requisition->payment->payment_method }}<br>
                    Date: {{ $requisition->payment->payment_date->format('d M Y') }}<br>
                    Receipt: <a href="{{ asset('storage/'.$requisition->payment->receipt_path) }}" target="_blank">View Receipt</a>
                </div>
                @endif

                @if($requisition->status === 'pending')
                <div class="d-flex gap-2 mt-3">
                    <form method="POST" action="{{ route('admin.requisitions.approve', $requisition) }}">
                        @csrf
                        <button class="btn btn-success" onclick="return confirm('Approve this request?')">
                            <i class="fas fa-check"></i> Approve
                        </button>
                    </form>
                    <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">
                        <i class="fas fa-times"></i> Reject
                    </button>
                </div>

                <!-- Reject Modal -->
                <div class="modal fade" id="rejectModal" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Reject Requisition</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <form method="POST" action="{{ route('admin.requisitions.reject', $requisition) }}">
                                @csrf
                                <div class="modal-body">
                                    <textarea name="rejection_reason" class="form-control" rows="3" placeholder="Enter rejection reason..." required></textarea>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-danger">Reject</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection