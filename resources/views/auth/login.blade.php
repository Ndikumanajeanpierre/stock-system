<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - StockSys</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            min-height: 100vh;
            display: flex;
            font-family: 'Segoe UI', sans-serif;
            background: #f0f4f8;
        }
        .left-panel {
            width: 55%;
            background: linear-gradient(135deg, #1a2f4e 0%, #2c3e50 50%, #1a6b8a 100%);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 60px;
            position: relative;
            overflow: hidden;
        }
        .left-panel::before {
            content: '';
            position: absolute;
            width: 400px; height: 400px;
            background: rgba(255,255,255,0.03);
            border-radius: 50%;
            top: -100px; left: -100px;
        }
        .left-panel::after {
            content: '';
            position: absolute;
            width: 300px; height: 300px;
            background: rgba(255,255,255,0.03);
            border-radius: 50%;
            bottom: -80px; right: -80px;
        }
        .left-panel .logo {
            font-size: 3rem;
            color: #3498db;
            margin-bottom: 20px;
        }
        .left-panel h1 {
            color: white;
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 15px;
            text-align: center;
        }
        .left-panel p {
            color: #bdc3c7;
            font-size: 1rem;
            text-align: center;
            line-height: 1.7;
            max-width: 400px;
        }
        .features {
            margin-top: 40px;
            width: 100%;
            max-width: 400px;
        }
        .feature-item {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
            color: #ecf0f1;
        }
        .feature-item .icon {
            width: 40px; height: 40px;
            background: rgba(52,152,219,0.2);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #3498db;
            flex-shrink: 0;
        }
        .feature-item span { font-size: 0.9rem; color: #bdc3c7; }
        .right-panel {
            width: 45%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 60px 50px;
            background: white;
        }
        .login-box { width: 100%; max-width: 400px; }
        .login-box h2 {
            font-size: 1.8rem;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 8px;
        }
        .login-box .subtitle {
            color: #7f8c8d;
            margin-bottom: 35px;
            font-size: 0.95rem;
        }
        .form-label {
            font-weight: 600;
            color: #2c3e50;
            font-size: 0.875rem;
            margin-bottom: 6px;
        }
        .input-group-text {
            background: #f8f9fa;
            border-right: none;
            color: #7f8c8d;
        }
        .form-control {
            border-left: none;
            padding: 12px 15px;
            font-size: 0.95rem;
            border-color: #dee2e6;
        }
        .form-control:focus {
            box-shadow: none;
            border-color: #3498db;
        }
        .form-control:focus + .input-group-text,
        .input-group:focus-within .input-group-text {
            border-color: #3498db;
        }
        .btn-login {
            background: linear-gradient(135deg, #3498db, #2980b9);
            border: none;
            padding: 13px;
            font-size: 1rem;
            font-weight: 600;
            border-radius: 8px;
            color: white;
            width: 100%;
            margin-top: 10px;
            transition: all 0.3s;
        }
        .btn-login:hover {
            background: linear-gradient(135deg, #2980b9, #1a6b8a);
            transform: translateY(-1px);
            box-shadow: 0 5px 15px rgba(52,152,219,0.4);
            color: white;
        }
        .divider {
            text-align: center;
            margin: 25px 0;
            color: #bdc3c7;
            font-size: 0.85rem;
        }
        .demo-accounts { margin-top: 25px; }
        .demo-accounts h6 {
            font-size: 0.8rem;
            color: #7f8c8d;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 12px;
        }
        .demo-badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.78rem;
            margin: 3px;
            cursor: pointer;
            transition: all 0.2s;
            border: 1px solid;
        }
        .demo-badge:hover { transform: translateY(-1px); }
        .badge-admin { background: #fef9f0; border-color: #f39c12; color: #e67e22; }
        .badge-accountant { background: #f0fdf4; border-color: #27ae60; color: #27ae60; }
        .badge-employee { background: #eff6ff; border-color: #3498db; color: #2980b9; }
        .alert-danger { border-radius: 8px; font-size: 0.875rem; }
    </style>
</head>
<body>
    <!-- Left Panel -->
    <div class="left-panel">
        <div class="logo"><i class="fas fa-boxes"></i></div>
        <h1>StockSys</h1>
        <p>A complete internal stock requisition management system for your organization.</p>
        <div class="features">
            <div class="feature-item">
                <div class="icon"><i class="fas fa-clipboard-list"></i></div>
                <span>Submit and track stock requests easily</span>
            </div>
            <div class="feature-item">
                <div class="icon"><i class="fas fa-check-circle"></i></div>
                <span>Approve or reject requests in one click</span>
            </div>
            <div class="feature-item">
                <div class="icon"><i class="fas fa-receipt"></i></div>
                <span>Upload payment receipts and track payments</span>
            </div>
            <div class="feature-item">
                <div class="icon"><i class="fas fa-chart-bar"></i></div>
                <span>Generate reports and monitor activity</span>
            </div>
        </div>
    </div>

    <!-- Right Panel -->
    <div class="right-panel">
        <div class="login-box">
            <h2>Welcome back 👋</h2>
            <p class="subtitle">Sign in to your account to continue</p>

            @if(session('status'))
                <div class="alert alert-success mb-3">{{ session('status') }}</div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger mb-3">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Email Address</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                        <input type="email" name="email" class="form-control"
                            value="{{ old('email') }}"
                            placeholder="Enter your email" required autofocus>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <input type="password" name="password" class="form-control"
                            placeholder="Enter your password" required>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-3">
    <div class="form-check">
        <input type="checkbox" name="remember" class="form-check-input" id="remember">
        <label class="form-check-label text-muted" for="remember" style="font-size:0.875rem;">
            Remember me
        </label>
    </div>
    <a href="{{ route('password.request') }}" style="font-size:0.875rem; color:#3498db; text-decoration:none;">
        <i class="fas fa-key me-1"></i> Forgot password?
    </a>
</div>

                <button type="submit" class="btn btn-login">
                    <i class="fas fa-sign-in-alt me-2"></i> Sign In
                </button>
            </form>

            <!-- Demo Accounts -->
            <div class="demo-accounts">
                <h6>Quick Login (Demo)</h6>
                <span class="demo-badge badge-admin" onclick="fillLogin('admin@company.com')">
                    <i class="fas fa-user-shield me-1"></i> Admin
                </span>
                <span class="demo-badge badge-accountant" onclick="fillLogin('accountant@company.com')">
                    <i class="fas fa-calculator me-1"></i> Accountant
                </span>
                <span class="demo-badge badge-employee" onclick="fillLogin('employee@company.com')">
                    <i class="fas fa-user me-1"></i> Employee
                </span>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function fillLogin(email) {
            document.querySelector('input[name="email"]').value = email;
            document.querySelector('input[name="password"]').value = 'password';
        }
    </script>
</body>
</html>