@extends('layouts.app')

@section('title', 'Employee Dashboard')

@section('content')
<div class="row">
    <div class="col-md-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #3498db, #2980b9);">
            <div class="d-flex justify-content-between">
                <div>
                    <h6>Total Requests</h6>
                    <h2>{{ $totalRequests }}</h2>
                </div>
                <i class="fas fa-list fa-2x opacity-75"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #f39c12, #e67e22);">
            <div class="d-flex justify-content-between">
                <div>
                    <h6>Pending</h6>
                    <h2>{{ $pendingRequests }}</h2>
                </div>
                <i class="fas fa-clock fa-2x opacity-75"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #3498db, #1abc9c);">
            <div class="d-flex justify-content-between">
                <div>
                    <h6>Approved</h6>
                    <h2>{{ $approvedRequests }}</h2>
                </div>
                <i class="fas fa-check fa-2x opacity-75"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #27ae60, #2ecc71);">
            <div class="d-flex justify-content-between">
                <div>
                    <h6>Completed</h6>
                    <h2>{{ $completedRequests }}</h2>
                </div>
                <i class="fas fa-check-circle fa-2x opacity-75"></i>
            </div>
        </div>
    </div>
</div>

<!-- Recent Requests -->
<div class="card mt-3">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">My Recent Requests</h5>
        <a href="{{ route('employee.requisitions.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> New Request
        </a>
    </div>
    <div class="card-body">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Reference</th>
                    <th>Item</th>
                    <th>Quantity</th>
                    <th>Department</th>
                    <th>Priority</th>
                    <th>Status</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentRequests as $req)
                <tr>
                    <td>{{ $req->reference_number }}</td>
                    <td>{{ $req->item_name }}</td>
                    <td>{{ $req->quantity }} {{ $req->unit }}</td>
                    <td>{{ $req->department->name }}</td>
                    <td><span class="badge bg-{{ $req->getPriorityBadgeClass() }}">{{ ucfirst($req->priority) }}</span></td>
                    <td><span class="badge bg-{{ $req->getStatusBadgeClass() }}">{{ ucfirst($req->status) }}</span></td>
                    <td>{{ $req->created_at->format('d M Y') }}</td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center">No requests yet. <a href="{{ route('employee.requisitions.create') }}">Create one!</a></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection