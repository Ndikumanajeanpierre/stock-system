@extends('layouts.app')

@section('title', 'My Requisitions')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">My Requisitions</h5>
        <a href="{{ route('employee.requisitions.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> New Request
        </a>
    </div>
    <div class="card-body">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Reference</th>
                    <th>Item</th>
                    <th>Quantity</th>
                    <th>Department</th>
                    <th>Priority</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($requisitions as $req)
                <tr>
                    <td>{{ $req->reference_number }}</td>
                    <td>{{ $req->item_name }}</td>
                    <td>{{ $req->quantity }} {{ $req->unit }}</td>
                    <td>{{ $req->department->name }}</td>
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
                    <td>
    <div class="d-flex gap-1">
        <a href="{{ route('employee.requisitions.show', $req) }}" class="btn btn-sm btn-info">
            <i class="fas fa-eye"></i> View
        </a>
        @if($req->status === 'pending')
            <form method="POST" action="{{ route('employee.requisitions.cancel', $req) }}" class="d-inline">
                @csrf
                @method('DELETE')
                <button class="btn btn-sm btn-danger"
                    onclick="return confirm('Cancel this request?')">
                    <i class="fas fa-times"></i> Cancel
                </button>
            </form>
        @endif
    </div>
</td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center">
                        No requisitions yet.
                        <a href="{{ route('employee.requisitions.create') }}">Submit your first request!</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection