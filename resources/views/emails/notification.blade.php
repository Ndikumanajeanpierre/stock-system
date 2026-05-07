<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f4f6f9;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #2c3e50, #3498db);
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            color: white;
            margin: 0;
            font-size: 1.5rem;
        }
        .header p {
            color: #bdc3c7;
            margin: 5px 0 0;
            font-size: 0.9rem;
        }
        .body {
            padding: 30px;
        }
        .alert {
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 0.95rem;
        }
        .alert-info    { background: #d1ecf1; border-left: 4px solid #3498db; color: #0c5460; }
        .alert-success { background: #d4edda; border-left: 4px solid #27ae60; color: #155724; }
        .alert-danger  { background: #f8d7da; border-left: 4px solid #e74c3c; color: #721c24; }
        .alert-warning { background: #fff3cd; border-left: 4px solid #f39c12; color: #856404; }
        .message {
            color: #2c3e50;
            font-size: 1rem;
            line-height: 1.6;
            margin-bottom: 25px;
        }
        .btn {
            display: inline-block;
            padding: 12px 30px;
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.95rem;
        }
        .footer {
            background: #f8f9fa;
            padding: 20px 30px;
            text-align: center;
            color: #7f8c8d;
            font-size: 0.85rem;
            border-top: 1px solid #dee2e6;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📦 StockSys</h1>
            <p>Internal Stock Requisition System</p>
        </div>
        <div class="body">
            <div class="alert alert-{{ $type }}">
                <strong>{{ $title }}</strong>
            </div>
            <div class="message">
                {{ $messageBody }}
            </div>
            <a href="{{ url('/') }}" class="btn">Login to StockSys</a>
        </div>
        <div class="footer">
            <p>This is an automated message from StockSys. Please do not reply to this email.</p>
            <p>&copy; {{ date('Y') }} StockSys. All rights reserved.</p>
        </div>
    </div>
</body>
</html>