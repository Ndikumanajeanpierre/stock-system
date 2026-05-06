@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="row">
    <!-- Stat Cards -->
    <div class="col-md-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #3498db, #2980b9);">
            <div class="d-flex justify-content-between">
                <div>
                    <h6>Total Users</h6>
                    <h2>{{ $totalUsers }}</h2>
                </div>
                <i class="fas fa-users fa-2x opacity-75"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #f39c12, #e67e22);">
            <div class="d-flex justify-content-between">
                <div>
                    <h6>Total Requisitions</h6>
                    <h2>{{ $totalRequisitions }}</h2>
                </div>
                <i class="fas fa-list fa-2x opacity-75"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #e74c3c, #c0392b);">
            <div class="d-flex justify-content-between">
                <div>
                    <h6>Pending</h6>
                    <h2>{{ $pendingRequisitions }}</h2>
                </div>
                <i class="fas fa-clock fa-2x opacity-75"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #27ae60, #2ecc71);">
            <div class="d-flex justify-content-between">
                <div>
                    <h6>Completed</h6>
                    <h2>{{ $completedRequisitions }}</h2>
                </div>
                <i class="fas fa-check-circle fa-2x opacity-75"></i>
            </div>
        </div>
    </div>
</div>

<!-- Recent Requisitions -->
<div class="card mt-3">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Recent Requisitions</h5>
        <a href="{{ route('admin.requisitions') }}" class="btn btn-sm btn-primary">View All</a>
    </div>
    <div class="card-body">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Reference</th>
                    <th>Employee</th>
                    <th>Item</th>
                    <th>Department</th>
                    <th>Priority</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentRequisitions as $req)
                <tr>
                    <td>{{ $req->reference_number }}</td>
                    <td>{{ $req->user->name }}</td>
                    <td>{{ $req->item_name }}</td>
                    <td>{{ $req->department->name }}</td>
                    <td><span class="badge bg-{{ $req->getPriorityBadgeClass() }}">{{ ucfirst($req->priority) }}</span></td>
                    <td><span class="badge bg-{{ $req->getStatusBadgeClass() }}">{{ ucfirst($req->status) }}</span></td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center">No requisitions yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection