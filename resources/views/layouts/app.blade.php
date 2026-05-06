<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} - Stock Requisition System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background-color: #f4f6f9; }
        .sidebar {
            min-height: 100vh;
            background: #2c3e50;
            color: white;
            width: 250px;
            position: fixed;
            top: 0; left: 0;
            overflow-y: auto;
            z-index: 100;
        }
        .sidebar .nav-link {
            color: #bdc3c7;
            padding: 10px 20px;
            border-radius: 5px;
            margin: 2px 10px;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            background: #3498db;
            color: white;
        }
        .sidebar-brand {
            padding: 20px;
            font-size: 1.2rem;
            font-weight: bold;
            border-bottom: 1px solid #34495e;
        }
        .main-content {
            margin-left: 250px;
            padding: 20px;
        }
        .topbar {
            background: white;
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .stat-card {
            border-radius: 10px;
            padding: 20px;
            color: white;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-brand">
            <i class="fas fa-boxes me-2"></i> StockSys
        </div>
        <nav class="mt-3">
            @if(auth()->user()->isAdmin())
                <a href="{{ route('admin.dashboard') }}" class="nav-link"><i class="fas fa-tachometer-alt me-2"></i> Dashboard</a>
                <a href="{{ route('admin.users') }}" class="nav-link"><i class="fas fa-users me-2"></i> Users</a>
                <a href="{{ route('admin.departments') }}" class="nav-link"><i class="fas fa-building me-2"></i> Departments</a>
                <a href="{{ route('admin.requisitions') }}" class="nav-link"><i class="fas fa-list me-2"></i> Requisitions</a>
            @elseif(auth()->user()->isAccountant())
                <a href="{{ route('accountant.dashboard') }}" class="nav-link"><i class="fas fa-tachometer-alt me-2"></i> Dashboard</a>
                <a href="{{ route('accountant.requisitions') }}" class="nav-link"><i class="fas fa-list me-2"></i> Requisitions</a>
            @else
                <a href="{{ route('employee.dashboard') }}" class="nav-link"><i class="fas fa-tachometer-alt me-2"></i> Dashboard</a>
                <a href="{{ route('employee.requisitions') }}" class="nav-link"><i class="fas fa-list me-2"></i> My Requests</a>
                <a href="{{ route('employee.requisitions.create') }}" class="nav-link"><i class="fas fa-plus me-2"></i> New Request</a>
            @endif
        </nav>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="topbar">
            <h5 class="mb-0">@yield('title', 'Dashboard')</h5>
            <div class="d-flex align-items-center gap-3">
    <span class="badge bg-primary">{{ ucfirst(auth()->user()->role) }}</span>
    <span>{{ auth()->user()->name }}</span>

    <!-- Notification Bell -->
    <div class="dropdown">
        <button class="btn btn-sm btn-light position-relative" data-bs-toggle="dropdown">
            <i class="fas fa-bell"></i>
            @if(auth()->user()->unreadNotifications()->count() > 0)
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                    {{ auth()->user()->unreadNotifications()->count() }}
                </span>
            @endif
        </button>
        <ul class="dropdown-menu dropdown-menu-end" style="width:320px; max-height:400px; overflow-y:auto;">
            <li class="dropdown-header d-flex justify-content-between align-items-center px-3 py-2">
                <strong>Notifications</strong>
                @if(auth()->user()->unreadNotifications()->count() > 0)
                    <a href="{{ route('notifications.readall') }}" class="text-sm text-primary" style="font-size:12px;">Mark all read</a>
                @endif
            </li>
            <li><hr class="dropdown-divider m-0"></li>
            @forelse(auth()->user()->notifications()->take(10)->get() as $notification)
            <li>
                <a class="dropdown-item py-2 {{ !$notification->is_read ? 'bg-light' : '' }}"
                    href="{{ route('notifications.read', $notification) }}">
                    <div class="d-flex gap-2">
                        <i class="fas fa-circle text-{{ $notification->type }} mt-1" style="font-size:8px;"></i>
                        <div>
                            <div style="font-size:13px; font-weight:{{ !$notification->is_read ? 'bold' : 'normal' }}">
                                {{ $notification->title }}
                            </div>
                            <div style="font-size:11px; color:#666;">{{ $notification->message }}</div>
                            <div style="font-size:10px; color:#999;">{{ $notification->created_at->diffForHumans() }}</div>
                        </div>
                    </div>
                </a>
            </li>
            @empty
            <li><div class="dropdown-item text-center text-muted py-3">No notifications</div></li>
            @endforelse
        </ul>
    </div>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="btn btn-sm btn-danger">
            <i class="fas fa-sign-out-alt"></i> Logout
        </button>
    </form>
</div>
            </div>
        </div>

        {{-- Success / Error Messages --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>