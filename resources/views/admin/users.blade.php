@extends('layouts.app')

@section('title', 'Manage Users')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            <h5 class="mb-0 fw-bold">All Users</h5>
            <small class="text-muted">Manage system users</small>
        </div>
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus me-1"></i> Add User
        </a>
    </div>
    <div class="card-body p-0">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Department</th>
                    <th>Phone</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            @if($user->profile_photo)
                                <img src="{{ asset('storage/'.$user->profile_photo) }}"
                                    style="width:32px;height:32px;border-radius:50%;object-fit:cover;">
                            @else
                                <div style="width:32px;height:32px;border-radius:50%;background:linear-gradient(135deg,#667eea,#764ba2);display:flex;align-items:center;justify-content:center;color:white;font-size:0.75rem;font-weight:700;">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                            @endif
                            <strong>{{ $user->name }}</strong>
                        </div>
                    </td>
                    <td>{{ $user->email }}</td>
                    <td>
                        <span class="badge bg-{{ $user->role === 'admin' ? 'danger' : ($user->role === 'accountant' ? 'warning' : 'primary') }}">
                            {{ ucfirst($user->role) }}
                        </span>
                    </td>
                    <td>{{ $user->department ?? 'N/A' }}</td>
                    <td>{{ $user->phone ?? 'N/A' }}</td>
                    <td>
                        <span class="badge bg-{{ $user->is_active ? 'success' : 'secondary' }}">
                            {{ $user->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            <!-- Toggle Active -->
                            <form method="POST" action="{{ route('admin.users.toggle', $user) }}" class="d-inline">
                                @csrf
                                <button class="btn btn-sm btn-{{ $user->is_active ? 'warning' : 'success' }}"
                                    onclick="return confirm('Are you sure?')">
                                    <i class="fas fa-{{ $user->is_active ? 'ban' : 'check' }}"></i>
                                </button>
                            </form>

                            <!-- Edit -->
                            <button class="btn btn-sm btn-info" data-bs-toggle="modal"
                                data-bs-target="#editModal{{ $user->id }}">
                                <i class="fas fa-edit"></i>
                            </button>

                            <!-- Delete -->
                            @if($user->id !== auth()->id())
                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger"
                                    onclick="return confirm('Delete this user permanently?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                            @endif
                        </div>

                        <!-- Edit Modal -->
                        <div class="modal fade" id="editModal{{ $user->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit User - {{ $user->name }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form method="POST" action="{{ route('admin.users.update', $user) }}">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body">
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label class="form-label">Full Name</label>
                                                    <input type="text" name="name" class="form-control"
                                                        value="{{ $user->name }}" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Email</label>
                                                    <input type="email" name="email" class="form-control"
                                                        value="{{ $user->email }}" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Role</label>
                                                    <select name="role" class="form-select" required>
                                                        <option value="admin"      {{ $user->role == 'admin'      ? 'selected' : '' }}>Admin</option>
                                                        <option value="employee"   {{ $user->role == 'employee'   ? 'selected' : '' }}>Employee</option>
                                                        <option value="accountant" {{ $user->role == 'accountant' ? 'selected' : '' }}>Accountant</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Department</label>
                                                    <input type="text" name="department" class="form-control"
                                                        value="{{ $user->department }}">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Phone</label>
                                                    <input type="text" name="phone" class="form-control"
                                                        value="{{ $user->phone }}">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">New Password (optional)</label>
                                                    <input type="password" name="password" class="form-control"
                                                        placeholder="Leave blank to keep current">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-primary">Save Changes</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center py-4">No users found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection