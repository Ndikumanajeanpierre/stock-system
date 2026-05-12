@extends('layouts.app')

@section('title', 'Reports')

@section('content')

<style>
.report-cards {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
    margin-bottom: 24px;
}
.report-card {
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
.report-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 24px rgba(0,0,0,0.10);
}
.report-card .rc-icon {
    width: 52px;
    height: 52px;
    border-radius: 13px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    color: #fff;
    flex-shrink: 0;
}
.rc-icon.ic-blue   { background: #3b82f6; }
.rc-icon.ic-amber  { background: #f59e0b; }
.rc-icon.ic-green  { background: #10b981; }
.rc-icon.ic-violet { background: #8b5cf6; }

.report-card .rc-body { flex: 1; min-width: 0; }
.report-card .rc-value {
    font-size: 1.75rem;
    font-weight: 800;
    color: #111827;
    line-height: 1.1;
    margin-bottom: 4px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.report-card .rc-value.small-val { font-size: 1.1rem; }
.report-card .rc-label {
    font-size: 0.75rem;
    color: #6b7280;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.4px;
}
.report-card::after {
    content: '';
    position: absolute;
    right: -10px; top: -10px;
    width: 70px; height: 70px;
    border-radius: 50%;
    opacity: 0.06;
}
.report-card.c-blue::after   { background: #3b82f6; }
.report-card.c-amber::after  { background: #f59e0b; }
.report-card.c-green::after  { background: #10b981; }
.report-card.c-violet::after { background: #8b5cf6; }

/* Filter card */
.filter-card {
    background: #fff;
    border-radius: 14px;
    border: 1px solid #f0f0f0;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    margin-bottom: 20px;
    overflow: hidden;
}
.filter-card .filter-header {
    padding: 14px 20px;
    border-bottom: 1px solid #f3f4f6;
    display: flex;
    align-items: center;
    gap: 8px;
}
.filter-card .filter-header h6 {
    margin: 0;
    font-size: 0.875rem;
    font-weight: 700;
    color: #111827;
}
.filter-card .filter-body { padding: 16px 20px; }

/* Table cards */
.tbl-card {
    background: #fff;
    border-radius: 14px;
    border: 1px solid #f0f0f0;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    overflow: hidden;
}
.tbl-card .tbl-header {
    padding: 14px 20px;
    border-bottom: 1px solid #f3f4f6;
    display: flex;
    align-items: center;
    gap: 8px;
}
.tbl-card .tbl-header h6 {
    margin: 0;
    font-size: 0.875rem;
    font-weight: 700;
    color: #111827;
}
.tbl-card table thead tr th {
    font-size: 0.72rem;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: 0.4px;
    background: #f9fafb;
    padding: 10px 14px;
    border-bottom: 1px solid #f0f0f0;
}
.tbl-card table tbody tr td {
    padding: 10px 14px;
    font-size: 0.845rem;
    color: #374151;
    border-bottom: 1px solid #f9fafb;
    vertical-align: middle;
}

@media (max-width: 992px) {
    .report-cards { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 576px) {
    .report-cards { grid-template-columns: 1fr; }
}
</style>

<!-- Summary Cards -->
<div class="report-cards">
    <div class="report-card c-blue">
        <div class="rc-icon ic-blue"><i class="fas fa-list"></i></div>
        <div class="rc-body">
            <div class="rc-value">{{ $totalRequisitions }}</div>
            <div class="rc-label">Total Requisitions</div>
        </div>
    </div>
    <div class="report-card c-amber">
        <div class="rc-icon ic-amber"><i class="fas fa-clock"></i></div>
        <div class="rc-body">
            <div class="rc-value">{{ $totalPending }}</div>
            <div class="rc-label">Pending</div>
        </div>
    </div>
    <div class="report-card c-green">
        <div class="rc-icon ic-green"><i class="fas fa-check-circle"></i></div>
        <div class="rc-body">
            <div class="rc-value">{{ $totalPaid }}</div>
            <div class="rc-label">Paid / Completed</div>
        </div>
    </div>
    <div class="report-card c-violet">
        <div class="rc-icon ic-violet"><i class="fas fa-money-bill-wave"></i></div>
        <div class="rc-body">
            <div class="rc-value small-val">RWF {{ number_format($totalAmount, 2) }}</div>
            <div class="rc-label">Total Payments</div>
        </div>
    </div>
</div>

<div class="d-flex justify-content-end mb-3">
    <a href="{{ route('admin.reports.export-pdf', request()->query()) }}" class="btn btn-danger">
        <i class="fas fa-file-pdf me-2"></i> Export PDF
    </a>
</div>

<!-- Filters -->
<div class="filter-card">
    <div class="filter-header">
        <span style="color:#3b82f6;"><i class="fas fa-filter"></i></span>
        <h6>Filter Report</h6>
    </div>
    <div class="filter-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label" style="font-size:0.8rem;font-weight:600;color:#374151;">Status</label>
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
                <label class="form-label" style="font-size:0.8rem;font-weight:600;color:#374151;">Department</label>
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
                <label class="form-label" style="font-size:0.8rem;font-weight:600;color:#374151;">Date From</label>
                <input type="date" name="date_from" class="form-control form-control-sm" value="{{ request('date_from') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label" style="font-size:0.8rem;font-weight:600;color:#374151;">Date To</label>
                <input type="date" name="date_to" class="form-control form-control-sm" value="{{ request('date_to') }}">
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary btn-sm w-100">
                    <i class="fas fa-search me-1"></i> Filter
                </button>
                <a href="{{ route('admin.reports') }}" class="btn btn-secondary btn-sm w-100">
                    <i class="fas fa-redo me-1"></i> Reset
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Department Breakdown + Requisitions Table -->
<div class="row g-3">
    <div class="col-md-3">
        <div class="tbl-card">
            <div class="tbl-header">
                <span style="color:#3b82f6;"><i class="fas fa-building"></i></span>
                <h6>By Department</h6>
            </div>
            <table class="table mb-0">
                <thead>
                    <tr><th>Department</th><th>Total</th></tr>
                </thead>
                <tbody>
                    @foreach($departmentStats as $stat)
                    <tr>
                        <td>{{ $stat->department->name ?? 'N/A' }}</td>
                        <td><span class="badge bg-primary">{{ $stat->total }}</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="col-md-9">
        <div class="tbl-card">
            <div class="tbl-header">
                <span style="color:#3b82f6;"><i class="fas fa-list"></i></span>
                <h6>Requisitions ({{ $totalRequisitions }})</h6>
            </div>
            <table class="table table-hover mb-0">
                <thead>
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
                        <td><span class="fw-bold text-primary">{{ $req->reference_number }}</span></td>
                        <td>{{ $req->user->name }}</td>
                        <td>{{ $req->item_name }}</td>
                        <td>{{ $req->department->name }}</td>
                        <td><span class="badge bg-{{ $req->getStatusBadgeClass() }}">{{ ucfirst($req->status) }}</span></td>
                        <td>{{ $req->payment ? 'RWF '.number_format($req->payment->amount, 2) : 'N/A' }}</td>
                        <td style="color:#6b7280;">{{ $req->created_at->format('d M Y') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center py-4 text-muted">No records found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection