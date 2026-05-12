@extends('layouts.app')

@section('title', 'Employee Dashboard')

@section('content')

<style>
/* ── Stat Cards ── */
.emp-cards {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
    margin-bottom: 24px;
}
.emp-card {
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
.emp-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 24px rgba(0,0,0,0.10);
}
.emp-card .ec-icon {
    width: 52px; height: 52px;
    border-radius: 13px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    color: #fff;
    flex-shrink: 0;
}
.ec-icon.ic-violet { background: #8b5cf6; }
.ec-icon.ic-amber  { background: #f59e0b; }
.ec-icon.ic-sky    { background: #0ea5e9; }
.ec-icon.ic-green  { background: #10b981; }

.emp-card .ec-body { flex: 1; }
.emp-card .ec-value {
    font-size: 1.9rem;
    font-weight: 800;
    color: #111827;
    line-height: 1;
    margin-bottom: 4px;
}
.emp-card .ec-label {
    font-size: 0.75rem;
    color: #6b7280;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.4px;
}
.emp-card::after {
    content: '';
    position: absolute;
    right: -10px; top: -10px;
    width: 70px; height: 70px;
    border-radius: 50%;
    opacity: 0.06;
}
.emp-card.c-violet::after { background: #8b5cf6; }
.emp-card.c-amber::after  { background: #f59e0b; }
.emp-card.c-sky::after    { background: #0ea5e9; }
.emp-card.c-green::after  { background: #10b981; }

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
    .emp-cards { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 576px) {
    .emp-cards { grid-template-columns: 1fr; }
}
</style>

<!-- Stat Cards -->
<div class="emp-cards">
    <div class="emp-card c-violet">
        <div class="ec-icon ic-violet"><i class="fas fa-clipboard-list"></i></div>
        <div class="ec-body">
            <div class="ec-value">{{ $totalRequests }}</div>
            <div class="ec-label">Total Requests</div>
        </div>
    </div>
    <div class="emp-card c-amber">
        <div class="ec-icon ic-amber"><i class="fas fa-clock"></i></div>
        <div class="ec-body">
            <div class="ec-value">{{ $pendingRequests }}</div>
            <div class="ec-label">Pending</div>
        </div>
    </div>
    <div class="emp-card c-sky">
        <div class="ec-icon ic-sky"><i class="fas fa-check"></i></div>
        <div class="ec-body">
            <div class="ec-value">{{ $approvedRequests }}</div>
            <div class="ec-label">Approved</div>
        </div>
    </div>
    <div class="emp-card c-green">
        <div class="ec-icon ic-green"><i class="fas fa-check-double"></i></div>
        <div class="ec-body">
            <div class="ec-value">{{ $completedRequests }}</div>
            <div class="ec-label">Completed</div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <a href="{{ route('employee.requisitions.create') }}" class="action-card">
            <div class="ac-btn-icon" style="background:#8b5cf6;">
                <i class="fas fa-plus"></i>
            </div>
            <h6>New Request</h6>
            <small>Submit a stock requisition</small>
        </a>
    </div>
    <div class="col-md-4">
        <a href="{{ route('employee.requisitions') }}" class="action-card">
            <div class="ac-btn-icon" style="background:#10b981;">
                <i class="fas fa-list"></i>
            </div>
            <h6>My Requests</h6>
            <small>Track your requisitions</small>
        </a>
    </div>
    <div class="col-md-4">
        <a href="{{ route('employee.stock-items') }}" class="action-card">
            <div class="ac-btn-icon" style="background:#f59e0b;">
                <i class="fas fa-boxes"></i>
            </div>
            <h6>View Stock</h6>
            <small>Browse available items</small>
        </a>
    </div>
</div>

<!-- Recent Requests -->
<div class="tbl-card">
    <div class="tbl-header">
        <div>
            <h6>My Recent Requests</h6>
            <small class="text-muted" style="font-size:0.78rem;">Your latest requisitions</small>
        </div>
        <a href="{{ route('employee.requisitions.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus me-1"></i> New Request
        </a>
    </div>
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
                <td style="color:#6b7280;">{{ $req->created_at->format('d M Y') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center py-5 text-muted">
                    <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                    No requests yet.
                    <a href="{{ route('employee.requisitions.create') }}">Create your first request!</a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection