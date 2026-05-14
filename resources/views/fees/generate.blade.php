<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Generate Fees</title>
    <style>
        :root {
            --font-sans: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            --color-background-primary: #ffffff;
            --color-border-tertiary: #dbe3ec;
            --color-border-secondary: #cbd5e1;
            --color-text-primary: #0f172a;
            --color-text-secondary: #64748b;
            --border-radius-lg: 24px;
            --border-radius-md: 16px;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: var(--font-sans);
            background: linear-gradient(180deg, #eef2f6 0%, #e9eef4 100%);
            color: var(--color-text-primary);
            min-height: 100vh;
        }

        a { color: inherit; text-decoration: none; }

        .page { max-width: 760px; margin: 0 auto; padding: 2rem 1rem 3rem; }

        .top { display: flex; align-items: center; justify-content: space-between; gap: 1rem; margin-bottom: 2rem; }

        .back { font-size: 13px; color: #185FA5; display: inline-flex; align-items: center; gap: 4px; }

        .back:hover { text-decoration: underline; }

        .eyebrow {
            margin-bottom: .5rem;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .24em;
            text-transform: uppercase;
            color: #64748b;
        }

        h1 { font-size: clamp(2rem, 3vw, 2.6rem); line-height: 1.05; letter-spacing: -0.04em; }

        .subtitle { margin-top: .6rem; color: #64748b; font-size: 14px; line-height: 1.7; max-width: 52rem; }

        .notice, .error-box {
            margin-bottom: 1rem;
            padding: 14px 16px;
            border-radius: 16px;
            font-size: 14px;
        }

        .notice {
            border: 1px solid #bbf7d0;
            background: rgba(240, 253, 244, 0.92);
            color: #166534;
        }

        .error-box {
            border: 1px solid #fecaca;
            background: rgba(254, 242, 242, 0.95);
            color: #b91c1c;
        }

        .error-box ul { margin: 0; padding-left: 18px; }

        .card {
            background: var(--color-background-primary);
            border: 1px solid var(--color-border-tertiary);
            border-radius: var(--border-radius-lg);
            padding: 1.75rem;
            box-shadow: 0 22px 60px rgba(15, 23, 42, 0.08);
        }

        .field { margin-bottom: 1.5rem; }

        .field-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: var(--color-text-secondary);
            margin-bottom: 8px;
            letter-spacing: 0.02em;
            text-transform: uppercase;
        }

        .year-input {
            width: 160px;
            border: 1px solid var(--color-border-secondary);
            border-radius: var(--border-radius-md);
            padding: 10px 14px;
            font-size: 15px;
            font-family: var(--font-sans);
            color: var(--color-text-primary);
            background: #fff;
            outline: none;
            transition: border-color 0.15s, box-shadow 0.15s;
        }

        .year-input:focus { border-color: #378ADD; box-shadow: 0 0 0 3px rgba(55, 138, 221, 0.12); }

        .divider { height: 1px; background: var(--color-border-tertiary); margin: 1.5rem 0; }

        .quarters { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 10px; }

        .q-option { position: relative; }

        .q-option input[type="radio"] { position: absolute; opacity: 0; width: 0; height: 0; }

        .q-label {
            display: flex; align-items: center; justify-content: space-between;
            padding: 14px 16px;
            border: 1px solid var(--color-border-tertiary);
            border-radius: var(--border-radius-md);
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            color: var(--color-text-primary);
            transition: border-color 0.15s, background 0.15s;
            background: #fff;
        }

        .q-label:hover { border-color: #378ADD; background: rgba(55, 138, 221, 0.04); }

        .q-option input:checked + .q-label { border: 1.5px solid #378ADD; background: rgba(55, 138, 221, 0.06); color: #185FA5; }

        .q-dot {
            width: 16px; height: 16px; border-radius: 50%; border: 1.5px solid var(--color-border-secondary);
            display: inline-flex; align-items: center; justify-content: center; flex-shrink: 0;
            transition: border-color 0.15s;
        }

        .q-option input:checked + .q-label .q-dot { border-color: #378ADD; background: #378ADD; }

        .q-option input:checked + .q-label .q-dot::after { content: ''; width: 6px; height: 6px; border-radius: 50%; background: white; }

        .submit-btn {
            margin-top: 1.75rem;
            width: 100%;
            padding: 12px 16px;
            border-radius: var(--border-radius-md);
            background: #185FA5;
            color: #fff;
            font-size: 14px;
            font-weight: 700;
            font-family: var(--font-sans);
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: background 0.15s, transform 0.15s;
        }

        .submit-btn:hover { background: #0C447C; }

        .submit-btn:active { transform: scale(0.99); }

        .helper {
            margin-top: .8rem;
            color: #64748b;
            font-size: 12px;
            line-height: 1.6;
        }

        .badge {
            font-size: 11px;
            font-weight: 700;
            background: #E6F1FB;
            color: #0C447C;
            padding: 3px 10px;
            border-radius: var(--border-radius-md);
            letter-spacing: 0.03em;
        }

        @media (max-width: 640px) {
            .top { flex-direction: column; align-items: flex-start; }
            .quarters { grid-template-columns: 1fr; }
            .year-input { width: 100%; }
        }
    </style>
</head>
<body>
<div class="page">
    <div class="top">
        <div>
            <p class="eyebrow">Fee workflow</p>
            <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
                <h1>Generate fees</h1>
                <span class="badge">Admin</span>
            </div>
            <p class="subtitle">Generate the current payment advice for the student. Choose the intake month and year, then submit to create the pending advice that the student portal will use for payment.</p>
        </div>
        <a class="back" href="{{ \App\Filament\Portal\Pages\PaymentsPage::getUrl(panel: 'portal') }}">
            <span aria-hidden="true">←</span> Payments
        </a>
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

    @if ($pendingAdvice)
        <div class="notice" style="border-color:#fed7aa;background:rgba(255,247,237,0.96);color:#9a3412;">
            You already have a pending advice. {{ $pendingAdvice->quarter_name }} • ₦{{ number_format((float) $pendingAdvice->amount, 2) }}
        </div>
    @endif

    <form action="{{ route('fees.generate.store') }}" method="POST" class="card">
        @csrf

        <div class="field">
            <label class="field-label" for="year">Year</label>
            <input class="year-input" type="number" id="year" name="year" value="{{ old('year', $year) }}" min="2020" max="2100" required>
        </div>

        <div class="divider"></div>

        <div class="field" style="margin-bottom:0">
            <label class="field-label">Quarter intake month</label>
            <div class="quarters">
                @foreach ($quarters as $quarter)
                    <div class="q-option">
                        <input type="radio" name="quarter_month" id="q{{ $quarter['month'] }}" value="{{ $quarter['month'] }}" @checked(old('quarter_month') == $quarter['month']) required>
                        <label class="q-label" for="q{{ $quarter['month'] }}">
                            <span>{{ $quarter['label'] }}</span>
                            <span class="q-dot"></span>
                        </label>
                    </div>
                @endforeach
            </div>
            <div class="helper">This will create a pending advice for the selected quarter intake month.</div>
        </div>

        <button class="submit-btn" type="submit">
            <span aria-hidden="true">▣</span>
            Generate advice
        </button>
    </form>
</div>
</body>
</html>
