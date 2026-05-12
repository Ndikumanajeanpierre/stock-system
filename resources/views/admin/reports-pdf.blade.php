<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Requisition Report</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 12px; color: #1E293B; background: #fff; }

        /* ── HEADER ── */
        .header {
            text-align: center;
            padding: 18px 0 14px;
            border-bottom: 3px solid #6366F1;
            margin-bottom: 22px;
        }
        .header h1 { color: #6366F1; font-size: 22px; font-weight: 800; letter-spacing: 0.5px; }
        .header p  { margin-top: 4px; color: #64748B; font-size: 11px; }

        /* ── STAT CARDS ── */
        .cards-row {
            display: table;
            width: 100%;
            border-collapse: separate;
            border-spacing: 10px 0;
            margin-bottom: 22px;
        }
        .card {
            display: table-cell;
            width: 25%;
            padding: 14px 12px 12px;
            border-radius: 10px;
            text-align: center;
            color: #fff;
            vertical-align: middle;
        }
        .card .card-value {
            font-size: 26px;
            font-weight: 800;
            line-height: 1.1;
            display: block;
        }
        .card .card-label {
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 0.6px;
            text-transform: uppercase;
            opacity: 0.9;
            margin-top: 5px;
            display: block;
        }
        .card-icon {
            font-size: 20px;
            margin-bottom: 4px;
            display: block;
            opacity: 0.85;
        }

        .card-blue   { background: linear-gradient(135deg, #6366F1 0%, #818CF8 100%); }
        .card-orange { background: linear-gradient(135deg, #F59E0B 0%, #FCD34D 100%); }
        .card-green  { background: linear-gradient(135deg, #10B981 0%, #34D399 100%); }
        .card-purple { background: linear-gradient(135deg, #8B5CF6 0%, #A78BFA 100%); }

        /* ── LOW STOCK ALERT ── */
        .alert-box {
            background: #FFFBEB;
            border: 1.5px solid #F59E0B;
            border-radius: 8px;
            padding: 10px 14px;
            margin-bottom: 18px;
            color: #92400E;
            font-size: 11.5px;
            font-weight: 700;
        }
        .alert-box span { margin-right: 6px; }

        /* ── SECTION TITLE ── */
        .section-title {
            font-size: 13px;
            font-weight: 700;
            color: #1E293B;
            margin-bottom: 10px;
            padding-left: 2px;
            border-left: 4px solid #6366F1;
            padding-left: 8px;
        }
        .section-sub {
            font-size: 10px;
            color: #64748B;
            margin-bottom: 10px;
            padding-left: 12px;
        }

        /* ── TABLE ── */
        table.main-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 4px;
        }
        table.main-table thead tr {
            background: linear-gradient(90deg, #6366F1 0%, #818CF8 100%);
        }
        table.main-table th {
            color: #fff;
            padding: 9px 8px;
            text-align: left;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.4px;
            text-transform: uppercase;
        }
        table.main-table td {
            padding: 8px 8px;
            border-bottom: 1px solid #E2E8F0;
            font-size: 11px;
            color: #334155;
            vertical-align: middle;
        }
        table.main-table tr:nth-child(even) td { background: #F8FAFC; }
        table.main-table tr:hover td { background: #EEF2FF; }

        /* reference bold */
        .ref { font-weight: 700; color: #4338CA; }

        /* ── BADGES ── */
        .badge {
            display: inline-block;
            padding: 3px 9px;
            border-radius: 20px;
            font-size: 9.5px;
            font-weight: 700;
            letter-spacing: 0.3px;
            text-transform: capitalize;
        }
        .badge-success { background: #D1FAE5; color: #065F46; }
        .badge-warning { background: #FEF3C7; color: #92400E; }
        .badge-danger  { background: #FEE2E2; color: #991B1B; }
        .badge-info    { background: #DBEAFE; color: #1E40AF; }
        .badge-primary { background: #EDE9FE; color: #5B21B6; }
        .badge-dark    { background: #F1F5F9; color: #334155; }

        /* ── PRIORITY DOTS ── */
        .priority { display: inline-flex; align-items: center; gap: 4px; }
        .dot { width: 7px; height: 7px; border-radius: 50%; display: inline-block; }
        .dot-urgent { background: #EF4444; }
        .dot-high   { background: #F59E0B; }
        .dot-medium { background: #3B82F6; }
        .dot-low    { background: #10B981; }

        /* ── AMOUNT ── */
        .amount { font-weight: 700; color: #10B981; }

        /* ── FOOTER ── */
        .footer {
            margin-top: 24px;
            text-align: center;
            color: #94A3B8;
            font-size: 10px;
            border-top: 1px solid #E2E8F0;
            padding-top: 10px;
        }
    </style>
</head>
<body>

    {{-- ── HEADER ── --}}
    <div class="header">
        <h1>StockSys &mdash; Requisition Report</h1>
        <p>Generated on {{ now()->format('d M Y H:i') }}</p>
    </div>

    {{-- ── STAT CARDS ── --}}
    <table class="cards-row" style="border-spacing:10px 0;">
        <tr>
            <td class="card card-blue">
                <span class="card-icon">&#128203;</span>
                <span class="card-value">{{ $totalRequisitions }}</span>
                <span class="card-label">Total Requisitions</span>
            </td>
            <td class="card card-orange">
                <span class="card-icon">&#9203;</span>
                <span class="card-value">{{ $totalPending }}</span>
                <span class="card-label">Pending</span>
            </td>
            <td class="card card-green">
                <span class="card-icon">&#10003;</span>
                <span class="card-value">{{ $totalPaid }}</span>
                <span class="card-label">Paid / Completed</span>
            </td>
            <td class="card card-purple">
                <span class="card-icon">&#128181;</span>
                <span class="card-value">RWF {{ number_format($totalAmount, 2) }}</span>
                <span class="card-label">Total Payments</span>
            </td>
        </tr>
    </table>

    {{-- ── SECTION TITLE ── --}}
    <div class="section-title">All Requisitions</div>
    <div class="section-sub">Complete requisition list with status and payment tracking</div>

    {{-- ── MAIN TABLE ── --}}
    <table class="main-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Reference</th>
                <th>Employee</th>
                <th>Item</th>
                <th>Department</th>
                <th>Priority</th>
                <th>Status</th>
                <th>Amount</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse($requisitions as $req)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td><span class="ref">{{ $req->reference_number }}</span></td>
                <td>{{ $req->user->name }}</td>
                <td>{{ $req->item_name }}</td>
                <td>{{ $req->department->name }}</td>
                <td>
                    <span class="priority">
                        <span class="dot dot-{{ strtolower($req->priority) }}"></span>
                        {{ ucfirst($req->priority) }}
                    </span>
                </td>
                <td>
                    <span class="badge badge-{{ $req->getStatusBadgeClass() }}">
                        {{ ucfirst($req->status) }}
                    </span>
                </td>
                <td>
                    @if($req->payment)
                        <span class="amount">RWF {{ number_format($req->payment->amount, 2) }}</span>
                    @else
                        <span style="color:#94A3B8;">N/A</span>
                    @endif
                </td>
                <td>{{ $req->created_at->format('d M Y') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="9" style="text-align:center; color:#94A3B8; padding:20px;">
                    No records found.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- ── FOOTER ── --}}
    <div class="footer">
        <p>StockSys &mdash; Internal Stock Requisition Management System &copy; {{ date('Y') }}</p>
    </div>

</body>
</html>