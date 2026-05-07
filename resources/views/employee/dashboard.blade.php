@extends('layouts.app')

@section('title', 'Employee Dashboard')

@section('content')
<div class="row g-4">
    <div class="col-md-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #667eea, #764ba2);">
            <div class="stat-icon"><i class="fas fa-clipboard-list"></i></div>
            <div class="stat-number">{{ $totalRequests }}</div>
            <div class="stat-label">Total Requests</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #f7971e, #ffd200);">
            <div class="stat-icon"><i class="fas fa-clock"></i></div>
            <div class="stat-number">{{ $pendingRequests }}</div>
            <div class="stat-label">Pending</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #4facfe, #00f2fe);">
            <div class="stat-icon"><i class="fas fa-check"></i></div>
            <div class="stat-number">{{ $approvedRequests }}</div>
            <div class="stat-label">Approved</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #11998e, #38ef7d);">
            <div class="stat-icon"><i class="fas fa-check-double"></i></div>
            <div class="stat-number">{{ $completedRequests }}</div>
            <div class="stat-label">Completed</div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row g-4 mb-2">
    <div class="col-md-4">
        <a href="{{ route('employee.requisitions.create') }}" style="text-decoration:none;">
            <div class="card h-100 text-center p-4" style="border: 2px dashed #667eea; background: #f8f7ff; cursor:pointer; transition: all 0.2s;">
                <div style="width:60px;height:60px;background:linear-gradient(135deg,#667eea,#764ba2);border-radius:16px;display:flex;align-items:center;justify-content:center;margin:0 auto 15px;color:white;font-size:1.5rem;">
                    <i class="fas fa-plus"></i>
                </div>
                <h6 class="fw-bold text-dark">New Request</h6>
                <small class="text-muted">Submit a stock requisition</small>
            </div>
        </a>
    </div>
    <div class="col-md-4">
        <a href="{{ route('employee.requisitions') }}" style="text-decoration:none;">
            <div class="card h-100 text-center p-4" style="border: 2px dashed #11998e; background: #f0fff8; cursor:pointer; transition: all 0.2s;">
                <div style="width:60px;height:60px;background:linear-gradient(135deg,#11998e,#38ef7d);border-radius:16px;display:flex;align-items:center;justify-content:center;margin:0 auto 15px;color:white;font-size:1.5rem;">
                    <i class="fas fa-list"></i>
                </div>
                <h6 class="fw-bold text-dark">My Requests</h6>
                <small class="text-muted">Track your requisitions</small>
            </div>
        </a>
    </div>
    <div class="col-md-4">
        <a href="{{ route('employee.stock-items') }}" style="text-decoration:none;">
            <div class="card h-100 text-center p-4" style="border: 2px dashed #f7971e; background: #fffbf0; cursor:pointer; transition: all 0.2s;">
                <div style="width:60px;height:60px;background:linear-gradient(135deg,#f7971e,#ffd200);border-radius:16px;display:flex;align-items:center;justify-content:center;margin:0 auto 15px;color:white;font-size:1.5rem;">
                    <i class="fas fa-boxes"></i>
                </div>
                <h6 class="fw-bold text-dark">View Stock</h6>
                <small class="text-muted">Browse available items</small>
            </div>
        </a>
    </div>
</div>

<!-- Recent Requests -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            <h6 class="mb-0 fw-bold">My Recent Requests</h6>
            <small class="text-muted">Your latest requisitions</small>
        </div>
        <a href="{{ route('employee.requisitions.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus me-1"></i> New Request
        </a>
    </div>
    <div class="card-body p-0">
        <table class="table mb-0">
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
                    <td><span class="fw-bold text-primary">{{ $req->reference_number }}</span></td>
                    <td>{{ $req->item_name }}</td>
                    <td>{{ $req->quantity }} {{ $req->unit }}</td>
                    <td>{{ $req->department->name }}</td>
                    <td><span class="badge bg-{{ $req->getPriorityBadgeClass() }}">{{ ucfirst($req->priority) }}</span></td>
                    <td><span class="badge bg-{{ $req->getStatusBadgeClass() }}">{{ ucfirst($req->status) }}</span></td>
                    <td>{{ $req->created_at->format('d M Y') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-4 text-muted">
                        <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                        No requests yet.
                        <a href="{{ route('employee.requisitions.create') }}">Create your first request!</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection