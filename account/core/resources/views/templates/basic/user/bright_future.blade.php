@extends($activeTemplate . 'layouts.master')
@section('content')
    @php $general = gs(); @endphp
    <style>
        :root {
            --bg-dark: #1e293b; /* Match the dark card background */
            --bg-darker: #0f172a;
            --text-light: #f8fafc;
            --text-muted: #94a3b8;
            --accent-color: #8b5cf6; /* Purple accent from image */
            --success-color: #10b981;
            --card-radius: 16px;
        }

        .bright-future-wrapper {
            font-family: 'Inter', sans-serif;
            color: var(--text-light);
        }

        /* Main Page Title */
        .page-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 20px;
            color: #fff;
        }

        /* Stats Cards Row */
        .stats-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-box {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: var(--card-radius);
            padding: 24px;
            display: flex;
            flex-direction: column;
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        .stat-box .label {
            color: var(--text-muted);
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-weight: 600;
            margin-bottom: 12px;
        }

        .stat-box .value {
            font-size: 1.75rem;
            font-weight: 700;
            color: #fff;
            margin-bottom: 4px;
            line-height: 1.2;
        }
        
        .stat-box .currency {
            font-size: 1rem;
            color: var(--text-muted);
            font-weight: 500;
        }

        .stat-box .sub-text {
            font-size: 0.85rem;
            color: var(--text-muted);
            margin-top: auto;
            padding-top: 12px;
        }

        /* Progress Section */
        .progress-card {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: var(--card-radius);
            padding: 24px;
            margin-bottom: 30px;
        }

        .progress-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .progress-title {
            font-weight: 600;
            font-size: 1.1rem;
        }

        .progress-percentage {
            font-weight: 700;
            font-size: 1.1rem;
        }

        .progress-container {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            height: 10px;
            width: 100%;
            overflow: hidden;
            margin-bottom: 15px;
        }

        .progress-bar {
            background: linear-gradient(90deg, #6366f1, #8b5cf6);
            height: 100%;
            border-radius: 10px;
            width: 0;
            transition: width 1s ease;
        }

        .progress-desc {
            color: var(--text-muted);
            font-size: 0.9rem;
            line-height: 1.5;
        }

        .progress-desc strong {
            color: #fff;
        }

        /* History Section */
        .history-title {
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 20px;
            color: #fff;
        }

        .history-container {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: var(--card-radius);
            overflow: hidden;
        }

        .custom-table {
            width: 100%;
            border-collapse: collapse;
        }

        .custom-table th {
            text-align: left;
            padding: 18px 24px;
            color: var(--text-muted);
            font-weight: 600;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            font-size: 0.9rem;
        }

        .custom-table td {
            padding: 20px 24px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            color: var(--text-light);
            vertical-align: middle;
        }

        .custom-table tr:last-child td {
            border-bottom: none;
        }

        .trx-id {
            font-family: monospace;
            color: #fff;
            font-weight: 600;
        }

        .date-time {
            display: flex;
            flex-direction: column;
            font-size: 0.9rem;
        }
        
        .date-time span:last-child {
            color: var(--text-muted);
            font-size: 0.8rem;
            margin-top: 2px;
        }

        .amount-text {
            color: var(--success-color);
            font-weight: 600;
            font-size: 1rem;
        }

        .status-badge {
            background: rgba(16, 185, 129, 0.2);
            color: var(--success-color);
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            display: inline-block;
        }

        @media (max-width: 768px) {
            .stats-row {
                grid-template-columns: 1fr;
            }
            .custom-table th, .custom-table td {
                padding: 15px;
            }
            .custom-table {
                display: block;
                overflow-x: auto;
            }
        }
    </style>

    <div class="bright-future-wrapper">
        <h2 class="page-title">@lang('Bright Future Plan')</h2>

        <div class="stats-row">
            <!-- Total Received -->
            <div class="stat-box">
                <div class="label">@lang('Total Received')</div>
                <div class="value">{{ $general->cur_sym }}{{ showAmount($receivedAmount, 2) }}</div>
                <div class="currency">{{ $general->cur_text }}</div>
                <div class="sub-text">@lang('From Daily Profit')</div>
            </div>

            <!-- Remaining Cap -->
            <div class="stat-box">
                <div class="label">@lang('Remaining Cap')</div>
                <div class="value">{{ $general->cur_sym }}{{ showAmount($remainingAmount, 2) }}</div>
                <div class="currency">{{ $general->cur_text }}</div>
                <div class="sub-text">@lang('Until Max Cap Reached')</div>
            </div>

            <!-- Target Goal -->
            <div class="stat-box">
                <div class="label">@lang('Target Goal')</div>
                <div class="value">{{ $general->cur_sym }}{{ showAmount($maxCap, 2) }}</div>
                <div class="currency">{{ $general->cur_text }}</div>
                <div class="sub-text">@lang('Maximum Earnings')</div>
            </div>
        </div>

        <!-- Progress -->
        <div class="progress-card">
            <div class="progress-header">
                <div class="progress-title">@lang('Plan Progress')</div>
                <div class="progress-percentage">{{ round($progress, 1) }}%</div>
            </div>
            <div class="progress-container">
                <div class="progress-bar" style="width: {{ $progress }}%"></div>
            </div>
            <div class="progress-desc">
                @lang('You have received') <strong>{{ $general->cur_sym }}{{ showAmount($receivedAmount, 2) }} {{ $general->cur_text }}</strong> @lang('out of') <strong>{{ $general->cur_sym }}{{ showAmount($maxCap, 2) }} {{ $general->cur_text }}</strong>. @lang('Daily profit will stop automatically once 100% is reached.')
            </div>
        </div>

        <!-- History -->
        <h3 class="history-title">@lang('Profit History')</h3>
        <div class="history-container">
            <table class="custom-table">
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
                            <td><span class="trx-id">#{{ $trx->trx }}</span></td>
                            <td>
                                <div class="date-time">
                                    <span>{{ showDateTime($trx->created_at, 'Y-m-d') }}</span>
                                    <span>{{ showDateTime($trx->created_at, 'h:i A') }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="amount-text">
                                    +{{ showAmount($trx->amount) }}<br>
                                    <span style="font-size: 0.8rem; opacity: 0.7;">{{ $general->cur_text }}</span>
                                </div>
                            </td>
                            <td style="max-width: 300px; line-height: 1.4;">{{ __($trx->details) }}</td>
                            <td><span class="status-badge">@lang('Received')</span></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">@lang('No daily profit received yet.')</td>
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
