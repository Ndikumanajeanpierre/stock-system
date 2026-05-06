@extends('layouts.app')

@section('title', 'Available Stock Items')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="fas fa-boxes me-2"></i>Available Stock Items</h5>
        <a href="{{ route('employee.requisitions.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus me-2"></i> New Request
        </a>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Item Name</th>
                    <th>Category</th>
                    <th>Unit</th>
                    <th>Unit Price</th>
                    <th>Qty Available</th>
                    <th>Description</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($stockItems as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td><strong>{{ $item->name }}</strong></td>
                    <td>{{ $item->category ?? 'N/A' }}</td>
                    <td>{{ ucfirst($item->unit) }}</td>
                    <td><strong class="text-success">${{ number_format($item->unit_price, 2) }}</strong></td>
                    <td>
                        <span class="badge bg-{{ $item->quantity_available > 0 ? 'success' : 'danger' }}">
                            {{ $item->quantity_available }} {{ $item->unit }}
                        </span>
                    </td>
                    <td>{{ $item->description ?? 'N/A' }}</td>
                    <td>
                        <a href="{{ route('employee.requisitions.create') }}?item={{ $item->name }}&price={{ $item->unit_price }}&unit={{ $item->unit }}"
                            class="btn btn-sm btn-primary">
                            <i class="fas fa-paper-plane me-1"></i> Request
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-4">
                        <i class="fas fa-box-open fa-2x text-muted mb-2"></i><br>
                        No stock items available at the moment.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection