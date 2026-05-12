<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} - Stock Requisition System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: #f0f2f5;
            overflow-x: hidden;
        }
        .sidebar {
            height: 100vh;
            width: 260px;
            position: fixed;
            top: 0; left: 0;
            z-index: 1000;
            transition: transform 0.3s ease;
            background: linear-gradient(180deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }
        .sidebar.accountant {
            background: linear-gradient(180deg, #1a2f1a 0%, #1e3a1e 50%, #145214 100%);
        }
        .sidebar.employee {
            background: linear-gradient(180deg, #1a1a2e 0%, #2d1b69 50%, #11998e 100%);
        }
        .sidebar-brand {
            padding: 25px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            display: flex;
            align-items: center;
            gap: 12px;
            flex-shrink: 0;
        }
        .sidebar-brand .brand-icon {
            width: 42px; height: 42px;
            background: rgba(255,255,255,0.15);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            color: white;
        }
        .sidebar-brand .brand-text {
            color: white;
            font-weight: 700;
            font-size: 1.2rem;
            line-height: 1.2;
        }
        .sidebar-brand .brand-sub {
            color: rgba(255,255,255,0.5);
            font-size: 0.7rem;
            font-weight: 400;
        }
        .sidebar-section {
            padding: 15px 15px 5px;
            color: rgba(255,255,255,0.35);
            font-size: 0.7rem;
            font-weight: 600;
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }
        .sidebar nav {
            flex: 1;
            overflow-y: auto;
            padding-bottom: 10px;
        }
        .sidebar nav::-webkit-scrollbar { width: 4px; }
        .sidebar nav::-webkit-scrollbar-track { background: transparent; }
        .sidebar nav::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.2); border-radius: 4px; }
        .sidebar .nav-link {
            color: rgba(255,255,255,0.65);
            padding: 11px 15px;
            border-radius: 10px;
            margin: 2px 10px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.2s;
        }
        .sidebar .nav-link .nav-icon {
            width: 32px; height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255,255,255,0.08);
            font-size: 0.85rem;
            flex-shrink: 0;
            transition: all 0.2s;
        }
        .sidebar .nav-link:hover {
            color: white;
            background: rgba(255,255,255,0.1);
        }
        .sidebar .nav-link:hover .nav-icon { background: rgba(255,255,255,0.2); }
        .sidebar .nav-link.active {
            color: white;
            background: rgba(255,255,255,0.15);
        }
        .sidebar .nav-link.active .nav-icon { background: rgba(255,255,255,0.25); }
        .sidebar-user {
            flex-shrink: 0;
            padding: 15px;
            border-top: 1px solid rgba(255,255,255,0.1);
            background: rgba(0,0,0,0.2);
        }
        .sidebar-user .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .sidebar-user .avatar {
            width: 36px; height: 36px;
            border-radius: 50%;
            background: rgba(255,255,255,0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 0.875rem;
            flex-shrink: 0;
        }
        .sidebar-user .user-name {
            color: white;
            font-size: 0.825rem;
            font-weight: 600;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .sidebar-user .user-role {
            color: rgba(255,255,255,0.5);
            font-size: 0.7rem;
        }
        .main-content {
            margin-left: 260px;
            padding: 0;
            width: calc(100% - 260px);
            overflow-x: hidden;
            min-height: 100vh;
        }
        .topbar {
            background: white;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #e9ecef;
            position: sticky;
            top: 0;
            z-index: 98;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .topbar .page-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: #1a1a2e;
            margin: 0;
        }
        .topbar .page-subtitle {
            font-size: 0.75rem;
            color: #7f8c8d;
            margin: 0;
        }
        .topbar-actions { display: flex; align-items: center; gap: 12px; }
        .topbar .role-badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .role-admin { background: #fef3cd; color: #856404; }
        .role-accountant { background: #d4edda; color: #155724; }
        .role-employee { background: #cce5ff; color: #004085; }
        .content-area { padding: 25px 30px; overflow-x: auto; }
        .card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.06);
            width: 100%;
            margin-bottom: 20px;
        }
        .card-header {
            background: white;
            border-bottom: 1px solid #f0f0f0;
            border-radius: 16px 16px 0 0 !important;
            padding: 18px 20px;
        }
        .card-body { overflow-x: auto; }
        .stat-card {
            border-radius: 16px;
            padding: 22px;
            color: white;
            margin-bottom: 20px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
        }
        .stat-card::before {
            content: '';
            position: absolute;
            top: -20px; right: -20px;
            width: 100px; height: 100px;
            border-radius: 50%;
            background: rgba(255,255,255,0.1);
        }
        .stat-card::after {
            content: '';
            position: absolute;
            bottom: -30px; right: 30px;
            width: 80px; height: 80px;
            border-radius: 50%;
            background: rgba(255,255,255,0.07);
        }
        .stat-card .stat-icon {
            width: 50px; height: 50px;
            border-radius: 12px;
            background: rgba(255,255,255,0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            margin-bottom: 15px;
        }
        .stat-card .stat-number {
            font-size: 2rem;
            font-weight: 700;
            line-height: 1;
            margin-bottom: 5px;
        }
        .stat-card .stat-label {
            font-size: 0.8rem;
            opacity: 0.85;
            font-weight: 500;
        }
        .table { width: 100%; }
        .table thead th {
            background: #f8f9fa;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #6c757d;
            border: none;
            padding: 12px 16px;
        }
        .table tbody td {
            padding: 13px 16px;
            font-size: 0.875rem;
            vertical-align: middle;
            border-color: #f0f0f0;
        }
        .table tbody tr:hover { background: #f8f9ff; }
        .badge { font-weight: 500; padding: 5px 10px; border-radius: 6px; }
        .alert { border-radius: 10px; border: none; }
        .btn { border-radius: 8px; font-weight: 500; font-size: 0.875rem; }
        .btn-primary { background: linear-gradient(135deg, #667eea, #764ba2); border: none; }
        .btn-primary:hover { background: linear-gradient(135deg, #5a6fd8, #6a3d96); }
        .btn-success { background: linear-gradient(135deg, #11998e, #38ef7d); border: none; color: white; }
        .btn-danger { background: linear-gradient(135deg, #f093fb, #f5576c); border: none; }
        .btn-info { background: linear-gradient(135deg, #4facfe, #00f2fe); border: none; color: white; }
        .btn-warning { background: linear-gradient(135deg, #f7971e, #ffd200); border: none; color: white; }
        .notification-btn {
            position: relative;
            width: 38px; height: 38px;
            border-radius: 10px;
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #495057;
            cursor: pointer;
            transition: all 0.2s;
        }
        .notification-btn:hover { background: #e9ecef; }
        .form-control, .form-select {
            border-radius: 8px;
            border-color: #e9ecef;
            font-size: 0.875rem;
            padding: 10px 14px;
        }
        .form-control:focus, .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102,126,234,0.15);
        }
        .form-label { font-weight: 600; font-size: 0.825rem; color: #495057; margin-bottom: 6px; }

        /* ── Overlay (sidebar mobile) ── */
        .overlay {
            display: none;
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 999;
        }
        .overlay.open { display: block; }

        /* ════════════════════════════════════════
           NOTIFICATION PANEL (slide in from right)
        ════════════════════════════════════════ */
        .notif-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.35);
            z-index: 1049;
            transition: opacity 0.3s;
        }
        .notif-overlay.open { display: block; }

        .notif-panel {
            position: fixed;
            top: 0;
            right: -400px;          /* hidden off-screen */
            width: 380px;
            height: 100vh;
            background: #fff;
            z-index: 1050;
            display: flex;
            flex-direction: column;
            box-shadow: -4px 0 30px rgba(0,0,0,0.15);
            transition: right 0.35s cubic-bezier(0.4,0,0.2,1);
            border-radius: 16px 0 0 16px;
        }
        .notif-panel.open { right: 0; }

        .notif-panel-header {
            padding: 20px;
            border-bottom: 1px solid #f0f0f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-shrink: 0;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 16px 0 0 0;
        }
        .notif-panel-header h6 {
            color: white;
            font-weight: 700;
            font-size: 1rem;
            margin: 0;
        }
        .notif-panel-header .notif-actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .notif-panel-header .mark-all-btn {
            font-size: 0.72rem;
            color: rgba(255,255,255,0.85);
            background: rgba(255,255,255,0.2);
            border: none;
            border-radius: 6px;
            padding: 4px 10px;
            cursor: pointer;
            text-decoration: none;
            transition: background 0.2s;
        }
        .notif-panel-header .mark-all-btn:hover {
            background: rgba(255,255,255,0.35);
            color: white;
        }
        .notif-close-btn {
            width: 30px; height: 30px;
            border-radius: 8px;
            background: rgba(255,255,255,0.2);
            border: none;
            color: white;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.85rem;
            transition: background 0.2s;
        }
        .notif-close-btn:hover { background: rgba(255,255,255,0.35); }

        .notif-panel-body {
            flex: 1;
            overflow-y: auto;
            padding: 10px 0;
        }
        .notif-panel-body::-webkit-scrollbar { width: 4px; }
        .notif-panel-body::-webkit-scrollbar-track { background: transparent; }
        .notif-panel-body::-webkit-scrollbar-thumb { background: #e0e0e0; border-radius: 4px; }

        .notif-item {
            display: flex;
            gap: 12px;
            padding: 14px 20px;
            border-bottom: 1px solid #f8f8f8;
            text-decoration: none;
            color: inherit;
            transition: background 0.15s;
        }
        .notif-item:hover { background: #f8f9ff; color: inherit; }
        .notif-item.unread { background: #f0f4ff; }
        .notif-item.unread:hover { background: #e8eeff; }

        .notif-dot {
            width: 10px; height: 10px;
            border-radius: 50%;
            flex-shrink: 0;
            margin-top: 5px;
        }
        .notif-item-content { flex: 1; min-width: 0; }
        .notif-item-title {
            font-size: 0.82rem;
            font-weight: 600;
            color: #1a1a2e;
            margin-bottom: 3px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .notif-item.read .notif-item-title { font-weight: 400; color: #495057; }
        .notif-item-msg {
            font-size: 0.77rem;
            color: #6c757d;
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .notif-item-time {
            font-size: 0.7rem;
            color: #adb5bd;
            margin-top: 4px;
        }

        .notif-empty {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 60px 20px;
            color: #adb5bd;
            gap: 12px;
        }
        .notif-empty i { font-size: 2.5rem; opacity: 0.4; }
        .notif-empty p { font-size: 0.85rem; margin: 0; }

        .notif-panel-footer {
            flex-shrink: 0;
            padding: 14px 20px;
            border-top: 1px solid #f0f0f0;
            text-align: center;
            font-size: 0.78rem;
            color: #667eea;
            font-weight: 600;
            background: #fafafa;
            border-radius: 0 0 0 16px;
        }

        /* ── Responsive ── */
        @media (max-width: 991px) {
            .sidebar { transform: translateX(-260px); }
            .sidebar.open { transform: translateX(0); }
            .main-content { margin-left: 0 !important; width: 100% !important; }
            .topbar { padding: 10px 15px; }
            .content-area { padding: 15px; }
            .stat-card { margin-bottom: 15px; }
        }
        @media (max-width: 576px) {
            .topbar .page-subtitle { display: none; }
            .topbar .role-badge { display: none; }
            .notif-panel { width: 100%; border-radius: 0; }
        }
    </style>
</head>
<body>

    <!-- Sidebar mobile overlay -->
    <div class="overlay" id="overlay"></div>

    <!-- ── Notification Panel Overlay ── -->
    <div class="notif-overlay" id="notifOverlay"></div>

    <!-- ── Notification Slide Panel ── -->
    <div class="notif-panel" id="notifPanel">
        <div class="notif-panel-header">
            <h6><i class="fas fa-bell me-2"></i>Notifications</h6>
            <div class="notif-actions">
                @if(auth()->user()->unreadNotifications()->count() > 0)
                    <a href="{{ route('notifications.readall') }}" class="mark-all-btn">
                        <i class="fas fa-check-double me-1"></i> Mark all read
                    </a>
                @endif
                <button class="notif-close-btn" id="notifCloseBtn">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>

        <div class="notif-panel-body">
            @forelse(auth()->user()->notifications()->take(15)->get() as $notification)
                <a class="notif-item {{ !$notification->is_read ? 'unread' : 'read' }}"
                   href="{{ route('notifications.read', $notification) }}">
                    <div class="notif-dot" style="background:{{ $notification->type == 'success' ? '#27ae60' : ($notification->type == 'danger' ? '#e74c3c' : '#3498db') }};"></div>
                    <div class="notif-item-content">
                        <div class="notif-item-title">{{ $notification->title }}</div>
                        <div class="notif-item-msg">{{ $notification->message }}</div>
                        <div class="notif-item-time">
                            <i class="fas fa-clock me-1"></i>{{ $notification->created_at->diffForHumans() }}
                        </div>
                    </div>
                </a>
            @empty
                <div class="notif-empty">
                    <i class="fas fa-bell-slash"></i>
                    <p>No notifications yet</p>
                </div>
            @endforelse
        </div>

        <div class="notif-panel-footer">
            <i class="fas fa-inbox me-1"></i>
            {{ auth()->user()->notifications()->count() }} total notification(s)
        </div>
    </div>

    <!-- Sidebar -->
    <div class="sidebar {{ auth()->user()->isAccountant() ? 'accountant' : (auth()->user()->isEmployee() ? 'employee' : '') }}">
        <div class="sidebar-brand">
            <div class="brand-icon"><i class="fas fa-boxes"></i></div>
            <div>
                <div class="brand-text">StockSys</div>
                <div class="brand-sub">Management System</div>
            </div>
        </div>

        <nav class="mt-2">
            @if(auth()->user()->isAdmin())
                <div class="sidebar-section">Main</div>
                <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <div class="nav-icon"><i class="fas fa-chart-pie"></i></div> Dashboard
                </a>
                <div class="sidebar-section">Management</div>
                <a href="{{ route('admin.users') }}" class="nav-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                    <div class="nav-icon"><i class="fas fa-users"></i></div> Users
                </a>
                <a href="{{ route('admin.departments') }}" class="nav-link {{ request()->routeIs('admin.departments*') ? 'active' : '' }}">
                    <div class="nav-icon"><i class="fas fa-building"></i></div> Departments
                </a>
                <a href="{{ route('admin.stock-items') }}" class="nav-link {{ request()->routeIs('admin.stock-items*') ? 'active' : '' }}">
                    <div class="nav-icon"><i class="fas fa-boxes"></i></div> Stock Items
                </a>
                <div class="sidebar-section">Requests</div>
                <a href="{{ route('admin.requisitions') }}" class="nav-link {{ request()->routeIs('admin.requisitions*') ? 'active' : '' }}">
                    <div class="nav-icon"><i class="fas fa-clipboard-list"></i></div> Requisitions
                </a>
                <a href="{{ route('admin.reports') }}" class="nav-link {{ request()->routeIs('admin.reports*') ? 'active' : '' }}">
                    <div class="nav-icon"><i class="fas fa-chart-bar"></i></div> Reports
                </a>
                <a href="{{ route('admin.stock-report') }}" class="nav-link {{ request()->routeIs('admin.stock-report*') ? 'active' : '' }}">
                    <div class="nav-icon"><i class="fas fa-warehouse"></i></div> Stock Report
                </a>
                <a href="{{ route('admin.activity-log') }}" class="nav-link {{ request()->routeIs('admin.activity-log*') ? 'active' : '' }}">
                    <div class="nav-icon"><i class="fas fa-history"></i></div> Activity Log
                </a>
                <a href="{{ route('admin.settings') }}" class="nav-link {{ request()->routeIs('admin.settings*') ? 'active' : '' }}">
                    <div class="nav-icon"><i class="fas fa-cog"></i></div> Settings
                </a>

            @elseif(auth()->user()->isAccountant())
                <div class="sidebar-section">Main</div>
                <a href="{{ route('accountant.dashboard') }}" class="nav-link {{ request()->routeIs('accountant.dashboard') ? 'active' : '' }}">
                    <div class="nav-icon"><i class="fas fa-chart-pie"></i></div> Dashboard
                </a>
                <div class="sidebar-section">Payments</div>
                <a href="{{ route('accountant.requisitions') }}" class="nav-link {{ request()->routeIs('accountant.requisitions*') ? 'active' : '' }}">
                    <div class="nav-icon"><i class="fas fa-clipboard-list"></i></div> Requisitions
                </a>
                <a href="{{ route('accountant.stock-report') }}" class="nav-link {{ request()->routeIs('accountant.stock-report*') ? 'active' : '' }}">
                    <div class="nav-icon"><i class="fas fa-warehouse"></i></div> Stock Report
                </a>

            @else
                <div class="sidebar-section">Main</div>
                <a href="{{ route('employee.dashboard') }}" class="nav-link {{ request()->routeIs('employee.dashboard') ? 'active' : '' }}">
                    <div class="nav-icon"><i class="fas fa-chart-pie"></i></div> Dashboard
                </a>
                <div class="sidebar-section">Requests</div>
                <a href="{{ route('employee.requisitions') }}" class="nav-link {{ request()->routeIs('employee.requisitions') ? 'active' : '' }}">
                    <div class="nav-icon"><i class="fas fa-list"></i></div> My Requests
                </a>
                <a href="{{ route('employee.requisitions.create') }}" class="nav-link {{ request()->routeIs('employee.requisitions.create') ? 'active' : '' }}">
                    <div class="nav-icon"><i class="fas fa-plus"></i></div> New Request
                </a>
                <div class="sidebar-section">Stock</div>
                <a href="{{ route('employee.stock-items') }}" class="nav-link {{ request()->routeIs('employee.stock-items') ? 'active' : '' }}">
                    <div class="nav-icon"><i class="fas fa-boxes"></i></div> View Stock
                </a>
            @endif
        </nav>

        <div class="sidebar-user">
            <a href="{{ route('profile.settings') }}" style="text-decoration:none;">
                <div class="user-info">
                    @if(auth()->user()->profile_photo)
                        <img src="{{ asset('storage/'.auth()->user()->profile_photo) }}"
                            style="width:36px;height:36px;border-radius:50%;object-fit:cover;flex-shrink:0;">
                    @else
                        <div class="avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                    @endif
                    <div style="overflow:hidden;">
                        <div class="user-name">{{ auth()->user()->name }}</div>
                        <div class="user-role">{{ ucfirst(auth()->user()->role) }} · <span style="color:#3498db;font-size:0.65rem;">Edit Profile</span></div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="topbar">
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-sm btn-light" id="menuToggle"
                    style="border:1px solid #e9ecef; display:none;">
                    <i class="fas fa-bars"></i>
                </button>
                <div>
                    <h5 class="page-title">@yield('title', 'Dashboard')</h5>
                    <p class="page-subtitle">{{ now()->format('l, d F Y') }}</p>
                </div>
            </div>
            <div class="topbar-actions">
                <span class="role-badge role-{{ auth()->user()->role }}">
                    {{ ucfirst(auth()->user()->role) }}
                </span>

                <!-- Bell button — opens slide panel -->
                <div class="notification-btn" id="notifToggleBtn" title="Notifications">
                    <i class="fas fa-bell" style="font-size:0.9rem;"></i>
                    @if(auth()->user()->unreadNotifications()->count() > 0)
                        <span style="position:absolute;top:-4px;right:-4px;width:18px;height:18px;border-radius:50%;background:#e74c3c;color:white;font-size:0.6rem;font-weight:700;display:flex;align-items:center;justify-content:center;border:2px solid white;">
                            {{ auth()->user()->unreadNotifications()->count() > 9 ? '9+' : auth()->user()->unreadNotifications()->count() }}
                        </span>
                    @endif
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-danger px-3">
                        <i class="fas fa-sign-out-alt me-1"></i> Logout
                    </button>
                </form>
            </div>
        </div>

        <div class="content-area">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // ── Sidebar mobile toggle ──
        function checkScreen() {
            const btn = document.getElementById('menuToggle');
            if (window.innerWidth <= 991) {
                btn.style.display = 'block';
            } else {
                btn.style.display = 'none';
                document.querySelector('.sidebar').classList.remove('open');
                document.getElementById('overlay').classList.remove('open');
            }
        }
        checkScreen();
        window.addEventListener('resize', checkScreen);

        document.getElementById('menuToggle').addEventListener('click', function () {
            document.querySelector('.sidebar').classList.toggle('open');
            document.getElementById('overlay').classList.toggle('open');
        });

        document.getElementById('overlay').addEventListener('click', function () {
            document.querySelector('.sidebar').classList.remove('open');
            document.getElementById('overlay').classList.remove('open');
        });

        // ── Notification slide panel ──
        const notifToggleBtn = document.getElementById('notifToggleBtn');
        const notifPanel     = document.getElementById('notifPanel');
        const notifOverlay   = document.getElementById('notifOverlay');
        const notifCloseBtn  = document.getElementById('notifCloseBtn');

        function openNotifPanel() {
            notifPanel.classList.add('open');
            notifOverlay.classList.add('open');
            document.body.style.overflow = 'hidden'; // prevent background scroll
        }

        function closeNotifPanel() {
            notifPanel.classList.remove('open');
            notifOverlay.classList.remove('open');
            document.body.style.overflow = '';
        }

        notifToggleBtn.addEventListener('click', function () {
            notifPanel.classList.contains('open') ? closeNotifPanel() : openNotifPanel();
        });

        notifCloseBtn.addEventListener('click', closeNotifPanel);
        notifOverlay.addEventListener('click', closeNotifPanel);

        // Close with Escape key
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') closeNotifPanel();
        });
    </script>
</body>
</html>