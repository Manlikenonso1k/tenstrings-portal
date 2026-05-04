<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $gatewayLabel }} Payment Step</title>
    <style>
        :root {
            color-scheme: light;
            --bg: #f8fafc;
            --panel: rgba(255, 255, 255, 0.96);
            --panel-soft: #f8fafc;
            --text: #0f172a;
            --muted: #64748b;
            --line: #e2e8f0;
            --line-strong: #cbd5e1;
            --accent: {{ $gateway === 'tgipay' ? '#0f766e' : '#2563eb' }};
            --accent-strong: {{ $gateway === 'tgipay' ? '#115e59' : '#1d4ed8' }};
            --accent-soft: {{ $gateway === 'tgipay' ? '#ccfbf1' : '#dbeafe' }};
            --shadow: 0 24px 64px rgba(15, 23, 42, 0.08);
            --radius-xl: 28px;
            --radius-lg: 22px;
            --radius-md: 16px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            background:
                radial-gradient(circle at top right, rgba(37, 99, 235, 0.08), transparent 36%),
                radial-gradient(circle at bottom left, rgba(14, 165, 233, 0.08), transparent 30%),
                var(--bg);
            color: var(--text);
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            -webkit-font-smoothing: antialiased;
            text-rendering: optimizeLegibility;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        .page {
            max-width: 1120px;
            margin: 0 auto;
            padding: 40px 24px;
        }

        .topbar {
            display: flex;
            gap: 16px;
            align-items: end;
            justify-content: space-between;
            margin-bottom: 28px;
        }

        .eyebrow {
            margin: 0 0 10px;
            color: var(--muted);
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.24em;
            text-transform: uppercase;
        }

        h1 {
            margin: 0;
            font-size: clamp(2rem, 3vw, 3rem);
            line-height: 1.08;
            letter-spacing: -0.04em;
        }

        .subtitle {
            max-width: 720px;
            margin: 10px 0 0;
            color: var(--muted);
            font-size: 15px;
            line-height: 1.7;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 44px;
            padding: 0 16px;
            border: 1px solid var(--line);
            border-radius: 999px;
            background: #fff;
            color: #334155;
            font-size: 14px;
            font-weight: 600;
            box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
            transition: transform 0.2s ease, border-color 0.2s ease, background 0.2s ease;
        }

        .back-link:hover {
            border-color: var(--line-strong);
            background: #f8fafc;
            transform: translateY(-1px);
        }

        .notice {
            margin-bottom: 20px;
            padding: 14px 16px;
            border: 1px solid #fecaca;
            border-radius: 18px;
            background: rgba(254, 242, 242, 0.9);
            color: #b91c1c;
            box-shadow: 0 8px 24px rgba(185, 28, 28, 0.06);
            font-size: 14px;
        }

        .notice ul {
            margin: 0;
            padding-left: 18px;
        }

        .layout {
            display: grid;
            grid-template-columns: 1.15fr 0.85fr;
            gap: 24px;
        }

        .card {
            border: 1px solid var(--line);
            border-radius: var(--radius-xl);
            background: var(--panel);
            box-shadow: var(--shadow);
            backdrop-filter: blur(14px);
        }

        .card-body {
            padding: 28px;
        }

        .card-head {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 20px;
        }

        .card-title {
            margin: 4px 0 0;
            font-size: 20px;
            line-height: 1.2;
            letter-spacing: -0.03em;
        }

        .muted {
            color: var(--muted);
        }

        .badge {
            display: inline-flex;
            align-items: center;
            min-height: 34px;
            padding: 0 12px;
            border-radius: 999px;
            background: #eff6ff;
            color: #1d4ed8;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 0.16em;
            text-transform: uppercase;
        }

        .summary {
            padding: 18px;
            border: 1px solid var(--line);
            border-radius: 24px;
            background: var(--panel-soft);
        }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 14px;
        }

        .summary-item {
            padding: 16px;
            border: 1px solid #eef2f7;
            border-radius: 20px;
            background: #fff;
            box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
        }

        .label {
            display: block;
            margin-bottom: 8px;
            color: #94a3b8;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 0.18em;
            text-transform: uppercase;
        }

        .value {
            font-size: 16px;
            font-weight: 700;
            color: #0f172a;
            line-height: 1.4;
            word-break: break-word;
        }

        .value-large {
            font-size: 22px;
        }

        .form-title {
            margin: 0;
            font-size: 20px;
            line-height: 1.2;
        }

        .form-group {
            margin-top: 22px;
        }

        .form-label {
            display: block;
            margin-bottom: 10px;
            font-size: 14px;
            font-weight: 700;
            color: #334155;
        }

        .input {
            width: 100%;
            min-height: 54px;
            padding: 0 16px;
            border: 1px solid var(--line);
            border-radius: 18px;
            background: #f8fafc;
            color: #0f172a;
            font-size: 16px;
            outline: none;
            transition: border-color 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
        }

        .input:focus {
            border-color: var(--accent);
            background: #fff;
            box-shadow: 0 0 0 4px var(--accent-soft);
        }

        .helper {
            margin-top: 10px;
            color: var(--muted);
            font-size: 12px;
            line-height: 1.6;
        }

        .actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 22px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 50px;
            padding: 0 20px;
            border-radius: 18px;
            font-size: 14px;
            font-weight: 800;
            transition: transform 0.2s ease, background 0.2s ease, border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .btn-primary {
            border: 1px solid var(--accent);
            background: var(--accent);
            color: #fff;
            box-shadow: 0 16px 32px {{ $gateway === 'tgipay' ? 'rgba(15, 118, 110, 0.18)' : 'rgba(37, 99, 235, 0.18)' }};
        }

        .btn-primary:hover {
            background: var(--accent-strong);
            transform: translateY(-1px);
        }

        .btn-secondary {
            border: 1px solid var(--line);
            background: #fff;
            color: #334155;
        }

        .btn-secondary:hover {
            border-color: var(--line-strong);
            background: #f8fafc;
            transform: translateY(-1px);
        }

        @media (max-width: 900px) {
            .topbar,
            .layout {
                grid-template-columns: 1fr;
                display: grid;
            }

            .topbar {
                align-items: start;
            }

            .summary-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
<main class="page">
    <div class="topbar">
        <div>
            <p class="eyebrow">Two-step payment</p>
            <h1>{{ $gatewayLabel }} Payment</h1>
            <p class="subtitle">
                Review the advice below, choose the amount to pay, and continue to the secure {{ $gatewayLabel }} payment gateway.
            </p>
        </div>

        <a href="{{ route('fees.advice.current') }}" class="back-link">Back to Advice</a>
    </div>

    @if ($errors->any())
        <div class="notice">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="layout">
        <section class="card">
            <div class="card-body">
                <div class="card-head">
                    <div>
                        <p class="muted" style="margin:0;font-size:14px;font-weight:600;">Student summary</p>
                        <h2 class="card-title">Payment overview</h2>
                    </div>
                    <div class="badge">{{ $gatewayLabel }}</div>
                </div>

                <div class="summary">
                    <div class="summary-grid">
                        <div class="summary-item">
                            <span class="label">Name</span>
                            <div class="value">{{ trim($student->first_name . ' ' . ($student->middle_name ? $student->middle_name . ' ' : '') . $student->last_name) ?: 'Test Student' }}</div>
                        </div>
                        <div class="summary-item">
                            <span class="label">Advice Period</span>
                            <div class="value">{{ $pendingAdvice->quarter_name ?? 'Q2-2026' }}</div>
                        </div>
                        <div class="summary-item">
                            <span class="label">Outstanding Balance</span>
                            <div class="value value-large">₦{{ number_format((float) $outstandingBalance, 2) }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="card">
            <div class="card-body">
                <p class="muted" style="margin:0;font-size:14px;font-weight:600;">Payment form</p>
                <h2 class="form-title" style="margin-top:4px;">Amount to Pay</h2>

                <form action="{{ route('fees.pay.submit', $gateway) }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label for="amount" class="form-label">Amount to Pay</label>
                        <input
                            id="amount"
                            name="amount"
                            type="number"
                            min="1"
                            max="{{ (float) $outstandingBalance }}"
                            step="0.01"
                            value="{{ old('amount', $defaultAmount) }}"
                            class="input"
                            required
                        >
                        <div class="helper">Pre-filled with the outstanding balance for a faster checkout.</div>
                    </div>

                    <div class="actions">
                        <button type="submit" class="btn btn-primary">Continue to {{ $gatewayLabel }}</button>
                        <a href="{{ route('fees.generate') }}" class="btn btn-secondary">Back to Generate Fees</a>
                    </div>
                </form>
            </div>
        </section>
    </div>
</main>
</body>
</html>