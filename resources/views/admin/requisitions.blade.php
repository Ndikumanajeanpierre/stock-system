@extends('layouts.app')

@section('title', 'All Requisitions')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0 fw-bold">All Requisitions</h5>
    </div>
    <div class="card-body">
        {{-- Filters & Search --}}
        <form method="GET" class="row mb-3 g-2">
            <div class="col-md-3">
                <input type="text" name="search" class="form-control"
                    placeholder="Search item, employee..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select">
                    <option value="">All Statuses</option>
                    @foreach(['pending','approved','rejected','purchased','paid','completed'] as $s)
                        <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select name="department_id" class="form-select">
                    <option value="">All Departments</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}" placeholder="From">
            </div>
            <div class="col-md-2">
                <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}" placeholder="To">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search me-1"></i> Search
                </button>
            </div>
            <div class="col-md-2">
                <a href="{{ route('admin.requisitions') }}" class="btn btn-secondary w-100">
                    <i class="fas fa-redo me-1"></i> Reset
                </a>
            </div>
        </form>

        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Reference</th>
                    <th>Employee</th>
                    <th>Item</th>
                    <th>Qty</th>
                    <th>Department</th>
                    <th>Priority</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($requisitions as $req)
                <tr>
                    <td><span class="fw-bold text-primary">{{ $req->reference_number }}</span></td>
                    <td>{{ $req->user->name }}</td>
                    <td>{{ $req->item_name }}</td>
                    <td>{{ $req->quantity }} {{ $req->unit }}</td>
                    <td>{{ $req->department->name }}</td>
                    <td><span class="badge bg-{{ $req->getPriorityBadgeClass() }}">{{ ucfirst($req->priority) }}</span></td>
                    <td><span class="badge bg-{{ $req->getStatusBadgeClass() }}">{{ ucfirst($req->status) }}</span></td>
                    <td>{{ $req->created_at->format('d M Y') }}</td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('admin.requisitions.show', $req) }}" class="btn btn-sm btn-info">
                                <i class="fas fa-eye"></i>
                            </a>
                            @if($req->status === 'pending')
                                <form method="POST" action="{{ route('admin.requisitions.approve', $req) }}" class="d-inline">
                                    @csrf
                                    <button class="btn btn-sm btn-success" onclick="return confirm('Approve this request?')">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </form>
                                <button class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                    data-bs-target="#rejectModal{{ $req->id }}">
                                    <i class="fas fa-times"></i>
                                </button>
                                <!-- Reject Modal -->
                                <div class="modal fade" id="rejectModal{{ $req->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Reject Requisition</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form method="POST" action="{{ route('admin.requisitions.reject', $req) }}">
                                                @csrf
                                                <div class="modal-body">
                                                    <textarea name="rejection_reason" class="form-control" rows="3"
                                                        placeholder="Enter rejection reason..." required></textarea>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-danger">Reject</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="9" class="text-center py-4 text-muted">No requisitions found.</td></tr>
                @endforelse
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="mt-3">
            {{ $requisitions->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection