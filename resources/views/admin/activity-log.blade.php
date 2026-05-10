@extends('layouts.app')

@section('title', 'Activity Log')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            <h6 class="mb-0 fw-bold"><i class="fas fa-history me-2"></i>Activity Log</h6>
            <small class="text-muted">All system activities and actions</small>
        </div>
    </div>
    <div class="card-body p-0">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>User</th>
                    <th>Action</th>
                    <th>Description</th>
                    <th>IP Address</th>
                    <th>Date & Time</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                <tr>
                    <td>{{ $log->id }}</td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            @if($log->user && $log->user->profile_photo)
                                <img src="{{ asset('storage/'.$log->user->profile_photo) }}"
                                    style="width:28px;height:28px;border-radius:50%;object-fit:cover;">
                            @else
                                <div style="width:28px;height:28px;border-radius:50%;background:linear-gradient(135deg,#667eea,#764ba2);display:flex;align-items:center;justify-content:center;color:white;font-size:0.7rem;font-weight:700;">
                                    {{ $log->user ? strtoupper(substr($log->user->name, 0, 1)) : '?' }}
                                </div>
                            @endif
                            <div>
                                <div style="font-size:0.825rem;font-weight:600;">{{ $log->user->name ?? 'System' }}</div>
                                <div style="font-size:0.75rem;color:#999;">{{ $log->user->role ?? '' }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        @php
                            $actionColors = [
                                'submitted_request' => 'primary',
                                'approved_request'  => 'success',
                                'rejected_request'  => 'danger',
                                'cancelled_request' => 'warning',
                                'uploaded_payment'  => 'info',
                            ];
                            $color = $actionColors[$log->action] ?? 'secondary';
                        @endphp
                        <span class="badge bg-{{ $color }}">
                            {{ ucfirst(str_replace('_', ' ', $log->action)) }}
                        </span>
                    </td>
                    <td style="font-size:0.825rem;">{{ $log->description }}</td>
                    <td style="font-size:0.825rem;">{{ $log->ip_address ?? 'N/A' }}</td>
                    <td style="font-size:0.825rem;">{{ $log->created_at->format('d M Y H:i') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-4 text-muted">
                        <i class="fas fa-history fa-2x mb-2 d-block"></i>
                        No activity logs yet.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">
        {{ $logs->links() }}
    </div>
</div>
@endsection