<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Current Payment Advice</title>
    <style>
        :root {
            color-scheme: light;
            --bg: #edf1f5;
            --surface: #ffffff;
            --surface-soft: #f8fafc;
            --text: #0f172a;
            --muted: #64748b;
            --border: #dbe3ec;
            --border-strong: #cbd5e1;
            --blue: #2563eb;
            --blue-hover: #1d4ed8;
            --slate: #334155;
            --slate-hover: #0f172a;
            --shadow: 0 22px 60px rgba(15, 23, 42, 0.08);
            --radius-xl: 28px;
            --radius-lg: 22px;
            --radius-md: 16px;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            min-height: 100vh;
            background: linear-gradient(180deg, #eef2f6 0%, #e9eef4 100%);
            color: var(--text);
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            -webkit-font-smoothing: antialiased;
            text-rendering: optimizeLegibility;
        }

        a { color: inherit; text-decoration: none; }

        .shell {
            max-width: 1080px;
            margin: 0 auto;
            padding: 40px 24px 56px;
        }

        .topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 24px;
        }

        h1 {
            margin: 0;
            font-size: clamp(1.9rem, 2.6vw, 2.6rem);
            line-height: 1.1;
            letter-spacing: -0.04em;
        }

        .back-link {
            color: var(--blue);
            font-size: 14px;
            font-weight: 700;
            transition: color 0.2s ease;
        }

        .back-link:hover { color: var(--blue-hover); }

        .notice {
            margin-bottom: 18px;
            padding: 14px 16px;
            border: 1px solid #bbf7d0;
            border-radius: 16px;
            background: rgba(240, 253, 244, 0.92);
            color: #166534;
            font-size: 14px;
        }

        .error-box {
            margin-bottom: 18px;
            padding: 14px 16px;
            border: 1px solid #fecaca;
            border-radius: 16px;
            background: rgba(254, 242, 242, 0.94);
            color: #b91c1c;
            font-size: 14px;
        }

        .error-box ul {
            margin: 0;
            padding-left: 18px;
        }

        .stage {
            display: flex;
            justify-content: center;
        }

        .panel {
            width: 100%;
            max-width: 920px;
            border: 1px solid var(--border);
            border-radius: var(--radius-xl);
            background: var(--surface);
            box-shadow: var(--shadow);
            overflow: hidden;
        }

        .panel-inner {
            padding: 30px;
        }

        .doc-card {
            border: 1px solid var(--border);
            border-radius: var(--radius-xl);
            background: #fff;
            padding: 28px;
        }

        .doc-title {
            margin: 0 0 20px;
            font-size: 1.25rem;
            line-height: 1.2;
            font-weight: 800;
            letter-spacing: -0.03em;
        }

        .doc-subtitle {
            margin: 4px 0 0;
            color: var(--muted);
            font-size: 14px;
            font-weight: 600;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 14px;
        }

        .field {
            border: 1px solid #edf2f7;
            border-radius: var(--radius-md);
            background: var(--surface-soft);
            padding: 16px 18px;
        }

        .field-label {
            display: block;
            margin-bottom: 8px;
            color: #94a3b8;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 0.2em;
            text-transform: uppercase;
        }

        .field-value {
            color: #0f172a;
            font-size: 15px;
            font-weight: 700;
            line-height: 1.5;
            word-break: break-word;
        }

        .divider {
            height: 1px;
            margin: 22px 0;
            background: linear-gradient(90deg, transparent, var(--border-strong), transparent);
        }

        .financials {
            display: grid;
            gap: 12px;
        }

        .row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding: 4px 0;
            font-size: 15px;
        }

        .row span:first-child {
            color: #475569;
            font-weight: 600;
        }

        .row span:last-child {
            color: #0f172a;
            font-weight: 700;
        }

        .total {
            padding-top: 6px;
            border-top: 1px solid var(--border);
            font-size: 18px;
        }

        .total span:last-child {
            font-size: 1.05rem;
        }

        .actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 18px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 48px;
            padding: 0 18px;
            border-radius: 16px;
            font-size: 14px;
            font-weight: 800;
            border: 1px solid transparent;
            transition: transform 0.2s ease, background 0.2s ease, border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .btn:hover { transform: translateY(-1px); }

        .btn-primary {
            background: var(--blue);
            color: #fff;
            box-shadow: 0 16px 30px rgba(37, 99, 235, 0.16);
        }

        .btn-primary:hover { background: var(--blue-hover); }

        .btn-dark {
            background: var(--slate);
            color: #fff;
            box-shadow: 0 16px 30px rgba(15, 23, 42, 0.12);
        }

        .btn-dark:hover { background: var(--slate-hover); }

        .btn-outline {
            background: #fff;
            color: #334155;
            border-color: var(--border);
            box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
        }

        .btn-outline:hover {
            border-color: var(--border-strong);
            background: #f8fafc;
        }

        @media (max-width: 720px) {
            .topbar {
                flex-direction: column;
                align-items: flex-start;
            }

            .grid {
                grid-template-columns: 1fr;
            }

            .panel-inner {
                padding: 20px;
            }

            .doc-card {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
<div class="shell">
    <div class="topbar">
        <h1>Current Payment Advice</h1>
        <a href="{{ \App\Filament\Portal\Pages\PaymentsPage::getUrl(panel: 'portal') }}" class="back-link">Back to Payments</a>
    </div>

    @if (session('status'))
        <div class="notice">{{ session('status') }}</div>
    @endif

    @if ($errors->any())
        <div class="error-box">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (! $advice)
        <div class="panel">
            <div class="panel-inner">
                <p style="margin:0;color:var(--muted);">No pending advice found.</p>
                <a href="{{ route('fees.generate') }}" class="btn btn-primary" style="margin-top:16px;">Generate fees now</a>
            </div>
        </div>
    @else
        <div class="stage">
            <div class="panel">
                <div class="panel-inner">
                    <div class="doc-card" id="printable-advice">
                        <div style="display:flex;justify-content:space-between;gap:12px;align-items:flex-start;flex-wrap:wrap;margin-bottom:18px;">
                            <div>
                                <p class="doc-subtitle" style="margin:0 0 6px;">Official document</p>
                                <h2 class="doc-title" style="margin:0;">Tenstrings Music Institute - Payment Advice</h2>
                            </div>
                            <div style="display:inline-flex;align-items:center;min-height:34px;padding:0 12px;border-radius:999px;background:#f8fafc;color:#334155;border:1px solid var(--border);font-size:11px;font-weight:800;letter-spacing:0.16em;text-transform:uppercase;">Statement</div>
                        </div>

                        <div class="grid">
                            <div class="field">
                                <span class="field-label">Name</span>
                                <div class="field-value">{{ $student->first_name }} {{ $student->middle_name }} {{ $student->last_name }}</div>
                            </div>
                            <div class="field">
                                <span class="field-label">Student Number</span>
                                <div class="field-value">{{ $student->student_number ?: '202605ADMP002' }}</div>
                            </div>
                            <div class="field">
                                <span class="field-label">Course</span>
                                <div class="field-value">{{ $advice->course?->name ?? 'N/A' }}</div>
                            </div>
                            <div class="field">
                                <span class="field-label">Quarter</span>
                                <div class="field-value">{{ $advice->quarter_name }}</div>
                            </div>
                            <div class="field">
                                <span class="field-label">Status</span>
                                <div class="field-value">{{ strtoupper($advice->status) }}</div>
                            </div>
                            <div class="field">
                                <span class="field-label">Date Generated</span>
                                <div class="field-value">{{ optional($advice->generated_at)->format('Y-m-d H:i') }}</div>
                            </div>
                        </div>

                        <div class="divider"></div>

                        <div class="financials">
                            <div class="row">
                                <span>Tuition Fee</span>
                                <span>₦{{ number_format((float) $advice->amount, 2) }}</span>
                            </div>
                            <div class="row total">
                                <span>Total</span>
                                <span>₦{{ number_format((float) $advice->amount, 2) }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="actions">
                        @if ($paystackEnabled)
                            <a href="{{ route('fees.pay.step', 'paystack-titan') }}" class="btn btn-primary">Pay with Paystack Titan</a>
                        @endif
                        @if ($tgipayEnabled)
                            <a href="{{ route('fees.pay.step', 'tgipay') }}" class="btn btn-dark">Pay with TGIPAY</a>
                        @endif
                        <button onclick="window.print()" class="btn btn-outline" type="button">Print Advice</button>
                    </div>

                    @if (! $paystackEnabled && ! $tgipayEnabled)
                        <div class="notice" style="margin-top:18px;background:rgba(255,247,237,0.95);border-color:#fed7aa;color:#9a3412;">
                            Payment is currently unavailable because all gateways are disabled by the administrator.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>
</body>
</html>
