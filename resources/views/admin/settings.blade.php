@extends('layouts.app')

@section('title', 'System Settings')

@section('content')
<div class="row g-4">
    <div class="col-md-8">
        <form method="POST" action="{{ route('admin.settings.update') }}">
            @csrf

            <!-- Company Info -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0 fw-bold"><i class="fas fa-building me-2"></i>Company Information</h6>
                    <small class="text-muted">Basic company details shown across the system</small>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Company Name</label>
                            <input type="text" name="company_name" class="form-control"
                                value="{{ $settings['company_name'] ?? '' }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Company Email</label>
                            <input type="email" name="company_email" class="form-control"
                                value="{{ $settings['company_email'] ?? '' }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Company Phone</label>
                            <input type="text" name="company_phone" class="form-control"
                                value="{{ $settings['company_phone'] ?? '' }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Company Address</label>
                            <input type="text" name="company_address" class="form-control"
                                value="{{ $settings['company_address'] ?? '' }}">
                        </div>
                    </div>
                </div>
            </div>


            <button type="submit" class="btn btn-primary px-5">
                <i class="fas fa-save me-2"></i> Save Settings
            </button>
        </form>
    </div>

    <!-- Settings Info -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0 fw-bold"><i class="fas fa-info-circle me-2"></i>Current Settings</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <small class="text-muted d-block">Company Name</small>
                    <strong>{{ $settings['company_name'] ?? 'N/A' }}</strong>
                </div>
                <div class="mb-3">
                    <small class="text-muted d-block">Company Email</small>
                    <strong>{{ $settings['company_email'] ?? 'N/A' }}</strong>
                </div>
                <div class="mb-3">
                    <small class="text-muted d-block">Company Phone</small>
                    <strong>{{ $settings['company_phone'] ?? 'N/A' }}</strong>
                </div>
                <div class="mb-3">
                    <small class="text-muted d-block">Company Address</small>
                    <strong>{{ $settings['company_address'] ?? 'N/A' }}</strong>
                </div>
            </div>
        </div>

        <!-- System Info -->
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="mb-0 fw-bold"><i class="fas fa-server me-2"></i>System Info</h6>
            </div>
            <div class="card-body">
                <div class="mb-2">
                    <small class="text-muted d-block">Laravel Version</small>
                    <strong>{{ app()->version() }}</strong>
                </div>
                <div class="mb-2">
                    <small class="text-muted d-block">PHP Version</small>
                    <strong>{{ PHP_VERSION }}</strong>
                </div>
                <div class="mb-2">
                    <small class="text-muted d-block">Environment</small>
                    <strong>{{ app()->environment() }}</strong>
                </div>
                <div class="mb-2">
                    <small class="text-muted d-block">Server Time</small>
                    <strong>{{ now()->format('d M Y H:i') }}</strong>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection