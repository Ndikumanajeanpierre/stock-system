@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')

<style>
/* ── Stat Cards ── */
.dash-cards {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
    margin-bottom: 24px;
}
.dash-card {
    background: #fff;
    border-radius: 14px;
    padding: 22px 20px;
    display: flex;
    align-items: center;
    gap: 16px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    border: 1px solid #f0f0f0;
    transition: transform 0.15s, box-shadow 0.15s;
    position: relative;
    overflow: hidden;
}
.dash-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 24px rgba(0,0,0,0.10);
}
.dash-card .dc-icon {
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
.dc-icon.ic-blue   { background: #3b82f6; }
.dc-icon.ic-amber  { background: #f59e0b; }
.dc-icon.ic-rose   { background: #f43f5e; }
.dc-icon.ic-green  { background: #10b981; }

.dash-card .dc-body { flex: 1; }
.dash-card .dc-value {
    font-size: 1.9rem;
    font-weight: 800;
    color: #111827;
    line-height: 1;
    margin-bottom: 4px;
}
.dash-card .dc-label {
    font-size: 0.78rem;
    color: #6b7280;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.4px;
}
.dash-card::after {
    content: '';
    position: absolute;
    right: -10px; top: -10px;
    width: 70px; height: 70px;
    border-radius: 50%;
    opacity: 0.06;
}
.dash-card.c-blue::after   { background: #3b82f6; }
.dash-card.c-amber::after  { background: #f59e0b; }
.dash-card.c-rose::after   { background: #f43f5e; }
.dash-card.c-green::after  { background: #10b981; }

/* ── Charts ── */
.chart-card {
    background: #fff;
    border-radius: 14px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    border: 1px solid #f0f0f0;
    overflow: hidden;
    height: 100%;
}
.chart-card .chart-header {
    padding: 16px 20px 12px;
    border-bottom: 1px solid #f3f4f6;
    display: flex;
    align-items: center;
    gap: 8px;
}
.chart-card .chart-header .ch-dot {
    width: 8px; height: 8px;
    border-radius: 50%;
    display: inline-block;
}
.chart-card .chart-header h6 {
    margin: 0;
    font-size: 0.875rem;
    font-weight: 700;
    color: #111827;
}
.chart-card .chart-body {
    padding: 16px 20px 20px;
    position: relative;
    height: 280px;
    display: flex;
    align-items: center;
    justify-content: center;
}

@media (max-width: 992px) {
    .dash-cards { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 576px) {
    .dash-cards { grid-template-columns: 1fr; }
}
</style>

<!-- Stat Cards -->
<div class="dash-cards">
    <div class="dash-card c-blue">
        <div class="dc-icon ic-blue"><i class="fas fa-users"></i></div>
        <div class="dc-body">
            <div class="dc-value">{{ $totalUsers }}</div>
            <div class="dc-label">Total Users</div>
        </div>
    </div>
    <div class="dash-card c-amber">
        <div class="dc-icon ic-amber"><i class="fas fa-clipboard-list"></i></div>
        <div class="dc-body">
            <div class="dc-value">{{ $totalRequisitions }}</div>
            <div class="dc-label">Total Requisitions</div>
        </div>
    </div>
    <div class="dash-card c-rose">
        <div class="dc-icon ic-rose"><i class="fas fa-clock"></i></div>
        <div class="dc-body">
            <div class="dc-value">{{ $pendingRequisitions }}</div>
            <div class="dc-label">Pending Requests</div>
        </div>
    </div>
    <div class="dash-card c-green">
        <div class="dc-icon ic-green"><i class="fas fa-check-circle"></i></div>
        <div class="dc-body">
            <div class="dc-value">{{ $completedRequisitions }}</div>
            <div class="dc-label">Completed</div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row g-4 mb-4">
    <div class="col-md-5">
        <div class="chart-card">
            <div class="chart-header">
                <span class="ch-dot" style="background:#3b82f6;"></span>
                <h6>Requisitions by Status</h6>
            </div>
            <div class="chart-body">
                <canvas id="statusChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-7">
        <div class="chart-card">
            <div class="chart-header">
                <span class="ch-dot" style="background:#10b981;"></span>
                <h6>Monthly Requisitions — Last 6 Months</h6>
            </div>
            <div class="chart-body">
                <canvas id="monthlyChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Recent Requisitions -->
<div class="card" style="border-radius:14px; border:1px solid #f0f0f0; box-shadow:0 2px 8px rgba(0,0,0,0.06);">
    <div class="card-header d-flex justify-content-between align-items-center" style="border-bottom:1px solid #f3f4f6; background:#fff; border-radius:14px 14px 0 0; padding:16px 20px;">
        <div>
            <h6 class="mb-0 fw-bold" style="color:#111827;">Recent Requisitions</h6>
            <small class="text-muted">Latest submitted requests</small>
        </div>
        <a href="{{ route('admin.requisitions') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-eye me-1"></i> View All
        </a>
    </div>
    <div class="card-body p-0">
        <table class="table mb-0">
            <thead style="background:#f9fafb;">
                <tr>
                    <th style="font-size:0.75rem;color:#6b7280;text-transform:uppercase;letter-spacing:0.4px;padding:12px 16px;">Reference</th>
                    <th style="font-size:0.75rem;color:#6b7280;text-transform:uppercase;letter-spacing:0.4px;">Employee</th>
                    <th style="font-size:0.75rem;color:#6b7280;text-transform:uppercase;letter-spacing:0.4px;">Item</th>
                    <th style="font-size:0.75rem;color:#6b7280;text-transform:uppercase;letter-spacing:0.4px;">Department</th>
                    <th style="font-size:0.75rem;color:#6b7280;text-transform:uppercase;letter-spacing:0.4px;">Priority</th>
                    <th style="font-size:0.75rem;color:#6b7280;text-transform:uppercase;letter-spacing:0.4px;">Status</th>
                    <th style="font-size:0.75rem;color:#6b7280;text-transform:uppercase;letter-spacing:0.4px;">Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentRequisitions as $req)
                <tr style="border-bottom:1px solid #f9fafb;">
                    <td style="padding:12px 16px;"><span class="fw-bold text-primary">{{ $req->reference_number }}</span></td>
                    <td>{{ $req->user->name }}</td>
                    <td>{{ $req->item_name }}</td>
                    <td>{{ $req->department->name }}</td>
                    <td><span class="badge bg-{{ $req->getPriorityBadgeClass() }}">{{ ucfirst($req->priority) }}</span></td>
                    <td><span class="badge bg-{{ $req->getStatusBadgeClass() }}">{{ ucfirst($req->status) }}</span></td>
                    <td style="color:#6b7280; font-size:0.875rem;">{{ $req->created_at->format('d M Y') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5 text-muted">
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
window.addEventListener('load', function () {

    // ── Doughnut Chart ──
    new Chart(document.getElementById('statusChart'), {
        type: 'doughnut',
        data: {
            labels: ['Pending', 'Approved', 'Rejected', 'Purchased', 'Paid', 'Completed'],
            datasets: [{
                data: [
                    {{ $statusCounts['pending']   ?? 0 }},
                    {{ $statusCounts['approved']  ?? 0 }},
                    {{ $statusCounts['rejected']  ?? 0 }},
                    {{ $statusCounts['purchased'] ?? 0 }},
                    {{ $statusCounts['paid']      ?? 0 }},
                    {{ $statusCounts['completed'] ?? 0 }}
                ],
                backgroundColor: ['#f59e0b','#3b82f6','#f43f5e','#8b5cf6','#10b981','#374151'],
                borderWidth: 3,
                borderColor: '#fff',
                hoverOffset: 6,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            cutout: '65%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        font: { size: 11 },
                        padding: 14,
                        usePointStyle: true,
                        pointStyleWidth: 8,
                        color: '#374151',
                    }
                }
            }
        }
    });

    // ── Bar Chart ──
    new Chart(document.getElementById('monthlyChart'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($monthlyLabels) !!},
            datasets: [{
                label: 'Requisitions',
                data: {!! json_encode($monthlyData) !!},
                backgroundColor: '#3b82f6',
                borderRadius: 8,
                borderSkipped: false,
                hoverBackgroundColor: '#2563eb',
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#111827',
                    titleColor: '#f9fafb',
                    bodyColor: '#d1d5db',
                    padding: 10,
                    cornerRadius: 8,
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { color: '#6b7280', font: { size: 11 } }
                },
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1, color: '#6b7280', font: { size: 11 } },
                    grid: { color: '#f3f4f6' }
                }
            }
        }
    });
});
</script>
@endsection