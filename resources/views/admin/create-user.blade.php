@extends('layouts.app')

@section('title', 'Create User')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Create New User</h5>
        <a href="{{ route('admin.users') }}" class="btn btn-sm btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.users.store') }}" id="createUserForm">
            @csrf
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name') }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Email</label>
                    <input
                        type="email"
                        name="email"
                        id="emailInput"
                        class="form-control @error('email') is-invalid @enderror"
                        value="{{ old('email') }}"
                        pattern="[a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{3,}"
                        placeholder="example@gmail.com"
                        required
                    >
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div id="emailError" class="invalid-feedback" style="display:none;">
                        Please enter a valid email address (e.g. name@example.com) — must include full extension like .com, .net, .org
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Role</label>
                    <select name="role" class="form-select @error('role') is-invalid @enderror" required>
                        <option value="">Select Role</option>
                        <option value="admin"      {{ old('role') == 'admin'      ? 'selected' : '' }}>Admin</option>
                        <option value="employee"   {{ old('role') == 'employee'   ? 'selected' : '' }}>Employee</option>
                        <option value="accountant" {{ old('role') == 'accountant' ? 'selected' : '' }}>Accountant</option>
                    </select>
                    @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Department</label>
                    <input type="text" name="department" class="form-control" value="{{ old('department') }}">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
                </div>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Create User
            </button>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const emailInput = document.getElementById('emailInput');
    const emailError = document.getElementById('emailError');
    const form       = document.getElementById('createUserForm');

    // Requires minimum 3 chars after dot — blocks .co, .c, .gmai etc.
    function isValidEmail(value) {
        return /^[a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{3,}$/.test(value.trim());
    }

    function showError() {
        emailInput.classList.add('is-invalid');
        emailError.style.display = 'block';
    }

    function clearError() {
        emailInput.classList.remove('is-invalid');
        emailError.style.display = 'none';
    }

    // Validate when user leaves the field
    emailInput.addEventListener('blur', function () {
        if (this.value && !isValidEmail(this.value)) {
            showError();
        } else {
            clearError();
        }
    });

    // Clear error live as user fixes the email
    emailInput.addEventListener('input', function () {
        if (isValidEmail(this.value)) {
            clearError();
        }
    });

    // Final gate — block submit if email is still invalid
    form.addEventListener('submit', function (e) {
        if (!isValidEmail(emailInput.value)) {
            e.preventDefault();
            showError();
            emailInput.focus();
        }
    });
});
</script>

@endsection