@extends('layouts.app')

@section('title', 'Requisition Details')

@section('content')
<div class="card">
   <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">Requisition - {{ $requisition->reference_number }}</h5>
    <div class="d-flex gap-2">
        <a href="{{ route('employee.requisitions.print', $requisition) }}" 
            class="btn btn-sm btn-danger" target="_blank">
            <i class="fas fa-file-pdf me-1"></i> Print PDF
        </a>
        <a href="{{ route('employee.requisitions') }}" class="btn btn-sm btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
</div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <table class="table table-bordered">
                    <tr><th>Reference</th><td>{{ $requisition->reference_number }}</td></tr>
                    <tr><th>Item Name</th><td>{{ $requisition->item_name }}</td></tr>
                    <tr><th>Quantity</th><td>{{ $requisition->quantity }} {{ $requisition->unit }}</td></tr>
                    <tr><th>Department</th><td>{{ $requisition->department->name }}</td></tr>
                    <tr><th>Priority</th><td>
                        <span class="badge bg-{{ $requisition->getPriorityBadgeClass() }}">
                            {{ ucfirst($requisition->priority) }}
                        </span>
                    </td></tr>
                    <tr><th>Status</th><td>
                        <span class="badge bg-{{ $requisition->getStatusBadgeClass() }}">
                            {{ ucfirst($requisition->status) }}
                        </span>
                    </td></tr>
                    <tr><th>Estimated Cost</th><td>{{ $requisition->estimated_cost ? '$'.$requisition->estimated_cost : 'N/A' }}</td></tr>
                    <tr><th>Description</th><td>{{ $requisition->description ?? 'N/A' }}</td></tr>
                    <tr><th>Submitted</th><td>{{ $requisition->created_at->format('d M Y H:i') }}</td></tr>
                </table>
            </div>
            <div class="col-md-6">
                <!-- Status Timeline -->
                <h6 class="mb-3">Request Timeline</h6>
                <ul class="list-group">
                    <li class="list-group-item d-flex align-items-center gap-2">
                        <i class="fas fa-circle text-warning"></i>
                        <div>
                            <strong>Submitted</strong><br>
                            <small>{{ $requisition->created_at->format('d M Y H:i') }}</small>
                        </div>
                    </li>
                    @if($requisition->approved_at)
                    <li class="list-group-item d-flex align-items-center gap-2">
                        <i class="fas fa-circle text-info"></i>
                        <div>
                            <strong>Approved</strong><br>
                            <small>{{ $requisition->approved_at->format('d M Y H:i') }}</small>
                        </div>
                    </li>
                    @endif
                    @if($requisition->status === 'rejected')
                    <li class="list-group-item d-flex align-items-center gap-2">
                        <i class="fas fa-circle text-danger"></i>
                        <div>
                            <strong>Rejected</strong><br>
                            <small>{{ $requisition->rejection_reason }}</small>
                        </div>
                    </li>
                    @endif
                    @if(in_array($requisition->status, ['purchased','paid','completed']))
                    <li class="list-group-item d-flex align-items-center gap-2">
                        <i class="fas fa-circle text-primary"></i>
                        <div><strong>Purchased</strong></div>
                    </li>
                    @endif
                    @if(in_array($requisition->status, ['paid','completed']))
                    <li class="list-group-item d-flex align-items-center gap-2">
                        <i class="fas fa-circle text-success"></i>
                        <div><strong>Payment Uploaded</strong></div>
                    </li>
                    @endif
                    @if(in_array($requisition->status, ['paid','completed']))
    <li class="list-group-item d-flex align-items-center gap-2">
        <i class="fas fa-circle text-success"></i>
        <div>
            <strong>Payment Uploaded</strong>
            @if($requisition->payment)
            <br>
            <small>Amount: ${{ number_format($requisition->payment->amount, 2) }}</small><br>
            <a href="{{ asset('storage/'.$requisition->payment->receipt_path) }}"
                target="_blank" class="btn btn-sm btn-info mt-1 me-1">
                <i class="fas fa-eye me-1"></i> View Receipt
            </a>
            <a href="{{ asset('storage/'.$requisition->payment->receipt_path) }}"
                download="{{ $requisition->payment->receipt_original_name }}"
                class="btn btn-sm btn-success mt-1">
                <i class="fas fa-download me-1"></i> Download Receipt
            </a>
            @endif
        </div>
    </li>
@endif
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection