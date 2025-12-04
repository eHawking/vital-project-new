@extends($activeTemplate . 'layouts.master')
@section('content')
    @php $general = gs(); @endphp
    
    <!-- Include Theme CSS -->
    @include($activeTemplate . 'css.modern-finance-theme')
    @include($activeTemplate . 'css.mobile-fixes')

    <!-- Include Color Picker (Right Sidebar) -->
    @include($activeTemplate . 'partials.color-picker')

    <div class="container-fluid px-4 py-4 inner-dashboard-container">
        <div class="bright-future-wrapper">

            <!-- Page Header -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                        <div>
                            <h3 class="premium-title mb-0">@lang('Bright Future Plan')</h3>
                            <p class="text-muted small m-0">@lang('Track your daily profits and plan progress')</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Wallet Overview (Like Dashboard) -->
            <div class="wallet-overview mb-4">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="wallet-balance-title">@lang('Total Received')</div>
                        <div class="wallet-balance-amount">{{ showAmount($receivedAmount, 2, currencyFormat: false) }} <span style="font-size: 1.5rem">{{ $general->cur_text }}</span></div>
                        <div class="wallet-balance-sub">@lang('From Daily Profit')</div>
                    </div>
                    <div class="text-end">
                        <span class="badge bg-success bg-opacity-75">@lang('Plan Active')</span>
                    </div>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="stats-grid">
                
                <!-- Remaining Cap -->
                <div class="premium-card stat-item">
                    <div class="stat-info">
                        <h6 class="text-muted text-uppercase">@lang('Remaining Cap')</h6>
                        <h3>{{ showAmount($remainingAmount, 2, currencyFormat: false) }} {{ $general->cur_text }}</h3>
                        <small class="text-warning">@lang('Until Max Cap Reached')</small>
                    </div>
                    <div class="icon-box variant-orange mb-0">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                </div>

                <!-- Target Goal -->
                <div class="premium-card stat-item">
                    <div class="stat-info">
                        <h6 class="text-muted text-uppercase">@lang('Target Goal')</h6>
                        <h3>{{ showAmount($maxCap, 2, currencyFormat: false) }} {{ $general->cur_text }}</h3>
                        <small class="text-primary">@lang('Maximum Earnings')</small>
                    </div>
                    <div class="icon-box variant-purple mb-0">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
                    </div>
                </div>

                <!-- Progress Stat -->
                 <div class="premium-card stat-item">
                    <div class="stat-info">
                        <h6 class="text-muted text-uppercase">@lang('Progress')</h6>
                        <h3>{{ round($progress, 1) }}%</h3>
                        <small class="text-info">@lang('Completion Rate')</small>
                    </div>
                    <div class="icon-box variant-blue mb-0">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" /></svg>
                    </div>
                </div>
            </div>

            <!-- Progress Card (Full Width) -->
            <div class="premium-card mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">@lang('Plan Progress Detail')</h5>
                </div>
                
                <div class="progress" style="height: 12px; background: rgba(128,128,128,0.1); border-radius: 10px;">
                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-gradient-primary" role="progressbar" style="width: {{ $progress }}%" aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                
                <p class="mt-3 text-muted small mb-0">
                    @lang('You have received') <strong>{{ showAmount($receivedAmount, 2, currencyFormat: false) }} {{ $general->cur_text }}</strong> @lang('out of') <strong>{{ showAmount($maxCap, 2, currencyFormat: false) }} {{ $general->cur_text }}</strong>. @lang('Daily profit will stop automatically once 100% is reached.')
                </p>
            </div>

            <!-- History Table -->
            <div class="premium-card">
                <h5 class="mb-4">@lang('Profit History')</h5>
                <div class="table-responsive">
                    <table class="table table-custom">
                        <thead>
                            <tr style="border-bottom: 1px solid rgba(128,128,128,0.1);">
                                <th class="text-muted">@lang('Transaction ID')</th>
                                <th class="text-muted">@lang('Date')</th>
                                <th class="text-muted">@lang('Amount')</th>
                                <th class="text-muted">@lang('Details')</th>
                                <th class="text-muted">@lang('Status')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transactions as $trx)
                                <tr style="border-bottom: 1px solid rgba(128,128,128,0.05);">
                                    <td class="fw-bold">#{{ $trx->trx }}</td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span>{{ showDateTime($trx->created_at, 'Y-m-d') }}</span>
                                            <small class="text-muted">{{ showDateTime($trx->created_at, 'h:i A') }}</small>
                                        </div>
                                    </td>
                                    <td class="fw-bold text-success">+{{ showAmount($trx->amount, currencyFormat: false) }} {{ $general->cur_text }}</td>
                                    <td class="text-muted">{{ __($trx->details) }}</td>
                                    <td><span class="badge bg-success bg-opacity-25 text-success">@lang('Received')</span></td>
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
        </div>
    </div>

    <style>
        /* Grid Layout */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        /* Wallet Overview Styling (Matched to Dashboard) */
        .wallet-overview {
            background: linear-gradient(135deg, #1a1f3c 0%, #2a2f5c 100%);
            border-radius: 20px;
            padding: 25px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            border: 1px solid rgba(255,255,255,0.05);
        }
        .wallet-overview::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(68, 102, 242, 0.2) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
        }
        .wallet-balance-title {
            color: rgba(255,255,255,0.7);
            font-size: 14px;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .wallet-balance-amount {
            color: #fff;
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 5px;
        }
        .wallet-balance-sub {
            color: rgba(255,255,255,0.5);
            font-size: 13px;
        }

        /* Ensure table styling matches theme */
        .table-custom {
            color: var(--text-primary) !important;
        }
        .table-custom th, .table-custom td {
            background: transparent !important;
            vertical-align: middle;
            padding: 15px;
            color: inherit !important;
            white-space: nowrap;
        }
        /* Ensure headings and other text inherit correct color */
        h1, h2, h3, h4, h5, h6, .premium-title {
            color: var(--text-primary) !important;
        }
        .bg-gradient-primary {
            background: linear-gradient(135deg, var(--color-primary) 0%, #8b5cf6 100%);
        }
        
        /* Mobile Full Width Adjustments to match dashboard */
        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: 1fr !important;
                display: flex;
                flex-direction: column;
                gap: 15px;
            }
            
            .stat-item, .premium-card {
                width: 100% !important;
                margin-bottom: 10px;
            }

            .container-fluid.px-4 {
                padding-left: 0 !important;
                padding-right: 0 !important;
            }
            .inner-dashboard-container {
                padding-left: 0 !important;
                padding-right: 0 !important;
            }
            .premium-card, .wallet-overview {
                border-radius: 0 !important;
                border-left: none !important;
                border-right: none !important;
            }
        }
    </style>
@endsection

@push('script')
    <!-- Include Modern Dashboard Initialization -->
    @include($activeTemplate . 'js.modern-dashboard-init')

    <!-- Include Icon Enhancer -->
    @include($activeTemplate . 'js.icon-enhancer')
@endpush
