@extends('layouts.app')

@section('title', 'Profile Settings')

@section('content')
@php $user = \App\Models\User::find(auth()->id()); @endphp
<div class="row g-4">

    <!-- Profile Photo Card -->
    <div class="col-md-4">
        <div class="card text-center p-4">
            <div class="mb-3">
                @if($user->profile_photo)
                    <img src="{{ asset('storage/'.$user->profile_photo) }}"
                        style="width:120px;height:120px;border-radius:50%;object-fit:cover;border:4px solid #667eea;">
                @else
                    <div style="width:120px;height:120px;border-radius:50%;background:linear-gradient(135deg,#667eea,#764ba2);display:flex;align-items:center;justify-content:center;margin:0 auto;font-size:3rem;color:white;font-weight:700;">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                @endif
            </div>
            <h5 class="fw-bold mb-1">{{ $user->name }}</h5>
            <p class="text-muted mb-1">{{ $user->email }}</p>
            <span class="badge bg-primary mb-3">{{ ucfirst($user->role) }}</span>

            <!-- Upload Photo -->
            <form method="POST" action="{{ route('profile.update-photo') }}" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <input type="file" name="profile_photo" class="form-control form-control-sm"
                        accept=".jpg,.jpeg,.png" required>
                    <small class="text-muted">JPG, PNG max 2MB</small>
                </div>
                <button type="submit" class="btn btn-primary btn-sm w-100 mb-2">
                    <i class="fas fa-upload me-1"></i> Upload Photo
                </button>
            </form>

            <!-- Delete Photo -->
            @if($user->profile_photo)
            <form method="POST" action="{{ route('profile.delete-photo') }}">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm w-100"
                    onclick="return confirm('Remove profile photo?')">
                    <i class="fas fa-trash me-1"></i> Remove Photo
                </button>
            </form>
            @endif

            <!-- Profile Info -->
            <div class="mt-4 text-start">
                <div class="mb-2" style="font-size:0.8rem;">
                    <i class="fas fa-building me-2 text-muted"></i>
                    {{ $user->department ?? 'No department' }}
                </div>
                <div class="mb-2" style="font-size:0.8rem;">
                    <i class="fas fa-phone me-2 text-muted"></i>
                    {{ $user->phone ?? 'No phone' }}
                </div>
                <div style="font-size:0.8rem;">
                    <i class="fas fa-calendar me-2 text-muted"></i>
                    Joined {{ $user->created_at->format('d M Y') }}
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <!-- Update Info -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0 fw-bold"><i class="fas fa-user-edit me-2"></i>Update Profile Information</h6>
                <small class="text-muted">Update your name, email and contact info</small>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('profile.update-info') }}">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="name" class="form-control"
                                value="{{ old('name', $user->name) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Username</label>
                            <input type="text" name="username" class="form-control"
                                value="{{ old('username', $user->username) }}"
                                placeholder="Optional username">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email Address</label>
                            <input type="email" name="email" class="form-control"
                                value="{{ old('email', $user->email) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone Number</label>
                            <input type="text" name="phone" class="form-control"
                                value="{{ old('phone', $user->phone) }}"
                                placeholder="e.g. +250 788 000 000">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Department</label>
                            <input type="text" name="department" class="form-control"
                                value="{{ old('department', $user->department) }}"
                                placeholder="Your department">
                        </div>
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i> Save Changes
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Update Password -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0 fw-bold"><i class="fas fa-lock me-2"></i>Change Password</h6>
                <small class="text-muted">Make sure to use a strong password</small>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('profile.update-password') }}">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label">Current Password</label>
                            <input type="password" name="current_password" class="form-control"
                                placeholder="Enter current password" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">New Password</label>
                            <input type="password" name="password" class="form-control"
                                placeholder="Enter new password" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Confirm New Password</label>
                            <input type="password" name="password_confirmation" class="form-control"
                                placeholder="Confirm new password" required>
                        </div>
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-key me-2"></i> Update Password
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection