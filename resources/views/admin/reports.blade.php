@extends('layouts.app')

@section('title', 'Reports')

@section('content')
<!-- Summary Cards -->
<div class="row mb-3">
    <div class="col-md-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #3498db, #2980b9);">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-1">Total Requisitions</h6>
                    <h2 class="mb-0">{{ $totalRequisitions }}</h2>
                </div>
                <i class="fas fa-list fa-2x opacity-75"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #f39c12, #e67e22);">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-1">Pending</h6>
                    <h2 class="mb-0">{{ $totalPending }}</h2>
                </div>
                <i class="fas fa-clock fa-2x opacity-75"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #27ae60, #2ecc71);">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-1">Paid/Completed</h6>
                    <h2 class="mb-0">{{ $totalPaid }}</h2>
                </div>
                <i class="fas fa-check-circle fa-2x opacity-75"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #8e44ad, #9b59b6);">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-1">Total Payments</h6>
                    <h2 class="mb-0">${{ number_format($totalAmount, 2) }}</h2>
                </div>
                <i class="fas fa-dollar-sign fa-2x opacity-75"></i>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card mb-3">
    <div class="card-header"><h5 class="mb-0"><i class="fas fa-filter me-2"></i>Filter Report</h5></div>
    <div class="card-body">
        <form method="GET" class="row g-2">
            <div class="col-md-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select form-select-sm">
                    <option value="">All Statuses</option>
                    @foreach(['pending','approved','rejected','purchased','paid','completed'] as $s)
                        <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>
                            {{ ucfirst($s) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Department</label>
                <select name="department_id" class="form-select form-select-sm">
                    <option value="">All Departments</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>
                            {{ $dept->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Date From</label>
                <input type="date" name="date_from" class="form-control form-control-sm" value="{{ request('date_from') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label">Date To</label>
                <input type="date" name="date_to" class="form-control form-control-sm" value="{{ request('date_to') }}">
            </div>
            <div class="col-md-2 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-primary btn-sm w-100">
                    <i class="fas fa-search"></i> Filter
                </button>
                <a href="{{ route('admin.reports') }}" class="btn btn-secondary btn-sm w-100">
                    <i class="fas fa-redo"></i> Reset
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Department Breakdown + Requisitions Table -->
<div class="row">
    <div class="col-md-3">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-building me-2"></i>By Department</h6>
            </div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <thead class="table-light">
                        <tr><th>Department</th><th>Total</th></tr>
                    </thead>
                    <tbody>
                        @foreach($departmentStats as $stat)
                        <tr>
                            <td style="font-size:13px;">{{ $stat->department->name ?? 'N/A' }}</td>
                            <td><span class="badge bg-primary">{{ $stat->total }}</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-9">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-list me-2"></i>Requisitions ({{ $totalRequisitions }})</h6>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover table-sm mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Reference</th>
                            <th>Employee</th>
                            <th>Item</th>
                            <th>Department</th>
                            <th>Status</th>
                            <th>Amount</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($requisitions as $req)
                        <tr>
                            <td style="font-size:13px;">{{ $req->reference_number }}</td>
                            <td style="font-size:13px;">{{ $req->user->name }}</td>
                            <td style="font-size:13px;">{{ $req->item_name }}</td>
                            <td style="font-size:13px;">{{ $req->department->name }}</td>
                            <td><span class="badge bg-{{ $req->getStatusBadgeClass() }}">{{ ucfirst($req->status) }}</span></td>
                            <td style="font-size:13px;">{{ $req->payment ? '$'.number_format($req->payment->amount,2) : 'N/A' }}</td>
                            <td style="font-size:13px;">{{ $req->created_at->format('d M Y') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="text-center py-3">No records found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection