@extends('layouts.app')

@section('title', 'Stock Report')

@section('content')

<!-- Summary Cards -->
<div class="row g-4">
    <div class="col-md-2">
        <div class="stat-card" style="background: linear-gradient(135deg, #667eea, #764ba2);">
            <div class="stat-icon"><i class="fas fa-boxes"></i></div>
            <div class="stat-number">{{ $totalItems }}</div>
            <div class="stat-label">Total Items</div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="stat-card" style="background: linear-gradient(135deg, #11998e, #38ef7d);">
            <div class="stat-icon"><i class="fas fa-cubes"></i></div>
            <div class="stat-number">{{ $totalAvailable }}</div>
            <div class="stat-label">Total Available</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #f7971e, #ffd200);">
            <div class="stat-icon"><i class="fas fa-dollar-sign"></i></div>
            <div class="stat-number">${{ number_format($totalValue, 0) }}</div>
            <div class="stat-label">Total Stock Value</div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="stat-card" style="background: linear-gradient(135deg, #4facfe, #00f2fe);">
            <div class="stat-icon"><i class="fas fa-arrow-up"></i></div>
            <div class="stat-number">{{ $totalIn }}</div>
            <div class="stat-label">Total In</div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="stat-card" style="background: linear-gradient(135deg, #f093fb, #f5576c);">
            <div class="stat-icon"><i class="fas fa-arrow-down"></i></div>
            <div class="stat-number">{{ $totalOut }}</div>
            <div class="stat-label">Total Out</div>
        </div>
    </div>
    <div class="col-md-1">
        <div class="stat-card" style="background: linear-gradient(135deg, #e74c3c, #c0392b);">
            <div class="stat-icon"><i class="fas fa-exclamation"></i></div>
            <div class="stat-number">{{ $outOfStockItems->count() }}</div>
            <div class="stat-label">Out of Stock</div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Low Stock Warning -->
    @if($lowStockItems->count() > 0)
    <div class="col-md-12">
        <div class="card border-warning">
            <div class="card-header" style="background:#fff3cd;">
                <h6 class="mb-0 text-warning fw-bold">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Low Stock Alert ({{ $lowStockItems->count() }} items)
                </h6>
            </div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Category</th>
                            <th>Remaining</th>
                            <th>Unit Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($lowStockItems as $item)
                        <tr>
                            <td><strong>{{ $item->name }}</strong></td>
                            <td>{{ $item->category ?? 'N/A' }}</td>
                            <td><span class="badge bg-warning text-dark">{{ $item->quantity_available }} {{ $item->unit }}</span></td>
                            <td>${{ number_format($item->unit_price, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    <!-- All Stock Items -->
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0 fw-bold"><i class="fas fa-warehouse me-2"></i>All Stock Items</h6>
                <small class="text-muted">Complete inventory with movement tracking</small>
            </div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Item Name</th>
                            <th>Category</th>
                            <th>Unit Price</th>
                            <th>Total In</th>
                            <th>Total Out</th>
                            <th>Available</th>
                            <th>Stock Value</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($stockItems as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td><strong>{{ $item->name }}</strong><br><small class="text-muted">{{ $item->unit }}</small></td>
                            <td>{{ $item->category ?? 'N/A' }}</td>
                            <td>${{ number_format($item->unit_price, 2) }}</td>
                            <td><span class="badge bg-info">{{ $item->totalIn() }}</span></td>
                            <td><span class="badge bg-danger">{{ $item->totalOut() }}</span></td>
                            <td>
                                <span class="badge bg-{{ $item->quantity_available > 5 ? 'success' : ($item->quantity_available > 0 ? 'warning' : 'danger') }}">
                                    {{ $item->quantity_available }}
                                </span>
                            </td>
                            <td><strong class="text-success">${{ number_format($item->quantity_available * $item->unit_price, 2) }}</strong></td>
                            <td>
                                @if($item->quantity_available == 0)
                                    <span class="badge bg-danger">Out of Stock</span>
                                @elseif($item->quantity_available < 5)
                                    <span class="badge bg-warning text-dark">Low Stock</span>
                                @else
                                    <span class="badge bg-success">In Stock</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-4 text-muted">
                                <i class="fas fa-box-open fa-2x mb-2 d-block"></i>
                                No stock items found.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Recent Movements -->
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0 fw-bold"><i class="fas fa-exchange-alt me-2"></i>Recent Stock Movements</h6>
                <small class="text-muted">Latest stock in/out activity</small>
            </div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Type</th>
                            <th>Quantity</th>
                            <th>Reference</th>
                            <th>Note</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentMovements as $movement)
                        <tr>
                            <td><strong>{{ $movement->stockItem->name ?? 'N/A' }}</strong></td>
                            <td>
                                <span class="badge bg-{{ $movement->type == 'in' ? 'success' : 'danger' }}">
                                    <i class="fas fa-arrow-{{ $movement->type == 'in' ? 'up' : 'down' }} me-1"></i>
                                    {{ strtoupper($movement->type) }}
                                </span>
                            </td>
                            <td>{{ $movement->quantity }}</td>
                            <td>{{ $movement->requisition->reference_number ?? 'Manual' }}</td>
                            <td>{{ $movement->note ?? 'N/A' }}</td>
                            <td>{{ $movement->created_at->format('d M Y H:i') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                No movements recorded yet.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection