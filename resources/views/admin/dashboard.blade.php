@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="row g-4">
    <div class="col-md-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #667eea, #764ba2);">
            <div class="stat-icon"><i class="fas fa-users"></i></div>
            <div class="stat-number">{{ $totalUsers }}</div>
            <div class="stat-label">Total Users</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #f7971e, #ffd200);">
            <div class="stat-icon"><i class="fas fa-clipboard-list"></i></div>
            <div class="stat-number">{{ $totalRequisitions }}</div>
            <div class="stat-label">Total Requisitions</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #f093fb, #f5576c);">
            <div class="stat-icon"><i class="fas fa-clock"></i></div>
            <div class="stat-number">{{ $pendingRequisitions }}</div>
            <div class="stat-label">Pending Requests</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #11998e, #38ef7d);">
            <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
            <div class="stat-number">{{ $completedRequisitions }}</div>
            <div class="stat-label">Completed</div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row g-4">
    <div class="col-md-5">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0 fw-bold"><i class="fas fa-chart-pie me-2"></i>Requisitions by Status</h6>
            </div>
            <div class="card-body" style="height:280px; display:flex; align-items:center; justify-content:center;">
                <canvas id="statusChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-7">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0 fw-bold"><i class="fas fa-chart-bar me-2"></i>Monthly Requisitions (Last 6 Months)</h6>
            </div>
            <div class="card-body" style="height:280px; display:flex; align-items:center; justify-content:center;">
                <canvas id="monthlyChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Recent Requisitions -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            <h6 class="mb-0 fw-bold">Recent Requisitions</h6>
            <small class="text-muted">Latest submitted requests</small>
        </div>
        <a href="{{ route('admin.requisitions') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-eye me-1"></i> View All
        </a>
    </div>
    <div class="card-body p-0">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>Reference</th>
                    <th>Employee</th>
                    <th>Item</th>
                    <th>Department</th>
                    <th>Priority</th>
                    <th>Status</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentRequisitions as $req)
                <tr>
                    <td><span class="fw-bold text-primary">{{ $req->reference_number }}</span></td>
                    <td>{{ $req->user->name }}</td>
                    <td>{{ $req->item_name }}</td>
                    <td>{{ $req->department->name }}</td>
                    <td><span class="badge bg-{{ $req->getPriorityBadgeClass() }}">{{ ucfirst($req->priority) }}</span></td>
                    <td><span class="badge bg-{{ $req->getStatusBadgeClass() }}">{{ ucfirst($req->status) }}</span></td>
                    <td>{{ $req->created_at->format('d M Y') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-4 text-muted">
                        <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                        No requisitions yet.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
window.addEventListener('load', function() {
    // Status Chart
    const statusCtx = document.getElementById('statusChart');
    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: ['Pending', 'Approved', 'Rejected', 'Purchased', 'Paid', 'Completed'],
            datasets: [{
                data: [
                    {{ $statusCounts['pending'] ?? 0 }},
                    {{ $statusCounts['approved'] ?? 0 }},
                    {{ $statusCounts['rejected'] ?? 0 }},
                    {{ $statusCounts['purchased'] ?? 0 }},
                    {{ $statusCounts['paid'] ?? 0 }},
                    {{ $statusCounts['completed'] ?? 0 }}
                ],
                backgroundColor: ['#f7971e','#4facfe','#f5576c','#667eea','#11998e','#343a40'],
                borderWidth: 0,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { position: 'bottom', labels: { font: { size: 11 } } }
            }
        }
    });

    // Monthly Chart
    const monthlyCtx = document.getElementById('monthlyChart');
    new Chart(monthlyCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($monthlyLabels) !!},
            datasets: [{
                label: 'Requisitions',
                data: {!! json_encode($monthlyData) !!},
                backgroundColor: 'rgba(102, 126, 234, 0.8)',
                borderRadius: 8,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1 } }
            }
        }
    });
});
</script>
@endsection