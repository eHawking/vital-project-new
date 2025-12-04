@extends($activeTemplate . 'layouts.master')
@section('content')
    <style>
        /* --- Premium Theme Variables --- */
        :root {
            /* Dark Mode (Default) */
            --bg-body: #0f172a;
            --bg-card: rgba(30, 41, 59, 0.7);
            --border-color: rgba(255, 255, 255, 0.1);
            --text-primary: #ffffff;
            --text-secondary: #94a3b8;
            --input-bg: rgba(255, 255, 255, 0.03);
            --input-border: rgba(255, 255, 255, 0.1);
            --input-focus: rgba(139, 92, 246, 0.5);
            --primary-grad: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            --shape-color-1: #4f46e5;
            --shape-color-2: #c026d3;
            --shadow-lg: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            --glass-blur: blur(20px);
        }

        /* --- Main Card Wrapper --- */
        .premium-card {
            background: var(--bg-card);
            backdrop-filter: var(--glass-blur);
            -webkit-backdrop-filter: var(--glass-blur);
            border: 1px solid var(--border-color);
            border-radius: 24px;
            box-shadow: var(--shadow-lg);
            overflow: hidden;
            margin-bottom: 30px;
            padding: 30px;
            color: var(--text-primary);
        }

        .premium-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .premium-title {
            font-size: 1.75rem;
            font-weight: 700;
            background: linear-gradient(to right, #fff, #e2e8f0);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* --- Stats Grid --- */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 25px;
            display: flex;
            flex-direction: column;
            position: relative;
            overflow: hidden;
            transition: transform 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            border-color: rgba(139, 92, 246, 0.3);
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0; right: 0;
            width: 100px; height: 100px;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.15) 0%, transparent 70%);
            transform: translate(30%, -30%);
            border-radius: 50%;
        }

        .stat-label {
            color: var(--text-secondary);
            font-size: 0.9rem;
            font-weight: 500;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-primary);
        }
        
        .stat-sub {
            font-size: 0.85rem;
            color: var(--text-secondary);
            margin-top: 5px;
        }

        /* --- Progress Section --- */
        .progress-section {
            margin-bottom: 40px;
            background: rgba(0,0,0,0.2);
            border-radius: 20px;
            padding: 30px;
            border: 1px solid var(--border-color);
        }

        .progress-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            color: var(--text-primary);
            font-weight: 600;
        }

        .progress-track {
            width: 100%;
            height: 12px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            overflow: hidden;
            position: relative;
        }

        .progress-bar-fill {
            height: 100%;
            background: var(--primary-grad);
            border-radius: 10px;
            position: relative;
            transition: width 1s ease-in-out;
        }

        .progress-bar-fill::after {
            content: '';
            position: absolute;
            top: 0; left: 0; bottom: 0; right: 0;
            background: linear-gradient(45deg, rgba(255,255,255,0.15) 25%, transparent 25%, transparent 50%, rgba(255,255,255,0.15) 50%, rgba(255,255,255,0.15) 75%, transparent 75%, transparent);
            background-size: 1rem 1rem;
            animation: progress-stripes 1s linear infinite;
        }

        @keyframes progress-stripes {
            0% { background-position: 1rem 0; }
            100% { background-position: 0 0; }
        }

        /* --- Transaction Table --- */
        .table-custom {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 10px;
        }

        .table-custom th {
            color: var(--text-secondary);
            font-weight: 600;
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid var(--border-color);
        }

        .table-custom td {
            background: rgba(255, 255, 255, 0.02);
            padding: 20px 15px;
            color: var(--text-primary);
            border-top: 1px solid transparent;
            border-bottom: 1px solid transparent;
            transition: all 0.2s;
        }
        
        .table-custom tr td:first-child {
            border-top-left-radius: 12px;
            border-bottom-left-radius: 12px;
            border-left: 1px solid rgba(255,255,255,0.05);
        }
        
        .table-custom tr td:last-child {
            border-top-right-radius: 12px;
            border-bottom-right-radius: 12px;
            border-right: 1px solid rgba(255,255,255,0.05);
        }

        .table-custom tr:hover td {
            background: rgba(255, 255, 255, 0.05);
            transform: scale(1.01);
        }

        .trx-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            background: rgba(46, 204, 113, 0.2);
            color: #2ecc71;
        }

    </style>

    <div class="premium-card">
        <div class="premium-header">
            <h3 class="premium-title">@lang('Bright Future Plan')</h3>
        </div>

        <div class="stats-grid">
            <!-- Received Amount -->
            <div class="stat-card">
                <div class="stat-label">@lang('Total Received')</div>
                <div class="stat-value">{{ $general->cur_sym }}{{ showAmount($receivedAmount, 2) }}</div>
                <div class="stat-sub">@lang('From Daily Profit')</div>
            </div>

            <!-- Remaining Amount -->
            <div class="stat-card">
                <div class="stat-label">@lang('Remaining Cap')</div>
                <div class="stat-value">{{ $general->cur_sym }}{{ showAmount($remainingAmount, 2) }}</div>
                <div class="stat-sub">@lang('Until Max Cap Reached')</div>
            </div>

            <!-- Max Cap -->
            <div class="stat-card">
                <div class="stat-label">@lang('Target Goal')</div>
                <div class="stat-value">{{ $general->cur_sym }}{{ showAmount($maxCap, 2) }}</div>
                <div class="stat-sub">@lang('Maximum Earnings')</div>
            </div>
        </div>

        <!-- Progress Bar Section -->
        <div class="progress-section">
            <div class="progress-header">
                <span>@lang('Plan Progress')</span>
                <span>{{ round($progress, 1) }}%</span>
            </div>
            <div class="progress-track">
                <div class="progress-bar-fill" style="width: {{ $progress }}%;"></div>
            </div>
            <p class="mt-3 text-white-50 small">
                @lang('You have received') <strong class="text-white">{{ $general->cur_sym }}{{ showAmount($receivedAmount) }}</strong> @lang('out of') <strong class="text-white">{{ $general->cur_sym }}{{ showAmount($maxCap) }}</strong>. @lang('Daily profit will stop automatically once 100% is reached.')
            </p>
        </div>

        <!-- Transaction History -->
        <h4 class="mb-4 text-white">@lang('Profit History')</h4>
        <div class="table-responsive">
            <table class="table-custom">
                <thead>
                    <tr>
                        <th>@lang('Transaction ID')</th>
                        <th>@lang('Date')</th>
                        <th>@lang('Amount')</th>
                        <th>@lang('Details')</th>
                        <th>@lang('Status')</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $trx)
                        <tr>
                            <td class="fw-bold">#{{ $trx->trx }}</td>
                            <td>{{ showDateTime($trx->created_at) }}</td>
                            <td class="fw-bold text-success">+{{ showAmount($trx->amount) }} {{ $general->cur_text }}</td>
                            <td>{{ __($trx->details) }}</td>
                            <td><span class="trx-badge">@lang('Received')</span></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">@lang('No daily profit received yet.')</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($transactions->hasPages())
            <div class="mt-4">
                {{ paginateLinks($transactions) }}
            </div>
        @endif
    </div>
@endsection
