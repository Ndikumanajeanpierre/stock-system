@extends('layouts.app')

@section('title', 'Manage Requisitions')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Requisitions</h5>
        <form method="GET" class="d-flex gap-2">
            <select name="status" class="form-select form-select-sm">
                <option value="">All Statuses</option>
                @foreach(['approved','purchased','paid','completed'] as $s)
                    <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>
                        {{ ucfirst($s) }}
                    </option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-primary btn-sm">Filter</button>
            <a href="{{ route('accountant.requisitions') }}" class="btn btn-secondary btn-sm">Reset</a>
        </form>
    </div>
    <div class="card-body">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Reference</th>
                    <th>Employee</th>
                    <th>Item</th>
                    <th>Qty</th>
                    <th>Priority</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($requisitions as $req)
                <tr>
                    <td>{{ $req->reference_number }}</td>
                    <td>{{ $req->user->name }}</td>
                    <td>{{ $req->item_name }}</td>
                    <td>{{ $req->quantity }} {{ $req->unit }}</td>
                    <td>
                        <span class="badge bg-{{ $req->getPriorityBadgeClass() }}">
                            {{ ucfirst($req->priority) }}
                        </span>
                    </td>
                    <td>
                        <span class="badge bg-{{ $req->getStatusBadgeClass() }}">
                            {{ ucfirst($req->status) }}
                        </span>
                    </td>
                    <td>{{ $req->created_at->format('d M Y') }}</td>
                    <td class="d-flex gap-1">
                        @if($req->status === 'approved')
                            <form method="POST" action="{{ route('accountant.requisitions.status', $req) }}">
                                @csrf
                                <input type="hidden" name="status" value="purchased">
                                <button class="btn btn-sm btn-primary" onclick="return confirm('Mark as purchased?')">
                                    <i class="fas fa-shopping-cart"></i> Mark Purchased
                                </button>
                            </form>
                        @elseif($req->status === 'purchased')
                            <a href="{{ route('accountant.requisitions.payment', $req) }}" class="btn btn-sm btn-success">
                                <i class="fas fa-upload"></i> Upload Payment
                            </a>
                        @elseif($req->status === 'paid')
                            <form method="POST" action="{{ route('accountant.requisitions.status', $req) }}">
                                @csrf
                                <input type="hidden" name="status" value="completed">
                                <button class="btn btn-sm btn-dark" onclick="return confirm('Mark as completed?')">
                                    <i class="fas fa-check-double"></i> Complete
                                </button>
                            </form>
                        @else
                            <span class="text-muted">No actions</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center">No requisitions found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection