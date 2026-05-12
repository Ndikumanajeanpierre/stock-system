@extends('layouts.app')

@section('title', 'Stock Report')

@section('content')

<style>
.stock-cards-row {
    display: grid;
    grid-template-columns: repeat(6, 1fr);
    gap: 16px;
    margin-bottom: 24px;
}
.stock-card {
    background: #fff;
    border-radius: 14px;
    padding: 20px 16px;
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    justify-content: space-between;
    min-height: 130px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.07);
    border: 1px solid #f0f0f0;
    position: relative;
    overflow: hidden;
    transition: transform 0.15s, box-shadow 0.15s;
}
.stock-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 20px rgba(0,0,0,0.11);
}
.stock-card .card-icon {
    width: 38px;
    height: 38px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    color: #fff;
    margin-bottom: 12px;
}
.stock-card .card-value {
    font-size: 1.45rem;
    font-weight: 700;
    color: #1a1a2e;
    line-height: 1.1;
    margin-bottom: 4px;
    word-break: break-word;
}
.stock-card .card-value.small-val {
    font-size: 1.05rem;
}
.stock-card .card-label {
    font-size: 0.75rem;
    color: #888;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.stock-card .card-accent {
    position: absolute;
    bottom: 0; right: 0;
    width: 60px; height: 60px;
    border-radius: 50%;
    opacity: 0.08;
    transform: translate(20px, 20px);
}
.icon-purple  { background: #6c63ff; }
.icon-teal    { background: #00b894; }
.icon-amber   { background: #f39c12; }
.icon-blue    { background: #0984e3; }
.icon-pink    { background: #e84393; }
.icon-red     { background: #d63031; }
.accent-purple { background: #6c63ff; }
.accent-teal   { background: #00b894; }
.accent-amber  { background: #f39c12; }
.accent-blue   { background: #0984e3; }
.accent-pink   { background: #e84393; }
.accent-red    { background: #d63031; }

@media (max-width: 1200px) {
    .stock-cards-row { grid-template-columns: repeat(3, 1fr); }
}
@media (max-width: 768px) {
    .stock-cards-row { grid-template-columns: repeat(2, 1fr); }
}
</style>

{{-- ✅ FIXED: Export button uses correct route based on user role --}}
<div class="d-flex justify-content-end mb-3">
    @if(auth()->user()->role === 'accountant')
        <a href="{{ route('accountant.stock-report.export') }}" class="btn btn-danger">
            <i class="fas fa-file-pdf me-2"></i> Export Stock PDF
        </a>
    @else
        <a href="{{ route('admin.stock-report.export') }}" class="btn btn-danger">
            <i class="fas fa-file-pdf me-2"></i> Export Stock PDF
        </a>
    @endif
</div>

<!-- Summary Cards -->
<div class="stock-cards-row">

    <div class="stock-card">
        <div class="card-icon icon-purple"><i class="fas fa-boxes"></i></div>
        <div>
            <div class="card-value">{{ $totalItems }}</div>
            <div class="card-label">Total Items</div>
        </div>
        <div class="card-accent accent-purple"></div>
    </div>

    <div class="stock-card">
        <div class="card-icon icon-teal"><i class="fas fa-cubes"></i></div>
        <div>
            <div class="card-value">{{ $totalAvailable }}</div>
            <div class="card-label">Total Available</div>
        </div>
        <div class="card-accent accent-teal"></div>
    </div>

    <div class="stock-card">
        <div class="card-icon icon-amber"><i class="fas fa-money-bill-wave"></i></div>
        <div>
            <div class="card-value small-val">RWF {{ number_format($totalValue, 0) }}</div>
            <div class="card-label">Total Stock Value</div>
        </div>
        <div class="card-accent accent-amber"></div>
    </div>

    <div class="stock-card">
        <div class="card-icon icon-blue"><i class="fas fa-arrow-up"></i></div>
        <div>
            <div class="card-value">{{ $totalIn }}</div>
            <div class="card-label">Total In</div>
        </div>
        <div class="card-accent accent-blue"></div>
    </div>

    <div class="stock-card">
        <div class="card-icon icon-pink"><i class="fas fa-arrow-down"></i></div>
        <div>
            <div class="card-value">{{ $totalOut }}</div>
            <div class="card-label">Total Out</div>
        </div>
        <div class="card-accent accent-pink"></div>
    </div>

    <div class="stock-card">
        <div class="card-icon icon-red"><i class="fas fa-exclamation-triangle"></i></div>
        <div>
            <div class="card-value">{{ $outOfStockItems->count() }}</div>
            <div class="card-label">Out of Stock</div>
        </div>
        <div class="card-accent accent-red"></div>
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
                            <td>RWF {{ number_format($item->unit_price, 2) }}</td>
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
                            <td>
                                <strong>{{ $item->name }}</strong><br>
                                <small class="text-muted">{{ $item->unit }}</small>
                            </td>
                            <td>{{ $item->category ?? 'N/A' }}</td>
                            <td>RWF {{ number_format($item->unit_price, 2) }}</td>
                            <td><span class="badge bg-info">{{ $item->totalIn() }}</span></td>
                            <td><span class="badge bg-danger">{{ $item->totalOut() }}</span></td>
                            <td>
                                <span class="badge bg-{{ $item->quantity_available > 5 ? 'success' : ($item->quantity_available > 0 ? 'warning' : 'danger') }}">
                                    {{ $item->quantity_available }}
                                </span>
                            </td>
                            <td>
                                <strong class="text-success">
                                    RWF {{ number_format($item->quantity_available * $item->unit_price, 2) }}
                                </strong>
                            </td>
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